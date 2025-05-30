<?php
/**
 * Class that handles specific [vc_gallery] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_gallery.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class WPBakeryShortCode_Vc_gallery
 */
class WPBakeryShortCode_Vc_Gallery extends WPBakeryShortCode {
	/**
	 * WPBakeryShortCode_Vc_gallery constructor.
	 *
	 * @param array $settings
	 */
	public function __construct( $settings ) {
		parent::__construct( $settings );

		$this->shortcodeScripts();
	}

	/**
	 * Register shortcode scripts.
	 */
	public function shortcodeScripts() {
		wp_register_script( 'vc_grid-js-imagesloaded', vc_asset_url( 'lib/vendor/node_modules/imagesloaded/imagesloaded.pkgd.min.js' ), [ 'jquery-core' ], WPB_VC_VERSION, true );
	}

	/**
	 * Add params html holders.
	 *
	 * @param array $param
	 * @param string $value
	 * @return string
	 */
	public function singleParamHtmlHolder( $param, $value ) {
		$output = '';
		// Compatibility fixes.
		$old_names = [
			'yellow_message',
			'blue_message',
			'green_message',
			'button_green',
			'button_grey',
			'button_yellow',
			'button_blue',
			'button_red',
			'button_orange',
		];
		$new_names = [
			'alert-block',
			'alert-info',
			'alert-success',
			'btn-success',
			'btn',
			'btn-info',
			'btn-primary',
			'btn-danger',
			'btn-warning',
		];
		$value = str_ireplace( $old_names, $new_names, $value );
		$param_name = isset( $param['param_name'] ) ? $param['param_name'] : '';
		$type = isset( $param['type'] ) ? $param['type'] : '';
		$class = isset( $param['class'] ) ? $param['class'] : '';

		if ( isset( $param['holder'] ) && 'hidden' !== $param['holder'] ) {
			$output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">' . $value . '</' . $param['holder'] . '>';
		}
		if ( 'images' === $param_name ) {
			$images_ids = empty( $value ) ? [] : explode( ',', trim( $value ) );
			$output .= '<ul class="attachment-thumbnails' . ( empty( $images_ids ) ? ' image-exists' : '' ) . '" data-name="' . $param_name . '">';
			foreach ( $images_ids as $image ) {
				$img = wpb_getImageBySize( [
					'attach_id' => (int) $image,
					'thumb_size' => 'thumbnail',
				] );
				$output .= ( $img ? '<li>' . $img['thumbnail'] . '</li>' : '<li><img width="150" height="150" test="' . $image . '" src="' . esc_url( vc_asset_url( 'vc/blank.gif' ) ) . '" class="attachment-thumbnail" alt="" title="" /></li>' );
			}
			$output .= '</ul>';
			$output .= '<a href="#" class="column_edit_trigger' . ( ! empty( $images_ids ) ? ' image-exists' : '' ) . '">' . esc_html__( 'Add images', 'js_composer' ) . '</a>';

		}

		return $output;
	}
}
