<?php
/**
 * Media Deduper Pro: ACF parser class.
 *
 * @package Media_Deduper_Pro
 */

// Disallow direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Detect & replace references in Advanced Custom Fields content
 */
class MDD_Parse_ACF extends MDD_Parse_Base {

	/**
	 * Constructor. Add reference/detection/replacement filters and post/meta save hooks.
	 */
	public function __construct() {
		// Specify support for all registered ACF fields on a post
		add_filter( 'mdd_get_reference_fields', array( $this, 'get_acf_post_fields' ), 10, 2 );
	}

	/**
	 * Get the acf definitons and add them to the fields array used for the mdd references.
	 *
	 * @param array       $fields       The array of field defintions with the references of mdd.
	 * @param int|WP_post $post         The post id or object.
	 *
	 * @return array $fields.
	 */
	public function get_acf_post_fields( $fields, $post = 0 ) {
		// Bail, if acf is not loaded or post is empty and return the original fields array.
		if ( ! function_exists( 'get_field_objects' ) || empty( $post ) ) {
			return $fields;
		}

		$post_id = ( is_object( $post ) ) ? $post->ID : $post;
		$acf_fields = get_field_objects( $post_id );
		$allowed_acf = array( 'image', 'file', 'gallery', 'wysiwyg', 'group', 'repeater', 'flexible_content' );

		// bail, if no ac fields are returned and return the original array.
		if ( empty( $acf_fields ) ) {
			return $fields;
		}

		foreach ( $acf_fields as $field ) {
			// If type is not a trackable acf field skip it.
			if ( ! in_array( $field['type'], $allowed_acf, true ) ) {
				continue;
			}
			$fields = $this->add_acf_fields_recursive( $fields, $field, $post_id );

		}//end foreach

		return $fields;
	}

	/**
	 * Recursively add meta field values to the fields array.
	 *
	 * @param array    $fields The fields array used for the mdd references.
	 * @param array    $field   The field being processed.
	 * @param int      $post_id The id of the current post being index/processed.
	 * @param array    $parents The array of parents for the current field.
	 * @param int|null $index   The field's index, if any.
	 *
	 * @returns array.
	 */
	public function add_acf_fields_recursive( $fields, $field, $post_id, $parents = array(), $index = null ) {
	
		// Bail, if type or post is empty and return the original fields array.
		if ( empty( $field ) || ! is_array( $field ) || ! isset( $field['type'] ) || ! is_array( $fields ) ) {
			return $fields;
		}
		// if index is not nulled format the index portion of the $fields array index.
		$index = ( null !== $index ) ? "_$index" : '';
		// if parent_name is not empty format the field_name portion of the $fields array index to account for the parent being present.
		$field_name = ( ! empty( $parents ) ) ? "_{$field['name']}" : $field['name'];
		// if parents isnt empty explode the array into a string, else set an empty string.
		$parent_name = ( ! empty( $parents ) ) ? implode( '', $parents ) : '';
		// save the meta name format based on parent index and field name.
		$meta_name = "{$parent_name}{$index}{$field_name}";

		switch ( $field['type'] ) {
			case 'image':
			case 'file':
				$fields[ "meta:{$meta_name}" ] = 'int' ;
				break;
			case 'gallery':
				$fields[ "meta:{$meta_name}" ] = 'multi_int' ;
				break;
			case 'wysiwyg':
				$fields[ "meta:{$meta_name}" ] = 'wysiwyg';
				break;
			case 'group':
				// if parents is empty lets save the field_name for the subfields. else this is a group within another field lets save the name with the index.
				if ( empty( $parents ) ) {
					$parents[] = $field['name'];
				} else {
					$parents[] = "{$index}_{$field['name']}";
				}

				foreach ( $field['sub_fields'] as $group_sub_field ) {
					$fields = $this->add_acf_fields_recursive( $fields, $group_sub_field, $post_id, $parents );
				}
				break;
			case 'repeater':
				$total = get_post_meta( $post_id, $meta_name, true );
				// if parents is empty lets save the field_name for the subfields. else this is a group within another field lets save the name with the index.
				if ( empty( $parents ) ) {
					$parents[] = $field['name'];
				} else {
					$parents[] = "{$index}_{$field['name']}";
				}
				foreach ( $field['sub_fields'] as $repeater_sub_field ) {
					for ( $index = 0; $index < $total; $index++ ) {
						$fields = $this->add_acf_fields_recursive( $fields, $repeater_sub_field, $post_id, $parents, $index );
					}
				}
				break;
			case 'flexible_content':
				// lets get the currently used layouts.
				$used_layouts = get_post_meta( $post_id, $meta_name, true );
				// if parents is empty lets save the field_name for the subfields. else this is a group within another field lets save the name with the index.
				if ( empty( $parents ) ) {
					$parents[] = $field['name'];
				} else {
					$parents[] = "{$index}_{$field['name']}";
				}
				foreach ( $used_layouts as $layout_key => $layout ) {
					$layout_index = $this->multi_array_search( 'name', $layout, $field['layouts'] );

					if ( empty( $layout ) || empty( $layout_index ) || ! isset( $field['layouts'][ $layout_index ]['sub_fields'] ) ) {
						continue;
					}
					foreach ( $field['layouts'][ $layout_index ]['sub_fields'] as $layout_sub_field ) {
						$fields = $this->add_acf_fields_recursive( $fields, $layout_sub_field, $post_id, $parents, $layout_key );
					}
				}
		}//end switch

		return $fields;
	}

	/**
	 * Find the key of a multidimentional array based on the value of a sub key.
	 *
	 * @param string $sub_key The sub key/index of the main array value.
	 * @param string $value   The sub key/index value to look for.
	 * @param array  $array   The array to look into.
	 *
	 * @returns bool|string.
	 */
	public function multi_array_search( $sub_key = '', $value = '', $array = array() ) {
		// Iterate through the array saing the key and value.
		foreach ( $array as $key => $item ) {
			// if the sub key exsists and the item sub key matches the value return the parent index/key.
			if ( isset( $item[ $sub_key ] ) && $item[ $sub_key ] === $value ) {
				return $key;
			}
		}

		// Return false if everything failed.
		return false;
	}
}
