<?php
/**
 * Media Deduper Pro: Yoast SEO parser class.
 *
 * @package Media_Deduper_Pro
 */

// Disallow direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Detect & replace references in Yoast SEO content
 */
class MDD_Parse_Yoast extends MDD_Parse_Base {

	/**
	 * Constructor. Add reference/detection/replacement filters and post/meta save hooks.
	 */
	public function __construct() {
		// Specify support for all registered ACF fields on a post
		add_filter( 'mdd_get_reference_fields', array( $this, 'get_yoast_fields' ), 10, 2 );
	}

	/**
	 * Get the add Yoast SEO content to the fields array used for the mdd references.
	 *
	 * @param array       $fields       The array of field defintions with the references of mdd.
	 * @param int|WP_post $post         The post id or object.
	 *
	 * @return array $fields.
	 */
	public function get_yoast_fields( $fields, $post = 0 ) {

		// Add Yoast SEO (free version) image fields.
		$fields['meta:_yoast_wpseo_twitter-image'] = 'url';
		$fields['meta:_yoast_wpseo_opengraph-image'] = 'url';

		return $fields;
	}
}
