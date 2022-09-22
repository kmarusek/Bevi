<?php
/**
 * Media Deduper Pro: Core parser class.
 *
 * @package Media_Deduper_Pro
 */

// Disallow direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Detect & replace references in WP Core and other commonly occurring techniques
 */
class MDD_Parse_Core extends MDD_Parse_Base {

	/**
	 * Constructor. Add reference/detection/replacement filters and post/meta save hooks.
	 */
	public function __construct() {
		// Add hooks for int fields (single integer values).
		add_filter( 'mdd_detect_type__int', array( $this, 'detect_int' ), 10, 2 );
		add_filter( 'mdd_replace_type__int', array( $this, 'replace_int' ), 10, 3 );

		// Add hooks for multi-int fields (arrays comma-separated strings of ints).
		add_filter( 'mdd_detect_type__multi_int', array( $this, 'detect_multi_int' ), 10, 2 );
		add_filter( 'mdd_replace_type__multi_int', array( $this, 'replace_multi_int' ), 10, 3 );

		// Add hooks for URL fields.
		add_filter( 'mdd_detect_type__url', array( $this, 'detect_urls' ), 10, 2 );
		add_filter( 'mdd_replace_type__url', array( $this, 'replace_url' ), 10, 3 );

		// Add hooks for WYSIWYG fields.
		add_filter( 'mdd_detect_type__wysiwyg', array( $this, 'detect_urls' ), 10, 2 );
		add_filter( 'mdd_replace_type__wysiwyg', array( $this, 'replace_multi_url' ), 10, 3 );

		add_filter( 'mdd_detect_type__wysiwyg', array( $this, 'detect_gallery_ids' ), 10, 2 );
		add_filter( 'mdd_replace_type__wysiwyg', array( $this, 'replace_gallery_ids' ), 10, 3 );

		add_filter( 'mdd_detect_type__wysiwyg', array( $this, 'detect_caption_ids' ), 10, 2 );
		add_filter( 'mdd_replace_type__wysiwyg', array( $this, 'replace_caption_ids' ), 10, 3 );

		add_filter( 'mdd_detect_type__wysiwyg', array( $this, 'detect_img_classes' ), 10, 2 );
		add_filter( 'mdd_replace_type__wysiwyg', array( $this, 'replace_img_classes' ), 10, 3 );

	}

	/**
	 * Detection filter for single integer values.
	 *
	 * @uses MDD_Reference_Handler::check_attachment_id()
	 *
	 * @param array      $refs An array of attachment IDs to add to.
	 * @param int|string $subject The value to check.
	 * @return array The $refs array, with any attachment IDs added.
	 */
	public function detect_int( $refs, $subject ) {

		// If $subject isn't an int or a string, pass $refs along untouched.
		if ( ! is_int( $subject ) && ! is_string( $subject ) ) {
			return $refs;
		}

		// Check whether $subject is an attachment ID.
		if ( $this->check_attachment_id( $subject ) ) {

			// Sanitize the integer value.
			$subject = absint( $subject );

			// If $subject isn't already in $refs, add it.
			if ( ! in_array( $subject, $refs, true ) ) {
				$refs[] = $subject;
			}
		}

		return $refs;
	}

	/**
	 * Replacement filter for single integer values.
	 *
	 * @param int|string $subject The value to (maybe) replace.
	 * @param int        $old_id  The value to look for.
	 * @param int        $new_id  The value to replace $old_id with.
	 * @return array The $refs array, with any attachment IDs added.
	 */
	public function replace_int( $subject, $old_id, $new_id ) {

		// If $subject isn't an int or a string, leave it alone.
		if ( ! is_int( $subject ) && ! is_string( $subject ) ) {
			return $subject;
		}

		// If $subject matches the old ID, replace it with the new ID.
		if ( absint( $old_id ) === absint( $subject ) ) {
			// Match the type of $subject: if it's a string, return a string.
			// Otherwise, return an integer.
			if ( is_string( $subject ) ) {
				return (string) $new_id;
			} else {
				return absint( $new_id );
			}
		}

		return $subject;
	}

	/**
	 * Replacement filter for multi-integer values.
	 *
	 * @uses MDD_Reference_Handler::replace_int()
	 *
	 * @param array|string $subject An array or comma-separated list of possible
	 *                     attachment IDs.
	 * @param int          $old_id  The value to look for.
	 * @param int          $new_id  The value to replace $old_id with.
	 * @return array The $refs array, with any attachment IDs added.
	 */
	public function replace_multi_int( $subject, $old_id, $new_id ) {

		$is_array = is_array( $subject );

		// If $subject isn't an array, see if we can turn it into one.
		if ( ! $is_array ) {
			// Split a comma-separated string into an array.
			if ( is_string( $subject ) ) {
				$subject = explode( ',', $subject );
			} else {
				// If $subject isn't a string, give up and return it untouched.
				return $subject;
			}
		}

		// Iterate over items in $subject, replacing as necessary.
		foreach ( $subject as $index => $value ) {
			$subject[ $index ] = $this->replace_int( $value, $old_id, $new_id );
		}

		// If $subject was originally a string, turn it back into a string.
		if ( ! $is_array ) {
			$subject = implode( ',', $subject );
		}

		return $subject;
	}

	/**
	 * Detection filter for multi-integer values.
	 *
	 * @uses MDD_Reference_Handler::detect_int()
	 *
	 * @param array        $refs An array of attachment IDs to add to.
	 * @param array|string $subject An array or comma-separated list of possible
	 *                     attachment IDs.
	 * @return array The $refs array, with any attachment IDs added.
	 */
	public function detect_multi_int( $refs, $subject ) {

		// If $subject isn't an array, see if we can turn it into one.
		if ( ! is_array( $subject ) ) {
			// Split a comma-separated string into an array.
			if ( is_string( $subject ) ) {
				$subject = explode( ',', $subject );
			} else {
				// If $subject isn't a string, give up and return $refs untouched.
				return $refs;
			}
		}

		// Check each array value like it was an individual int value, and add any
		// attachment IDs to $refs.
		foreach ( $subject as $value ) {
			$refs = $this->detect_int( $refs, $value );
		}

		// Weed out duplicate IDs.
		$refs = array_unique( $refs );

		return $refs;
	}

	/**
	 * Return a regex that matches all URLs that look like attachment URLs.
	 *
	 * @return string A regular expression for use with preg_match().
	 */
	public function get_attachment_url_regex() {

		// This function will always return the same value for a given site, unless
		// we're on multisite and switch_to_blog() or restore_current_blog() gets
		// called. So check for a cached value, and return it if found.
		if ( ! empty( $this->attachment_url_regex ) ) {
			return $this->attachment_url_regex;
		}

		// First, get the URL of the upload directory (pass FALSE as the second
		// parameter to avoid unnecessarily creating a new year/month subdir).
		$uploads = wp_upload_dir( null, false );
		$uploads_url = $uploads['baseurl'];
		// Create a partial regex pattern that will match domain-relative or
		// protocol-relative versions of the upload dir URL.
		$home_url = home_url();
		$home_pattern = preg_replace( '!^https?://!', '(?:https?:)?//', $home_url );
		$uploads_pattern = '(?:' . str_replace( $home_url, "(?:$home_pattern)?", $uploads_url ) . ')?';
		
		$uploads_pattern = str_replace( '/', '(?:\\\\)?/', $uploads_pattern );

		// Get all allowed media file extensions.
		$extensions = array_keys( wp_get_mime_types() );
		// Create a partial regex pattern that will match any allowed media extension.
		$extension_pattern = '(?:' . implode( '|', $extensions ) . ')';

		// Create a partial regex pattern matching valid URL characters (see
		// http://stackoverflow.com/q/7109143). Exclude hash and question mark
		// because we're only looking for file paths -- query strings and hashes
		// don't matter. The exclamation point is escaped because that's also going
		// to be our regex delimiter.
		$url_char_pattern = '[A-Za-z0-9-._~:/\[\]@\!$&\'()*+,;=%]';
		// And a negated version.
		$not_url_char_pattern = '[^A-Za-z0-9-._~:/\[\]@\!$&\'()*+,;=%]';

		// Create a regex that will match anything that looks like the URL for an
		// attachment file on this site.
		$this->attachment_url_regex = '!'
			// Non-capturing group: all URLs must be found at the start of $subject,
			. '(?:^|'
			// ...or following a CSS 'url(' function,
			. 'url\(|'
			// ...or be preceded by something that isn't a valid URL character.
			. $not_url_char_pattern
			// End non-capturing group.
			. ')'
			// Capturing group #1: opening quote, if any.
			. '(["\']?)'
			// Begin named capturing group for entire URL.
			. '(?P<url>'
			// The upload directory URL.
			. $uploads_pattern
			// Slash between upload dir and subdir or filename.
			. '(?:\\\\)?/'
			// Begin named capturing group for filename (including year/month folders, because that's how _wp_attached_file values are stored).
			. '(?P<filename>'
			// Year/month folder, if any.
			. '(?:\d{4}(?:\\\\)?/\d{2}(?:\\\\)?/)?'
			// Possible filename characters (anything other than a slash, space, or quote).
			. '[^/ "\']*'
			// A valid file extension.
			. '\.' . $extension_pattern
			// End 'filename' capturing group.
			. ')'
			// End 'url' capturing group.
			. ')'
			// Either a closing quote, a closing paren, the start of a query string or hash, or the end of $subject.
			. '(?:\1|[)?#]|$)'
			// End of regex.
			. '!';
		return $this->attachment_url_regex;
	}

	/**
	 * Detection filter for attachment URLs within a string value.
	 *
	 * @uses MDD_Reference_Handler::get_attachment_id_from_filename()
	 * @uses MDD_Reference_Handler::get_attachment_url_regex()
	 *
	 * @param array  $refs    An array of attachment IDs to add to.
	 * @param string $subject The field value/post content in which to look for
	 *                        attachment references.
	 * @return array The $refs array, with any attachment IDs added.
	 */
	public function detect_urls( $refs, $subject ) {

		// If $subject isn't a string, pass $refs along untouched.
		if ( ! is_string( $subject ) ) {
			return $refs;
		}

		// If we got this far, $subject is a string. Look for attachment URLs.
		preg_match_all( $this->get_attachment_url_regex(), $subject, $matches );
		// URL-decode all filenames.
		$filenames = array_map( 'urldecode', $matches['filename'] );
		// Weed out duplicate filenames.
		$filenames = array_unique( $filenames );

		// Try to get attachment IDs for all filenames, and add them to $refs.
		foreach ( $filenames as $filename ) {
			$attachment_id = $this->get_attachment_id_from_filename( $filename );
			if ( $attachment_id ) {
				$refs[] = $attachment_id;
			}
		}

		// Weed out duplicate IDs.
		$refs = array_unique( $refs );

		return $refs;
	}

	/**
	 * Replace a URL, relative or absolute, for the attachment identified by
	 * $old_id with URLs for the attachment identified by $new_id.
	 *
	 * @uses MDD_Reference_Handler::get_replacement_urls()
	 *
	 * @param string|int $subject The value that should be replaced if it's a URL
	 *                            pointing to the attachment identified by
	 *                            $old_id.
	 * @param int        $old_id  The ID of the old attachment post.
	 * @param int        $new_id  The ID of the new attachment post. References to
	 *                            the old attachment will be replaced with
	 *                            references to this attachment.
	 */
	public function replace_url( $subject, $old_id, $new_id ) {

		// If $subject isn't a string, pass it along untouched.
		if ( ! is_string( $subject ) ) {
			return $subject;
		}

		// Get URLs to replace.
		$replacements = $this->get_replacement_urls( $old_id, $new_id );

		// If $subject matches any of the old URLs, return the equivalent new URL.
		foreach ( $replacements as $old_url => $new_url ) {
			if ( $this->get_replacement_urls( $subject ) === $this->get_replacement_urls( $old_url ) ) {
				return $new_url;
			}
		}

		return $subject;
	}

	/**
	 * Replace any number of URLs, relative or absolute, for the attachment
	 * identified by $old_id with URLs for the attachment identified by $new_id.
	 *
	 * @uses MDD_Reference_Handler::get_replacement_urls()
	 * @uses MDD_Reference_Handler::get_attachment_url_regex()
	 * @uses MDD_Reference_Handler::_replace_multi_url_callback()
	 *
	 * @param string|int $subject The value in which URLs should be replaced.
	 * @param int        $old_id  The ID of the old attachment post.
	 * @param int        $new_id  The ID of the new attachment post. References
	 *                            to the old attachment will be replaced with
	 *                            references to this attachment.
	 */
	public function replace_multi_url( $subject, $old_id, $new_id ) {

		// Set temporary array of replacement URLs, so they don't have to be
		// recalculated again and again.
		$this->urls_to_replace = $this->get_replacement_urls( $old_id, $new_id );

		// Find attachment URLs, and if any URL matches a key in $urls_to_replace,
		// replace it.
		$subject = preg_replace_callback(
			$this->get_attachment_url_regex(),
			array( $this, '_replace_multi_url_callback' ),
			$subject
		);

		return $subject;
	}

	/**
	 * Callback for preg_replace_callback().
	 *
	 * @param array $matches An array of matches/capturing groups, as passsed in
	 *                       by preg_replace_callback().
	 */
	public function _replace_multi_url_callback( $matches ) {

		$url = ( strpos( $matches['url'], 'http' ) !== false ) ? $this->get_schemeless_url( $matches['url'] ) : $matches['url'];

		// If $url matches any of the old URLs, return the entire match (including
		// context that may have been captured by the regex, i.e. opening quotes).
		foreach ( $this->urls_to_replace as $old_url => $new_url ) {
			$old_url = ( strpos( $old_url, 'http' ) !== false ) ? $this->get_schemeless_url( $old_url ) : $old_url;
			$new_url = ( strpos( $new_url, 'http' ) !== false ) ? $this->get_schemeless_url( $new_url ) : $new_url;
			$old_url_with_backslashes = str_replace( '/', '\/', $old_url );
			$new_url_with_backslashes = str_replace( '/', '\/', $new_url );
			
			if ( $url === $old_url || $url === $old_url_with_backslashes ) {
				return str_replace( array( $old_url, $old_url_with_backslashes ), array( $new_url, $new_url_with_backslashes ), $matches[0] );
			}
		}
		return $matches[0];
	}

	/**
	 * Return a regex that matches (an) image ID(s) in the [gallery] shortcode.
	 *
	 * @param int|null $id An image ID, if the regex will be used to match
	 *                     galleries containing a specific ID, or NULL if the
	 *                     regex should match all gallery shortcodes.
	 * @return string A regular expression for use with preg_match() or
	 *                preg_replace().
	 */
	public function get_gallery_ids_regex( $id = null ) {

		if ( is_null( $id ) ) {
			// If no ID was given, use a pattern matching multiple comma-separated
			// IDs, and without other capturing groups, for simplicity's sake.
			return '/'
				// Gallery shortcode, up to ID attribute.
				. '\[gallery [^\]]*ids='
				// Capturing group #2: ID attribute opening quote.
				. '(|["\'])'
				// One or more IDs, separated by commas.
				. '(?P<ids>(?:\d+,)*\d+)'
				// ID attribute closing quote, to match the opening quote.
				. '\1'
				// Any other attributes inside the gallery shortcode.
				. '[^\]]*'
				// End of gallery shortcode.
				. '\]'
				. '/';
		} else {
			// If an ID was given, use a pattern matching the ID preceded and followed
			// by any number of other IDs, separated by commas.
			return '/'
				// Begin capturing group #1.
				. '('
				// Gallery shortcode, up to ID attribute.
				. '\[gallery [^\]]*ids='
				// Capturing group #2: ID attribute opening quote.
				. '(|["\'])'
				// Preceding IDs, if any.
				. '(?:\d+,)*'
				// End capturing group #1.
				. ')'
				. absint( $id )
				// Begin capturing group #3.
				. '('
				// Following IDs, if any.
				. '(?:,\d+)*'
				// ID attribute closing quote, to match the opening quote.
				. '\2'
				// Any other attributes inside the gallery shortcode.
				. '[^\]]*'
				// End of gallery shortcode.
				. '\]'
				// End capturing group #3.
				. ')'
				. '/';
		}//end if
	}

	/**
	 * Detect gallery shortcodes with 'ids=' attributes.
	 *
	 * @uses MDD_Reference_Handler::get_gallery_ids_regex()
	 *
	 * @param array  $refs    Array to which referenced IDs will be added.
	 * @param string $subject The value in which to look for gallery shortcodes.
	 * @return array An array of attachment IDs (as integers).
	 */
	public function detect_gallery_ids( $refs, $subject ) {

		// If this isn't a string, pass $refs along untouched.
		if ( ! is_string( $subject ) ) {
			return $refs;
		}

		$re = $this->get_gallery_ids_regex();

		// Search for matches.
		preg_match_all( $re, $subject, $matches );

		// Initialize an array for all IDs.
		$ids = array();

		// For each set of IDs, add each comma-separated ID to the $ids array.
		foreach ( $matches['ids'] as $match ) {
			$ids = array_merge( $ids, explode( ',', $match ) );
		}

		// Cast all IDs as integers.
		$ids = array_map( 'absint', $ids );

		// Add $ids to $refs, and weed out duplicates.
		$refs = array_unique( array_merge( $refs, $ids ) );

		return $refs;
	}

	/**
	 * Replace an old image ID in the [gallery] shortcode.
	 *
	 * @uses MDD_Reference_Handler::get_gallery_ids_regex()
	 *
	 * @param string $subject The value in which to look for gallery shortcodes.
	 * @param int    $old_id  The attachment ID to replace with $new_id.
	 * @param int    $new_id  The attachment ID to replace $old_id with.
	 */
	public function replace_gallery_ids( $subject, $old_id, $new_id ) {
		$re = $this->get_gallery_ids_regex( $old_id );
		return preg_replace( $re, "\${1}$new_id\${3}", $subject );
	}

	/**
	 * Return a regex that matches an image ID in the [caption] shortcode.
	 *
	 * @param int|null $id An image ID, if the regex will be used to match
	 *                     shortcodes for a specific ID, or NULL if the regex
	 *                     should match all [caption] shortcodes with attachment_*
	 *                     ID attributes.
	 * @return string A regular expression for use with preg_match() or
	 *                preg_replace().
	 */
	public function get_caption_id_regex( $id = null ) {

		if ( is_null( $id ) ) {
			// If no ID was given, add a named capturing group for preg_match().
			$id_pattern = '(?P<id>\d+)';
		} else {
			// If an ID was given, sanitize it.
			$id_pattern = absint( $id );
		}

		return '/'
			// Begin capturing group #1.
			. '('
			// Caption shortcode, up to ID attribute.
			. '\[caption [^\]]*id='
			// Capturing group #2: ID attribute opening quote.
			. '(|["\'])'
			// Standard ID attribute prefix.
			. 'attachment_'
			// End capturing group #1.
			. ')'
			. $id_pattern
			// Begin capturing group #3.
			. '('
			// ID attribute closing quote, to match the opening quote.
			. '\2'
			// Any other attributes inside the caption shortcode.
			. '[^\]]*'
			// End of caption shortcode opening tag.
			. '\]'
			// End capturing group #3.
			. ')'
			. '/';
	}

	/**
	 * Detect caption shortcodes with ID attributes like "attachment_*".
	 *
	 * @uses MDD_Reference_Handler::get_caption_id_regex()
	 *
	 * @param array  $refs    Array to which referenced IDs will be added.
	 * @param string $subject The value in which to look for caption shortcodes.
	 * @return array An array of attachment IDs (as integers).
	 */
	public function detect_caption_ids( $refs, $subject ) {

		// If this isn't a string, pass $refs along untouched.
		if ( ! is_string( $subject ) ) {
			return $refs;
		}

		$re = $this->get_caption_id_regex();

		// Search for matches.
		preg_match_all( $re, $subject, $matches );

		// Cast all matches as integers.
		$ids = array_map( 'absint', $matches['id'] );

		// Add $ids to $refs, and weed out duplicate IDs.
		$refs = array_unique( array_merge( $refs, $ids ) );

		return $refs;
	}

	/**
	 * Replace an old image ID in the [caption] shortcode.
	 *
	 * @uses MDD_Reference_Handler::get_caption_id_regex()
	 *
	 * @param string $subject The value in which to look for caption shortcodes.
	 * @param int    $old_id  The attachment ID to replace with $new_id.
	 * @param int    $new_id  The attachment ID to replace $old_id with.
	 */
	public function replace_caption_ids( $subject, $old_id, $new_id ) {
		$re = $this->get_caption_id_regex( $old_id );
		return preg_replace( $re, "\${1}$new_id\${3}", $subject );
	}

	/**
	 * Return a regex that matches wp-image-[ID] class attributes.
	 *
	 * @param int|null $id An image ID, if the regex will be used to match classes
	 *                     for a specific ID, or NULL if the regex should match
	 *                     all wp-image-* classes.
	 * @return string A regular expression for use with preg_match() or
	 *                preg_replace().
	 */
	public function get_img_class_regex( $id = null ) {

		if ( is_null( $id ) ) {
			// If no ID was given, add a named capturing group for preg_match().
			$id_pattern = '(?P<id>\d+)';
		} else {
			// If an ID was given, sanitize it.
			$id_pattern = absint( $id );
		}

		return '/'
			. '(' // Begin capturing group #1.
			. '<img[^>]*class=' // Image tag, up to class attribute.
			. '(["\'])' // Capturing group #2: class attribute opening quote.
			. '(?:[^"\']* )*' // Preceding classes, if any.
			. 'wp-image-' // Standard class attribute prefix.
			. ')' // End capturing group #1.
			. $id_pattern
			. '(' // Begin capturing group #3.
			. '(?: [^"\']*)*' // Following classes, if any.
			. '\2' // Class attribute closing quote, to match the opening quote.
			. ')' // End capturing group #3. The rest of the img tag is irrelevant.
			. '/';
	}

	/**
	 * Detect img tags with class attributes like "wp-caption-*".
	 *
	 * @uses MDD_Reference_Handler::get_img_class_regex()
	 *
	 * @param array  $refs    Array to which referenced IDs will be added.
	 * @param string $subject The value in which to look for img tags.
	 * @return array An array of attachment IDs (as integers).
	 */
	public function detect_img_classes( $refs, $subject ) {

		// If this isn't a string, pass $refs along untouched.
		if ( ! is_string( $subject ) ) {
			return $refs;
		}

		$re = $this->get_img_class_regex();

		// Search for matches.
		preg_match_all( $re, $subject, $matches );

		// Cast all matches as integers.
		$ids = array_map( 'absint', $matches['id'] );

		// Add $ids to $refs, and weed out duplicate IDs.
		$refs = array_unique( array_merge( $refs, $ids ) );

		return $refs;
	}

	/**
	 * Replace an old wp-image-[ID] class attribute value in an <img> tag.
	 *
	 * This is important for WP's built-in responsive image functionality, which
	 * uses the wp-image-* class to detect images inserted using TinyMCE. Without
	 * the right attachment ID, WordPress won't be able to find other sizes of the
	 * image and add them to the srcset attribute.
	 *
	 * Note that unlike the gallery and caption shortcode replacement functions,
	 * this one won't handle unquoted class attributes. Who leaves an HTML class
	 * attribute unquoted? Definitely not WordPress itself, and this function
	 * mainly targets img tags generated by WP.
	 *
	 * @uses MDD_Reference_Handler::get_img_class_regex()
	 *
	 * @param string $subject The value in which to look for caption shortcodes.
	 * @param int    $old_id  The attachment ID to replace with $new_id.
	 * @param int    $new_id  The attachment ID to replace $old_id with.
	 */
	public function replace_img_classes( $subject, $old_id, $new_id ) {
		$re = $this->get_img_class_regex( $old_id );
		return preg_replace( $re, "\${1}$new_id\${3}", $subject );
	}
}
