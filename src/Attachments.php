<?php

namespace DisableImageSrcset;

defined( 'ABSPATH' ) || exit;

/**
 * Attachments functionality.
 *
 * @since   1.0.0
 * @version 1.0.0
 */
final class Attachments {

	private string $meta_field_key = 'disable_srcset';

	// region METHODS

	/**
	 * Initializes the blocks.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  void
	 */
	public function initialize(): void {
		\add_filter( 'attachment_fields_to_edit', array( $this, 'attachment_fields_to_edit' ), 10, 2 );
		\add_filter( 'attachment_fields_to_save', array( $this, 'attachment_fields_to_save' ), 10, 2 );
		\add_filter( 'wp_calculate_image_srcset', array( $this, 'remove_image_srcset' ), 10, 5 );
	}

	// endregion

	// region HOOKS

	/**
	 * Adding our custom fields to the $form_fields array
	 *
	 * @param array $form_fields
	 * @param object $post
	 *
	 * @return array
	 */
	public function attachment_fields_to_edit( array $form_fields, object $post ): array {

		$value = get_post_meta( $post->ID, '_' . $this->meta_field_key, true );

		$form_fields[ $this->meta_field_key ] = array(
			'label' => __( 'Disable SrcSet', 'disable-image-srcset' ),
			'input' => 'html',
			'html'  => sprintf( '<input %s type="checkbox" value="1" name="attachments[%s][%s]" id="attachments[%s][%s]" />', '1' === $value ? 'checked' : '', $post->ID, $this->meta_field_key, $post->ID, $this->meta_field_key ),
			'value' => $value,
		);

		return $form_fields;
	}

	/**
	 * Saving our custom attachment field
	 *
	 * @param array $post
	 * @param array $attachment
	 *
	 * @return array
	 */
	public function attachment_fields_to_save( array $post, array $attachment ): array {
		$meta_value = isset( $attachment[ $this->meta_field_key ] ) ? 1 : 0;

		update_post_meta( $post['ID'], '_' . $this->meta_field_key, $meta_value );

		return $post;
	}

	/**
	 * Removes the srcset attribute for selected images
	 *
	 * @param $sources array One or more arrays of source data to include in the 'srcset'
	 * @param $size_array array An array of requested width and height values.
	 * @param $image_src string The 'src' of the image.
	 * @param $image_meta array The image meta data as returned by 'wp_get_attachment_metadata() '.
	 * @param $attachment_id int Image attachment ID or 0.
	 *
	 * @return array|bool
	 */
	public function remove_image_srcset( array $sources, array $size_array, string $image_src, array $image_meta, int $attachment_id ): array|bool {

		$disable_image_srcset = get_post_meta( $attachment_id, '_' . $this->meta_field_key, true );

		if ( $disable_image_srcset ) {
			return false;
		}

		return $sources;
	}

	// endregion
}
