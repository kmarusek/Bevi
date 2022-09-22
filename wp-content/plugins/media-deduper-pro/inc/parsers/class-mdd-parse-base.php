<?php
/**
 * Media Deduper Pro: Base parser class.
 *
 * @package Media_Deduper_Pro
 */

// Disallow direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Base parser class containing helpful shared methods
 */
class MDD_Parse_Base {

	/**
	 * Constructor.
	 */
	public function __construct() {

	}

	/**
	 * Given an old attachment ID and a new attachment ID, return an array mapping
	 * URLs for the old attachment to URLs for the new attachment.
	 *
	 * @param int $old_id The ID of the attachment whose URLs should be replaced.
	 * @param int $new_id The ID of the attachment whose URLs should replace the
	 *                    other attachment's.
	 * @return array An array where keys are URLs for the old attachment and
	 *               values are equivalently formatted URLs for the new attachment.
	 */
	public function get_replacement_urls( $old_id, $new_id ) {
		// TODO: cache results.
		// Get absolute URLs of the old and new attachments.
		$old_url = wp_get_attachment_url( $old_id );
		$new_url = wp_get_attachment_url( $new_id );

		// If either $old_url or $new_url is empty, something went wrong. Return an
		// empty array (i.e. no replacements).
		if ( empty( $old_url ) || empty( $new_url ) ) {
			return array();
		}

		// Initialize an array of URLs to replace => replacement URLs.
		$replacements = array();
		$replacements[ $old_url ] = $new_url;
		// Add relative versions of the main attachment file URLs.
		$replacements[ $this->get_schemeless_url( $old_url ) ] = $this->get_schemeless_url( $new_url );
		$replacements[ $this->get_relative_url( $old_url ) ] = $this->get_relative_url( $new_url );

		// If both attachments are images, add URLs for alternate sizes.
		if ( wp_attachment_is_image( $old_id ) && wp_attachment_is_image( $new_id ) ) {

			// First, get metadata for both attachments.
			$old_meta = wp_get_attachment_metadata( $old_id );
			$new_meta = wp_get_attachment_metadata( $new_id );

			// Skip this step if metadata wasn't retrieved for either the old or the
			// new attachment. That'd be weird, though.
			if ( ! empty( $old_meta ) && ! empty( $new_meta ) ) {

				// Get the filename of the full-sized old & new images, so we can use them
				// to get image size URLs. See image_downsize() in wp-inclues/media.php.
				$old_basename = wp_basename( $old_url );
				$new_basename = wp_basename( $new_url );

				// Loop through each generated size of the old image, and add size URLs
				// to the replacement array.
				foreach ( $old_meta['sizes'] as $size_name => $old_size ) {

					// If a size that existed for the image is missing for the new image...
					if ( ! isset( $new_meta['sizes'][ $size_name ] ) ) {
						// TODO: output a warning; copy old image size file instead?!
						continue;
					}

					// Add absolute size URLs to the replacement array, by replacing
					// original-size filenames with filenames for resized images.
					$old_size_url = str_replace( $old_basename, $old_size['file'], $old_url );
					$new_size_url = str_replace( $new_basename, $new_meta['sizes'][ $size_name ]['file'], $new_url );
					$replacements[ $old_size_url ] = $new_size_url;
					// Add relative versions of size URLs.
					$replacements[ $this->get_schemeless_url( $old_size_url ) ] = $this->get_schemeless_url( $new_size_url );
					$replacements[ $this->get_relative_url( $old_size_url ) ] = $this->get_relative_url( $new_size_url );
				}
			}//end if
		}//end if

		return $replacements;
	}

	/**
	 * Return a schemeless/protocol-relative version of an absolute URL.
	 *
	 * @param string $url An absolute URL (e.g. http://test.biz:8888/123.html).
	 * @return string A schemeless version of the URL (e.g.
	 *                //test.biz:8888/123.html).
	 */
	public function get_schemeless_url( $url ) {
		$bits = $this->parse_url( $url );
		return $bits['host'] . $bits['port'] . $bits['path'] . $bits['query'] . $bits['fragment'];
	}

	/**
	 * Return an array of URL components, with prefixes/suffixes as they would
	 * appear in an actual URL string, or empty strings for components that were
	 * missing from the provided URL.
	 *
	 * @param string $url An absolute URL (e.g. http://test.biz:8888/123.html).
	 * @return array An array containing URL components.
	 */
	public function parse_url( $url ) {
		$bits = wp_parse_url( $url );
		return array(
			'scheme'   => ( isset( $bits['scheme'] ) ? $bits['scheme'] . ':' : '' ),
			'host'     => ( isset( $bits['host'] ) ? '//' . $bits['host'] : '' ),
			'port'     => ( isset( $bits['port'] ) ? ':' . $bits['port'] : '' ),
			'path'     => ( isset( $bits['path'] ) ? $bits['path'] : '' ),
			'query'    => ( isset( $bits['query'] ) ? '?' . $bits['query'] : '' ),
			'fragment' => ( isset( $bits['fragment'] ) ? '#' . $bits['fragment'] : '' ),
		);
	}

	/**
	 * Return a relative version of an absolute URL.
	 *
	 * @param string $url An absolute URL (e.g. http://test.biz:8888/123.html).
	 * @return string A relative version of the URL (e.g. /123.html).
	 */
	public function get_relative_url( $url ) {
		$bits = $this->parse_url( $url );
		return $bits['path'] . $bits['query'] . $bits['fragment'];
	}

	/**
	 * Given an attachment filename, try to find the corresponding attachment, and
	 * return its ID.
	 *
	 * @param string $filename       The attachment filename.
	 * @param bool   $may_be_resized Set to TRUE if $filename might be the
	 *                               filename for a resized image. If this is
	 *                               true, and no attachment is found for
	 *                               $filename, this function will look for a
	 *                               filename suffix like "-123x456", strip it
	 *                               out, and try to find an attachment with
	 *                               _that_ name.
	 * @return bool|int The attachment ID, or false if no attachment was found.
	 */
	public function get_attachment_id_from_filename( $filename, $may_be_resized = true ) {

		// Find the first attachment, if any, whose _wp_attached_file value
		// matches $filename.
		$attachments = get_posts(
			array(
				'numberposts' => 1,
				'post_type'   => 'attachment',
				'post_status' => 'any',
				'meta_key'    => '_wp_attached_file',
				'meta_value'  => $filename,
			)
		);
		// If an attachment was found, return its ID.
		if ( ! empty( $attachments ) ) {
			return $attachments[0]->ID;
		} else {
			// If no attachment was found with $filename as its original filename,
			// then check whether $filename looks like a resized image filename. If
			// so, strip out the -__x__ suffix and try again.
			if ( preg_match( '/(-\d+x\d+)\.[^.]*/', $filename, $matches ) ) {
				$original_filename = str_replace( $matches[1], '', $filename );
				// Set $may_be_resized to false this time. If $filename was
				// kitten-640x480-16x16.jpg, and kitten-640x480.jpg can't be found, then
				// we should just give up. Even if it exists, kitten.jpg might be a
				// completely different image.
				return $this->get_attachment_id_from_filename( $original_filename, false );
			}
		}
	}

	/**
	 * Detect whether a single integer or integer-like value is a reference to an
	 * attachment.
	 *
	 * @param int|string $maybe_id The value to check.
	 * @return int|null The integer ID of an attachment post, or NULL if $maybe_id
	 *                  is not an attachment ID.
	 */
	public function check_attachment_id( $maybe_id ) {

		// If $maybe_id isn't an integer, then this detection method may not apply.
		if ( ! is_int( $maybe_id ) ) {

			// If it's not a string either, bail.
			if ( ! is_string( $maybe_id ) ) {
				return false;
			}

			// If it isn't a positive integer-like string (no floats or scientific
			// notation allowed!), bail.
			if ( ! preg_match( '/^\d+$/', trim( $maybe_id ) ) ) {
				return false;
			}
		}

		// If $maybe_id is a valid post ID, and the post is an attachment, return
		// true.
		$maybe_attachment = get_post( absint( $maybe_id ) );
		if ( $maybe_attachment && 'attachment' === $maybe_attachment->post_type ) {
			return true;
		}

		// If $maybe_id isn't a post ID, or if it's the ID of a post that isn't an
		// attachment, then return false.
		return false;
	}
}
