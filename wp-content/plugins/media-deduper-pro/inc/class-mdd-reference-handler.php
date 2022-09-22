<?php
/**
 * Media Deduper Pro: reference handler class.
 *
 * @package Media_Deduper_Pro
 */

// Disallow direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helper class for finding/replacing references to attachment posts.
 */
class MDD_Reference_Handler {

	/**
	 * Holder for all loaded parser class instances.
	 *
	 * @var array
	 */
	private $parsers = array();

	/**
	 * True if track_post_meta() is currently running. Used to prevent recursion.
	 *
	 * @var bool
	 */
	public $is_tracking_meta = false;

	/**
	 * An array of IDs for posts that are currently being deleted. Used to prevent
	 * unnecessary calls to track_deleted_meta().
	 *
	 * @var array
	 */
	public $posts_being_deleted = array();

	/**
	 * An array of attachment URLs to replace. Used by
	 * MDD_Reference_Handler::replace_mutli_url().
	 *
	 * @var array
	 */
	public $urls_to_replace = array();

	/**
	 * Constructor. Add detection/replacement filters and post/meta save hooks.
	 */
	public function __construct() {

		// Always load the base & core parsers!
		require_once( 'parsers/class-mdd-parse-base.php' );
		require_once( 'parsers/class-mdd-parse-core.php' );
		$this->parsers['core'] = new MDD_Parse_Core();

		// Always load Gutenberg parsers (hoping plugin users are up to date)
		require_once( 'parsers/class-mdd-parse-gutenberg.php' );
		$this->parsers['gutenberg'] = new MDD_Parse_Gutenberg();

		// Maybe load parser: Advanced Custom Fields (ACF)
		if ( class_exists( 'ACF' ) ) {
			require_once( 'parsers/class-mdd-parse-acf.php' );
			$this->parsers['acf'] = new MDD_Parse_ACF();
		}

		// Maybe load parser: Yoast SEO
		if ( defined( 'WPSEO_VERSION' ) ) {
			require_once( 'parsers/class-mdd-parse-yoast.php' );
			$this->parsers['yoast'] = new MDD_Parse_Yoast();
		}

		// When switch_to_blog() or restore_current_blog() is called, clear out
		// site-specific data.
		add_action( 'switch_blog', array( $this, 'switch_blog' ) );

		// When a post meta field is set, updated, or deleted, check for attachment
		// references.
		add_action( 'added_post_meta',    array( $this, 'track_post_meta' ), 10, 3 );
		add_action( 'updated_post_meta',  array( $this, 'track_post_meta' ), 10, 3 );
		add_action( 'deleted_post_meta',  array( $this, 'track_post_meta' ), 10, 3 );

		// When a post is created, updated, or deleted, check for references within
		// post fields (or delete previously indexed reference data).
		add_action( 'wp_insert_post',     array( $this, 'track_post_props' ) );
		add_action( 'before_delete_post', array( $this, 'track_deleted_post' ) );
		add_action( 'after_delete_post',  array( $this, 'done_deleting_post' ) );
	}

	/**
	 * Get fields in which to search for and/or replace attachment references.
	 *
	 * @param int|WP_Post $post A post ID or post object.
	 * @return An array describing post fields. See the documentation for the
	 *         `mdd_get_reference_fields` hook.
	 * @see mdd_get_reference_fields
	 */
	public function get_post_fields( $post ) {

		$post = get_post( $post );

		// If $post wasn't a valid post or post ID, return an empty array.
		if ( ! $post ) {
			return array();
		}

		// Initialize the array of fields.
		$fields = array(
			// Always track post content.
			'prop:post_content' => 'wysiwyg',
			// Excerpts mostly won't contain images (or markup at all), but some themes support it. And
			// WooCommerce even uses a WYSIWYG editor with an Add Media button, so better safe than sorry.
			'prop:post_excerpt' => 'wysiwyg',
		);

		// If this post type supports featured images, add the featured image field.
		if ( post_type_supports( $post->post_type, 'thumbnail' ) ) {
			$fields['meta:_thumbnail_id'] = 'int';
		}

		// If this is a WooCommerce product or product variation, add the gallery
		// image field.
		if ( in_array( $post->post_type, array( 'product', 'product_variation' ), true ) ) {
			$fields['meta:_product_image_gallery'] = 'multi_int';
		}

		/**
		 * Filter the list of post fields that may contain attachment references.
		 *
		 * @param array       $fields An array describing post fields. Array keys start with either
		 *                            'meta' or 'prop' (denoting a post meta field or post object
		 *                            property, respectively), followed by a colon character, followed
		 *                            by the field's name (e.g. '_thumbnail_id' or 'post_content').
		 *                            Array values are strings indicating the type of data stored in a
		 *                            field.
		 * @param int|WP_Post $post   The post to return fields for.
		 */
		$fields = apply_filters( 'mdd_get_reference_fields', $fields, $post );

		return $fields;
	}

	/**
	 * Get the list of post types in which to search for and/or replace attachment
	 * references.
	 */
	public function get_post_types() {

		// Get all post type slugs.
		$types = get_post_types(
			array(
				'public' => true,
			)
		);

		// Don't track attachments.
		array_diff( $types, array( 'attachment' ) );

		/**
		 * Filter the list of post types that may contain attachment references.
		 *
		 * @param array $types An array of post type slugs. Defaults to all public post types except
		 *                     'attachment'.
		 */
		return apply_filters( 'mdd_get_reference_post_types', $types );
	}

	/**
	 * Determine which attachments, if any, are referenced in a given field.
	 *
	 * @param int|WP_Post $post   A post ID or post object.
	 * @param string      $field  A string describing describing the field to check. See
	 *                            MDD_Reference_Handler::get_post_fields().
	 * @param string      $type   The type of value expected in $field. This
	 *                            determines which detection filters are applied
	 *                            to the value.
	 * @return array An array of referenced attachment IDs.
	 */
	public function detect_in_post_field( $post, $field, $type ) {
		$post = get_post( $post );

		// If $post wasn't a valid post or post ID, return an empty array.
		if ( ! $post ) {
			return array();
		}

		// Initialize an array where we'll store referenced attachment IDs.
		$refs = array();

		// Get the source and name of the field.
		list( $field_source, $field_name ) = explode( ':', $field, 2 );

		// Get field value(s) based on field source.
		if ( 'meta' === $field_source ) {
			// Get all values for the given meta key.
			$values = get_post_meta( $post->ID, $field_name );
			// Return an empty array if no values were found.
			if ( empty( $values ) ) {
				return array();
			}
		} elseif ( 'prop' === $field_source ) {
			// Return an empty array if the named object property doesn't really exist
			// or is empty.
			if ( empty( $post->$field_name ) ) {
				return array();
			}
			// Put property value in an array, so we can use the same detection code
			// for both meta values and object properties.
			$values = array( $post->$field_name );
		}

		// Check value(s) for attachment references.
		foreach ( $values as $value ) {

			// If $value is serialized, unserialize it.
			$value = maybe_unserialize( $value );

			/**
			 * Filter the list of attachment IDs referenced by a field.
			 *
			 * The dynamic portion of the hook name, `$type`, refers to a field data type, i.e. a value in
			 * the array returned by the `mdd_get_reference_fields` filter, such as 'wysiwyg', 'int', or
			 * 'multi_int'.
			 *
			 * @param array $refs  An array of attachment IDs to filter.
			 * @param mixed $value The value of the field.
			 */
			$refs = apply_filters( "mdd_detect_type__$type",   $refs, $value );

			/**
			 * Filter the list of attachment IDs referenced by a field.
			 *
			 * The dynamic portion of the hook name, `$field`, refers to a field descriptor, i.e. a key in
			 * the array returned by the `mdd_get_reference_fields` filter, such as 'prop:post_content' or
			 * 'meta:_thumbnail_id'.
			 *
			 * @param array $refs  An array of attachment IDs to filter.
			 * @param mixed $value The value of the field.
			 */
			$refs = apply_filters( "mdd_detect_field__$field", $refs, $value );
		}//end foreach

		// Return referenced IDs, without duplicates.
		return array_unique( $refs );
	}

	/**
	 * Check whether a given attachment post is referenced anywhere on the site.
	 *
	 * @param int $attachment_id The ID of the attachment to check.
	 * @return bool True if the attachment is referenced in another post, false if
	 * not.
	 */
	public function attachment_is_referenced( $attachment_id ) {
		return (bool) get_post_meta( $attachment_id, '_mdd_referenced_by', true );
	}

	/**
	 * Replace all references to one attachment with references to another
	 * attachment.
	 *
	 * @uses MDD_Reference_Handler::replace_in_field()
	 *
	 * @param int $old_id The ID of the attachment to replace.
	 * @param int $new_id The ID of the attachment that should replace $old_id.
	 */
	public function replace_all_references( $old_id, $new_id ) {

		// Get all references to the old attachment.
		$refs = get_post_meta( $old_id, '_mdd_referenced_by', true );

		// Bail if the _mdd_referenced_by field doesn't exist or isn't an array.
		if ( ! is_array( $refs ) ) {
			return;
		}

		// Iterate over all posts containing references to this attachment.
		foreach ( $refs as $post_id => $post_fields ) {

			// Get data types of all trackable fields for this post.
			$trackable_fields = $this->get_post_fields( $post_id );

			foreach ( $post_fields as $field ) {

				// If this field isn't among the known trackable fields, skip it.
				if ( ! isset( $trackable_fields[ $field ] ) ) {
					continue;
				}

				// If this field is trackable, replace any references in it.
				$this->replace_in_field( $post_id, $field, $trackable_fields[ $field ], $old_id, $new_id );
			}
		}
	}

	/**
	 * Update a field, replacing references to one attachment with references to
	 * another attachment.
	 *
	 * @param int|WP_Post $post   A post ID or post object.
	 * @param string      $field  A string describing the field to check. See
	 *                            MDD_Reference_Handler::get_post_fields().
	 * @param string      $type   The type of value expected in $field. This
	 *                            determines which replacement filters are applied
	 *                            to the value.
	 * @param int         $old_id The ID of the attachment to replace.
	 * @param int         $new_id The ID of the attachment that should replace
	 *                            $old_id.
	 */
	public function replace_in_field( $post, $field, $type, $old_id, $new_id ) {
		$post = get_post( $post );

		// If $post wasn't a valid post or post ID, bail.
		if ( ! $post ) {
			return;
		}

		// Sanitize old and new ID arguments.
		$old_id = absint( $old_id );
		$new_id = absint( $new_id );

		// Get the source and name of the field.
		list( $field_source, $field_name ) = explode( ':', $field, 2 );

		// Update field value(s) based on field source.
		if ( 'meta' === $field_source ) {

			// Get all values for the given meta key.
			$values = get_post_meta( $post->ID, $field_name );
			// Bail if no values were found.
			if ( empty( $values ) ) {
				return;
			}

			foreach ( $values as $old_value ) {

				// If the value was serialized, unserialize it.
				$old_value = maybe_unserialize( $old_value );

				// Initialize the new value.
				$new_value = $old_value;

				/**
				 * Filter the value of a field, replacing references to one attachment with references to
				 * another.
				 *
				 * The dynamic portion of the hook name, `$type`, refers to a field data type, i.e. a value
				 * in the array returned by the `mdd_get_reference_fields` filter, such as 'wysiwyg', 'int',
				 * or 'multi_int'.
				 *
				 * @param mixed $new_value The field value to filter.
				 * @param int   $old_id    The ID of the 'old' attachment, references to which should be
				 *                         replaced.
				 * @param int   $new_id    The ID of the 'new' attachment, which should replace the 'old'
				 *                         attachment wherever it's referenced.
				 */
				$new_value = apply_filters( "mdd_replace_type__$type",   $new_value, $old_id, $new_id );

				/**
				 * Filter the value of a field, replacing references to one attachment with references to
				 * another.
				 *
				 * The dynamic portion of the hook name, `$field`, refers to a field descriptor, i.e. a key
				 * in the array returned by the `mdd_get_reference_fields` filter, such as
				 * 'prop:post_content' or 'meta:_thumbnail_id'.
				 *
				 * @param mixed $new_value The field value to filter.
				 * @param int   $old_id    The ID of the 'old' attachment, references to which should be
				 *                         replaced.
				 * @param int   $new_id    The ID of the 'new' attachment, which should replace the 'old'
				 *                         attachment wherever it's referenced.
				 */
				$new_value = apply_filters( "mdd_replace_field__$field", $new_value, $old_id, $new_id );

				// If the value has changed, update the meta field (and if there's more
				// than one metadata entry for this key, only update those whose value
				// is $old_value).
				update_post_meta( $post->ID, $field_name, $new_value, $old_value );
			}//end foreach
		} elseif ( 'prop' === $field_source ) {

			// Bail if the named object propery doesn't really exist or is empty.
			if ( empty( $post->$field_name ) ) {
				return;
			}

			// Get the current field value.
			$old_value = $post->$field_name;

			/** This filter is documented in media-deduper-pro/inc/class-mdd-reference-handler.php */
			$post->$field_name = apply_filters( "mdd_replace_type__$type",   $post->$field_name, $old_id, $new_id );
			/** This filter is documented in media-deduper-pro/inc/class-mdd-reference-handler.php */
			$post->$field_name = apply_filters( "mdd_replace_field__$field", $post->$field_name, $old_id, $new_id );

			// If the value has changed, update the post.
			// TODO: only call wp_update_post() once per post, instead of once per field.
			if ( $post->$field_name !== $old_value ) {
				wp_update_post( $post );
			}
		}//end if
	}

	/**
	 * Clear out site-specific data.
	 */
	public function switch_blog() {
		$this->attachment_url_regex = false;
	}

	/**
	 * Track references to attachments in all fields (properties and metadata) of
	 * a given post.
	 *
	 * @uses MDD_Reference_Handler::get_post_fields()
	 * @uses MDD_Reference_Handler::track_post_fields()
	 *
	 * @param int $post_id The ID of the post to track references in.
	 */
	public function track_post( $post_id ) {

		// Get the list of all fields for this post that should be checked for
		// references.
		$tracked_fields = $this->get_post_fields( $post_id );

		// If there are no fields to track, store an empty _mdd_references value so
		// we know we've checked this post already.
		if ( empty( $tracked_fields ) ) {
			update_post_meta( $post_id, '_mdd_references', array() );
			return;
		}

		// Iterate over tracked fields.
		foreach ( $tracked_fields as $field => $method ) {
			// Track and store references in this field.
			$this->track_post_field( $post_id, $field, $method );
		}
	}

	/**
	 * Track references to attachments in the properties of a newly added or
	 * upated post.
	 *
	 * @uses MDD_Reference_Handler::get_post_fields()
	 * @uses MDD_Reference_Handler::track_post_field()
	 *
	 * @param int $post_id The ID of the post to track references in.
	 */
	public function track_post_props( $post_id ) {

		// Don't track properties on revisions, because we can't store metadata for them.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Get the list of all fields for this post that should be checked for
		// references.
		$tracked_fields = $this->get_post_fields( $post_id );

		// If there are no fields to track, bail. This way we don't fire the `mdd_tracked_post_props`
		// hook unnecessarily, and thus avoid unnecessarily clearing transients.
		if ( empty( $tracked_fields ) ) {
			return;
		}

		// Iterate over tracked fields.
		foreach ( $tracked_fields as $field => $method ) {

			// Get the type and name of the field.
			list( $field_source, $field_name ) = explode( ':', $field, 2 );

			// If this field isn't a property, skip it.
			if ( 'prop' !== $field_source ) {
				continue;
			}

			// Track and store references in this field.
			$this->track_post_field( $post_id, $field, $method );
		}

		/**
		 * Fires after a new post has been inserted and properties have been tracked for the post.
		 *
		 * @param int $post_id The ID of the post that has just been tracked.
		 */
		do_action( 'mdd_tracked_post_props', $post_id );
	}

	/**
	 * Track references to attachments in a newly added or updated post meta
	 * field.
	 *
	 * @uses MDD_Reference_Handler::get_post_fields()
	 * @uses MDD_Reference_Handler::track_post_field()
	 *
	 * @param int    $meta_id    The ID(s) of the meta value in the postmeta
	 *                           table. Passed in by update_metadata(), ignored
	 *                           here.
	 * @param int    $post_id    The post whose metadata is being set/changed.
	 * @param string $meta_key   The meta key whose value has been set/changed.
	 */
	public function track_post_meta( $meta_id, $post_id, $meta_key ) {

		// Don't track meta on revisions, because we can't store metadata for them. Nor track mdd post meta fields.
		if ( wp_is_post_revision( $post_id ) || in_array( $meta_key, array( '_mdd_references', '_mdd_referenced_by', '_mdd_referenced_by_count' ), true ) ) {
			return;
		}

		// Save time by bailing if this action was fired while track_post_meta() was running. None of
		// the other meta values that track_post_meta() sets need to be tracked.
		if ( $this->is_tracking_meta ) {
			return;
		}

		// Save time by bailing if the post whose metadata has changed is actually
		// being deleted. All references related to the post will be untracked later
		// by MDD_Reference_Handler::track_deleted_post().
		if ( in_array( $post_id, $this->posts_being_deleted, true ) ) {
			return;
		}

		// Get the list of all fields for this post that should be checked for
		// references.
		$tracked_fields = $this->get_post_fields( $post_id );

		// If this field isn't among the fields that are worth tracking, bail.
		if ( ! isset( $tracked_fields[ "meta:$meta_key" ] ) ) {
			return;
		}

		// Detect and store references found in this field.
		$this->track_post_field( $post_id, "meta:$meta_key", $tracked_fields[ "meta:$meta_key" ] );

		/**
		 * Fires after post metadata has been changed and the updated metadata has been tracked.
		 *
		 * @param int    $meta_id  The ID of the added/updated/removed row in the postmeta table.
		 * @param int    $post_id  The ID of the post to which the metadata belongs.
		 * @param string $meta_key The key for the metadata that has been added/updated/removed.
		 */
		do_action( 'mdd_tracked_post_meta', $meta_id, $post_id, $meta_key );
	}

	/**
	 * Detect references in a given post field, and store references in post and
	 * attachment metadata.
	 *
	 * @uses MDD_Reference_Handler::detect_in_post_field()
	 * @uses MDD_Reference_Handler::track_post_reference()
	 * @uses MDD_Reference_Handler::untrack_post_reference()
	 *
	 * @param int    $post_id The ID of the post in which to look for references.
	 * @param string $field   String describing the field in which to look for
	 *                        references.
	 * @param string $type    The type of value expected in $field.
	 */
	public function track_post_field( $post_id, $field, $type ) {

		// If this is a meta field, set a flag indicating that we're tracking a meta
		// field.
		if ( 0 === strpos( $field, 'meta:' ) ) {
			$this->is_tracking_meta = true;
		}

		// Get referenced attachments in this field, using the field type specified
		// in $type.
		$refs = $this->detect_in_post_field( $post_id, $field, $type );

		// Get the list of attachments, if any, that were referenced in this post,
		// prior to this update.
		$refs_by_field = get_post_meta( $post_id, '_mdd_references', true );

		// If no references were previously stored for this post, initialize an
		// array of references.
		if ( empty( $refs_by_field ) ) {
			$refs_by_field = array();
		}

		// Get the list of attachments, if any, that were referenced in this field.
		$old_refs = isset( $refs_by_field[ $field ] ) ? $refs_by_field[ $field ] : array();

		// Iterate over attachment IDs currently referenced by this field.
		foreach ( $refs as $attachment_id ) {
			$this->track_post_reference( $attachment_id, $post_id, $field );
		}

		// Get an array of all attachments that _were_ referenced by this field
		// until it was updated, but aren't anyore.
		$removed_refs = array_diff( $old_refs, $refs );

		// Iterate over attachment IDs no longer referenced in this field.
		foreach ( $removed_refs as $attachment_id ) {
			$this->untrack_post_reference( $attachment_id, $post_id, $field );
		}

		// Update this post's _mdd_references value.
		$refs_by_field[ $field ] = $refs;
		update_post_meta( $post_id, '_mdd_references', $refs_by_field );

		// If this was a meta field, clear the is_tracking_meta flag.
		if ( 0 === strpos( $field, 'meta:' ) ) {
			$this->is_tracking_meta = false;
		}
	}

	/**
	 * Update an attachment's _mdd_referenced_by value to reflect a newly added
	 * reference in a post field.
	 *
	 * @param int    $attachment_id The ID of the attachment.
	 * @param int    $post_id       The ID of the post.
	 * @param string $field         String describing the post field that no
	 *                              longer references $attachment_id.
	 */
	function track_post_reference( $attachment_id, $post_id, $field ) {

		// Get the attachment's _mdd_referenced_by value.
		$fields_by_post = get_post_meta( $attachment_id, '_mdd_referenced_by', true );

		// If no references were previously stored for this attachment, initialize
		// an array of references.
		if ( empty( $fields_by_post ) ) {
			$fields_by_post = array();
		}

		// Get the array of the posts's fields (if any) containing known references
		// to the attachment.
		$fields = isset( $fields_by_post[ $post_id ] ) ? $fields_by_post[ $post_id ] : array();

		// If $field is already among them, bail -- we don't need to add anything.
		if ( in_array( $field, $fields, true ) ) {
			// try and update reference by count when we need to bail and it needs updating
			$this->update_referenced_by_count( $attachment_id, $fields_by_post );
			return;
		}

		// Add $field to the array of fields referencing this attachment.
		$fields[] = $field;

		// Remove duplicate fields.
		$fields = array_unique( $fields );

		// Update the array of fields in $fields_by_post.
		$fields_by_post[ $post_id ] = $fields;

		// Update the _mdd_referenced_by value.
		update_post_meta( $attachment_id, '_mdd_referenced_by', $fields_by_post );
		$this->update_referenced_by_count( $attachment_id, $fields_by_post );
	}

	/**
	 * Update an attachment's _mdd_referenced_by value to reflect a post field
	 * that no longer references it.
	 *
	 * @param int    $attachment_id The ID of the attachment.
	 * @param int    $post_id       The ID of the post.
	 * @param string $field         String describing the post field that no
	 *                              longer references $attachment_id.
	 */
	function untrack_post_reference( $attachment_id, $post_id, $field ) {

		// Get the attachment's _mdd_referenced_by value.
		$fields_by_post = get_post_meta( $attachment_id, '_mdd_referenced_by', true );

		// If no references were previously stored for this attachment, bail --
		// there's nothing to remove.
		if ( empty( $fields_by_post ) ) {
			return;
		}

		// If $fields_by_post doesn't contain any fields for the post, bail --
		// there's nothing to remove.
		if ( ! isset( $fields_by_post[ $post_id ] ) ) {
			return;
		}

		// Get the array of the post's fields containing known references to the
		// attachment.
		$fields = isset( $fields_by_post[ $post_id ] ) ? $fields_by_post[ $post_id ] : array();

		// Remove $field from the array of fields referencing the attachment.
		$fields = array_diff( $fields, array( $field ) );

		// If this was the only field on the post that referenced the attachment,
		// then remove the key for the post from $fields_by_post altogether.
		// Otherwise, replace the old array with the new one.
		if ( empty( $fields ) ) {
			unset( $fields_by_post[ $post_id ] );
		} else {
			$fields_by_post[ $post_id ] = $fields;
		}

		// Update the _mdd_referenced_by value.
		update_post_meta( $attachment_id, '_mdd_referenced_by', $fields_by_post );
		$this->update_referenced_by_count( $attachment_id, $fields_by_post );
	}

	/**
	 * When WP begins deleting a post, untrack all references to attachments.
	 *
	 * @param int $post_id The ID of the post being deleted.
	 */
	function track_deleted_post( $post_id ) {

		// Don't track revisions, because they won't have metadata anyway.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Update the $posts_being_deleted property.
		$this->posts_being_deleted[] = $post_id;

		// Get the list of attachments referenced by this post.
		$refs_by_field = get_post_meta( $post_id, '_mdd_references', true );

		// Bail if the _mdd_references meta field doesn't exist or isn't an array.
		if ( ! is_array( $refs_by_field ) ) {
			return;
		}

		// Consolidate the list of attachments by field into one list of all
		// attachment IDs.
		$all_refs = array();
		foreach ( $refs_by_field as $refs ) {
			$all_refs = array_unique( array_merge( $all_refs, $refs ) );
		}

		// For each referenced attachment ID, remove the post being deleted from the
		// list of posts/fields referencing the attachment.
		foreach ( $all_refs as $attachment_id ) {
			$fields_by_post = get_post_meta( $attachment_id, '_mdd_referenced_by', true );

			// If this attachment's _mdd_referenced_by field is empty or not an array,
			// then skip it -- there's nothing to update. This might happen if the index
			// is incomplete.
			if ( ! is_array( $fields_by_post ) ) {
				continue;
			}

			unset( $fields_by_post[ $post_id ] );
			update_post_meta( $attachment_id, '_mdd_referenced_by', $fields_by_post );
			$this->update_referenced_by_count( $attachment_id, $fields_by_post );
		}
	}

	/**
	 * Method for updating the _mdd_referenced_by_count for a given attachment.
	 *
	 * @param int   $attachment_id  The ID of the attachment.
	 * @param array $fields_by_post An array of post_ids with the fields references.
	 *
	 * @return void
	 */
	private function update_referenced_by_count( $attachment_id, $fields_by_post ) {
		update_post_meta( $attachment_id, '_mdd_referenced_by_count', count( $fields_by_post ) );
	}

	/**
	 * When WP is done deleting a post, remove its ID from the
	 * $posts_being_deleted property.
	 *
	 * @param int $post_id The ID of the post that was deleted.
	 */
	function done_deleting_post( $post_id ) {

		// If this post's ID isn't in the $posts_being_deleted array, then we didn't do anything to
		// track the deletion of this post. Bail.
		if ( ! in_array( $post_id, $this->posts_being_deleted, true ) ) {
			return;
		}

		$this->posts_being_deleted = array_diff( $this->posts_being_deleted, array( $post_id ) );

		/**
		 * Fires after a post has been deleted and tracked reference data has been removed.
		 *
		 * @param int $post_id The ID of the deleted post.
		 */
		do_action( 'mdd_tracked_deleted_post', $post_id );
	}
}
