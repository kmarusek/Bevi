<?php
/**
 * Media Deduper Pro: Gutenberg parser class.
 *
 * @package Media_Deduper_Pro
 */

// Disallow direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Detect & replace references in Gutenberg content
 */
class MDD_Parse_Gutenberg extends MDD_Parse_Base {

	/**
	 * Constructor. Add reference/detection/replacement filters and post/meta save hooks.
	 */
	public function __construct() {
		// Add hooks for WYSIWYG fields.
		add_filter( 'mdd_detect_type__wysiwyg', array( $this, 'detect_gutenberg_ids' ), 10, 2 );
		add_filter( 'mdd_detect_type__wysiwyg', array( $this, 'detect_gutenberg_media_ids' ), 10, 2 );
		add_filter( 'mdd_detect_type__wysiwyg', array( $this, 'detect_gutenberg_multi_ids' ), 10, 2 );
		add_filter( 'mdd_detect_type__wysiwyg', array( $this, 'detect_gutenberg_gallery_attributes' ), 10, 2 );
		add_filter( 'mdd_replace_type__wysiwyg', array( $this, 'replace_gutenberg_ids' ), 10, 3 );
		add_filter( 'mdd_replace_type__wysiwyg', array( $this, 'replace_gutenberg_media_ids' ), 10, 3 );
		add_filter( 'mdd_replace_type__wysiwyg', array( $this, 'replace_gutenberg_multi_ids' ), 10, 3 );
		add_filter( 'mdd_replace_type__wysiwyg', array( $this, 'replace_gutenberg_gallery_attributes' ), 10, 3 );
	}

	/**
	 * Return a regex that matches an image ID in the gutenberg image blocks.
	 *
	 * @param int|null $id An image ID.
	 * @return string A regular expression for use with preg_match() or
	 *                preg_replace().
	 */
	public function get_gutenberg_id_regex( $id = null ) {

		if ( is_null( $id ) ) {
			// If no ID was given, add a named capturing group for preg_match().
			$id_pattern = '(?P<id>\d+)';
		} else {
			// If an ID was given, sanitize it.
			$id_pattern = absint( $id );
		}

		return '/'
			. '(' // Begin capturing group #1: beginning of Gutenberg-style HTML comment.
			. '<!-- wp:(?:cover|image) {' // Gutenberg block name.
			. '(?:[^}]*,)?' // Any arbitrary JSON properties.
			. '"id":' // JSON ID property declaration.
			. ')' // End capturing group #1.
			. $id_pattern // ID property value.
			. '(' // Begin capturing group #2: end of Gutenberg-style HTML comment.
			. '(?:,[^}]*)?' // More arbitrary JSON properties.
			. '} -->' // End comment.
			. ')' // End capturing group #2.
			. '/';
	}

	/**
	 * Detect the ID in the gutenberg image blocks.
	 *
	 * @uses MDD_Reference_Handler::get_gutenberg_id_regex()
	 *
	 * @param array  $refs    Array to which referenced IDs will be added.
	 * @param string $subject The value in which to look for the gutenberg image blocks ids.
	 * @return array An array of attachment IDs (as integers).
	 */
	public function detect_gutenberg_ids( $refs, $subject ) {

		// If this isn't a string, pass $refs along untouched.
		if ( ! is_string( $subject ) ) {
			return $refs;
		}

		$re = $this->get_gutenberg_id_regex();

		// Search for matches.
		preg_match_all( $re, $subject, $matches );

		// Cast all matches as integers.
		$ids = array_map( 'absint', $matches['id'] );

		// Add $ids to $refs, and weed out duplicate IDs.
		$refs = array_unique( array_merge( $refs, $ids ) );

		return $refs;
	}

	/**
	 * Replace an old image ID in the gutenberg image blocks.
	 *
	 * @uses MDD_Reference_Handler::get_gutenberg_id_regex()
	 *
	 * @param string $subject The value in which to look in the gutenberg image blocks ids.
	 * @param int    $old_id  The attachment ID to replace with $new_id.
	 * @param int    $new_id  The attachment ID to replace $old_id with.
	 */
	public function replace_gutenberg_ids( $subject, $old_id, $new_id ) {
		$re = $this->get_gutenberg_id_regex( $old_id );
		return preg_replace( $re, "\${1}$new_id\${2}", $subject );
	}

	/**
	 * Return a regex that matches an media ID in the gutenberg media-text blocks.
	 *
	 * @param int|null $id An image ID.
	 * @return string A regular expression for use with preg_match() or
	 *                preg_replace().
	 */
	public function get_gutenberg_media_id_regex( $id = null ) {

		if ( is_null( $id ) ) {
			// If no ID was given, add a named capturing group for preg_match().
			$id_pattern = '(?P<id>\d+)';
		} else {
			// If an ID was given, sanitize it.
			$id_pattern = absint( $id );
		}

		return '/'
			. '(' // Begin capturing group #1: beginning of Gutenberg-style HTML comment.
			. '<!-- wp:media-text {' // Gutenberg block name.
			. '(?:[^}]*,)?' // Any arbitrary JSON properties.
			. '"mediaId":' // JSON ID property declaration.
			. ')' // End capturing group #1.
			. $id_pattern // ID property value.
			. '(' // Begin capturing group #2: end of Gutenberg-style HTML comment.
			. '(?:,[^}]*)?' // More arbitrary JSON properties.
			. '} -->' // End comment.
			. ')' // End capturing group #2.
			. '/';
	}

	/**
	 * Detect the ID in the gutenberg media-text blocks.
	 *
	 * @uses MDD_Reference_Handler::get_gutenberg_media_id_regex()
	 *
	 * @param array  $refs    Array to which referenced IDs will be added.
	 * @param string $subject The value in which to look for the gutenberg image blocks ids.
	 * @return array An array of attachment IDs (as integers).
	 */
	public function detect_gutenberg_media_ids( $refs, $subject ) {

		// If this isn't a string, pass $refs along untouched.
		if ( ! is_string( $subject ) ) {
			return $refs;
		}

		$re = $this->get_gutenberg_media_id_regex();

		// Search for matches.
		preg_match_all( $re, $subject, $matches );

		// Cast all matches as integers.
		$ids = array_map( 'absint', $matches['id'] );

		// Add $ids to $refs, and weed out duplicate IDs.
		$refs = array_unique( array_merge( $refs, $ids ) );

		return $refs;
	}

	/**
	 * Replace an old image ID in the gutenberg media-text blocks.
	 *
	 * @uses MDD_Reference_Handler::get_gutenberg_media_id_regex()
	 *
	 * @param string $subject The value in which to look in the gutenberg image blocks ids.
	 * @param int    $old_id  The attachment ID to replace with $new_id.
	 * @param int    $new_id  The attachment ID to replace $old_id with.
	 */
	public function replace_gutenberg_media_ids( $subject, $old_id, $new_id ) {
		$re = $this->get_gutenberg_media_id_regex( $old_id );
		return preg_replace( $re, "\${1}$new_id\${2}", $subject );
	}

	/**
	 * Return a regex that matches an image ID in the gutenberg gallery blocks.
	 *
	 * @param int|null $id An image ID.
	 * @return string A regular expression for use with preg_match() or
	 *                preg_replace().
	 */
	public function get_gutenberg_multi_id_regex( $id = null ) {

		if ( is_null( $id ) ) {
			// If no ID was given, add a named capturing group for preg_match().
			$id_pattern = '(?P<id>\d+)';
		} else {
			// If an ID was given, sanitize it.
			$id_pattern = absint( $id );
		}

		return '/'
			. '(' // Begin capturing group #1: beginning of Gutenberg-style HTML comment.
			. '<!-- wp:gallery {' // Gutenberg block name.
			. '(?:[^}]*)?' // Any arbitrary JSON properties.
			. '"ids":(?:[^}]*)?' // JSON ID property declaration.
			. ')' // End capturing group #1.
			. $id_pattern // ID property value.
			. '(' // Begin capturing group #2: end of Gutenberg-style HTML comment.
			. '(?:[^}]*)?' // More arbitrary JSON properties.
			. '} -->' // End comment.
			. ')' // End capturing group #2.
			. '/';
	}


	/**
	 * Detect the ID in the gutenberg gallery blocks.
	 *
	 * @uses MDD_Reference_Handler::get_gutenberg_id_regex()
	 *
	 * @param array  $refs    Array to which referenced IDs will be added.
	 * @param string $subject The value in which to look for the gutenberg image blocks ids.
	 * @return array An array of attachment IDs (as integers).
	 */
	public function detect_gutenberg_multi_ids( $refs, $subject ) {

		// If this isn't a string, pass $refs along untouched.
		if ( ! is_string( $subject ) ) {
			return $refs;
		}

		$re = $this->get_gutenberg_multi_id_regex();

		// Search for matches.
		preg_match_all( $re, $subject, $matches );

		// Cast all matches as integers.
		$ids = array_map( 'absint', $matches['id'] );

		// Add $ids to $refs, and weed out duplicate IDs.
		$refs = array_unique( array_merge( $refs, $ids ) );

		return $refs;
	}

	/**
	 * Replace an old image ID in the gutenberg gallery blocks.
	 *
	 * @uses MDD_Reference_Handler::get_gutenberg_id_regex()
	 *
	 * @param string $subject The value in which to look in the gutenberg image blocks ids.
	 * @param int    $old_id  The attachment ID to replace with $new_id.
	 * @param int    $new_id  The attachment ID to replace $old_id with.
	 */
	public function replace_gutenberg_multi_ids( $subject, $old_id, $new_id ) {
		$re = $this->get_gutenberg_multi_id_regex( $old_id );
		return preg_replace( $re, "\${1}$new_id\${2}", $subject );
	}

	/**
	 * Detect caption shortcodes with ID the gutenberg gallery data-id attribute.
	 *
	 * @uses MDD_Reference_Handler::get_gutenberg_gallery_id_regex()
	 *
	 * @param array  $refs    Array to which referenced IDs will be added.
	 * @param string $subject The value in which to look for the gutenberg gallery data-id attribute.
	 * @return array An array of attachment IDs (as integers).
	 */
	public function detect_gutenberg_gallery_attributes( $refs, $subject ) {

		// If this isn't a string, pass $refs along untouched.
		if ( ! is_string( $subject ) ) {
			return $refs;
		}

		// Search for Gutenberg galleries and grab their content.
		preg_match_all( '/<!-- wp:gallery {(?:(?<=\{)(.*?)(?=\}))} -->(?:\s*)(?P<gallery>.*?)(?:\s*)<!-- \/wp:gallery -->/', $subject, $matches );

		// Within each Gutenberg gallery, search for 'data-id' attributes.
		foreach ( $matches['gallery'] as $gallery ) {

			// Get all data-id attribute values (if any).
			preg_match_all( '/data-id="(?P<id>\d+)"|data-link="(?P<url>[^"]+)"/', $gallery, $attribute_matches );

			$id_values = array_filter( $attribute_matches['id'] );
			$url_values = array_filter( $attribute_matches['url'] );

			// Cast all ID values as integers.
			if ( ! empty( $id_values ) ) {
				$id_values = array_map( 'absint', $id_values );
			}

			// Get post IDs from data-url values.
			if ( ! empty( $url_values ) ) {
				$url_values = array_map( 'url_to_postid', $url_values );
			}

			// Add $ids to $refs, and weed out duplicate IDs.
			$refs = array_unique( array_merge( $refs, $id_values, $url_values ) );
		}//end foreach

		return $refs;
	}

	/**
	 * Replace an old image ID in the [caption] shortcode.
	 *
	 * @uses MDD_Reference_Handler::get_gutenberg_gallery_id_regex()
	 *
	 * @param string $subject The value in which to look for the gutenberg gallery data-id attributes.
	 * @param int    $old_id  The attachment ID to replace with $new_id.
	 * @param int    $new_id  The attachment ID to replace $old_id with.
	 */
	public function replace_gutenberg_gallery_attributes( $subject, $old_id, $new_id ) {

		// Search for Gutenberg galleries and grab their content.
		preg_match_all( '/<!-- wp:gallery {(?:(?<=\{)(.*?)(?=\}))} -->(?:\s*)(?P<gallery>.*?)(?:\s*)<!-- \/wp:gallery -->/', $subject, $matches );

		// Within each Gutenberg gallery, search for and replace 'data-id' attributes.
		foreach ( $matches['gallery'] as $gallery ) {

			// Replace data-id attributes in the gallery code.
			$new_gallery = str_replace( "data-id=\"$old_id\"", "data-id=\"$new_id\"", $gallery );

			$old_permalink = get_permalink( $old_id );
			$new_permalink = get_permalink( $new_id );

			// Replace data-link attributes in the gallery code.
			$new_gallery = str_replace( "data-link=\"$old_permalink\"", "data-link=\"$new_permalink\"", $new_gallery );

			// Replace the old gallery code with the new gallery code.
			$subject = str_replace( $gallery, $new_gallery, $subject );
		}

		return $subject;
	}
}
