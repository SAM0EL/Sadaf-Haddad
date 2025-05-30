<?php
/**
 * Class that handles specific [vc_single_image] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_single_image.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class WPBakeryShortCode_Vc_Single_image
 */
class WPBakeryShortCode_Vc_Single_Image extends WPBakeryShortCode {

	/**
	 * WPBakeryShortCode_Vc_Single_image constructor.
	 *
	 * @param array $settings
	 */
	public function __construct( $settings ) {
		parent::__construct( $settings );

		$this->jsScripts();
	}

	/**
	 * Register scripts.
	 */
	public function jsScripts() {
		wp_register_script( 'zoom', vc_asset_url( 'lib/vendor/node_modules/jquery-zoom/jquery.zoom.min.js' ), [ 'jquery-core' ], WPB_VC_VERSION, true );

		wp_register_script( 'vc_image_zoom', vc_asset_url( 'lib/vc/vc_image_zoom/vc_image_zoom.min.js' ), [
			'jquery-core',
			'zoom',
		], WPB_VC_VERSION, true );
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

		if ( 'attach_image' === $param['type'] && 'image' === $param_name ) {
			$output .= '<input type="hidden" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '" />';
			$element_icon = $this->settings( 'icon' );
			$img = wpb_getImageBySize( [
				'attach_id' => (int) preg_replace( '/[^\d]/', '', $value ),
				'thumb_size' => 'thumbnail',
			] );
			$this->setSettings( 'logo', ( $img ? $img['thumbnail'] : '<img width="150" height="150" src="' . esc_url( vc_asset_url( 'vc/blank.gif' ) ) . '" class="attachment-thumbnail vc_general vc_element-icon"  data-name="' . $param_name . '" alt="" title="" style="display: none;" />' ) . '<span class="no_image_image vc_element-icon' . ( ! empty( $element_icon ) ? ' ' . $element_icon : '' ) . ( $img && ! empty( $img['p_img_large'][0] ) ? ' image-exists' : '' ) . '"></span><a href="#" class="column_edit_trigger' . ( $img && ! empty( $img['p_img_large'][0] ) ? ' image-exists' : '' ) . '">' . esc_html__( 'Add image', 'js_composer' ) . '</a>' );
			$output .= $this->outputTitleTrue( $this->settings['name'] );
		} elseif ( ! empty( $param['holder'] ) ) {
			if ( 'input' === $param['holder'] ) {
				$output .= '<' . $param['holder'] . ' readonly="true" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '">';
			} elseif ( in_array( $param['holder'], [
				'img',
				'iframe',
			], true ) ) {
				$output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" src="' . esc_url( $value ) . '">';
			} elseif ( 'hidden' !== $param['holder'] ) {
				$output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">' . $value . '</' . $param['holder'] . '>';
			}
		}

		if ( ! empty( $param['admin_label'] ) && true === $param['admin_label'] ) {
			$output .= '<span class="vc_admin_label admin_label_' . $param['param_name'] . ( empty( $value ) ? ' hidden-label' : '' ) . '"><label>' . $param['heading'] . '</label>: ' . $value . '</span>';
		}

		return $output;
	}

	/**
	 * Set image square size.
	 *
	 * @param int $img_id
	 * @param string|array $img_size
	 * @return string
	 */
	public function getImageSquareSize( $img_id, $img_size ) {
		if ( preg_match_all( '/(\d+)x(\d+)/', $img_size, $sizes ) ) {
			$exact_size = [
				'width' => isset( $sizes[1][0] ) ? $sizes[1][0] : '0',
				'height' => isset( $sizes[2][0] ) ? $sizes[2][0] : '0',
			];
		} else {
			$image_downsize = image_downsize( $img_id, $img_size );
			$exact_size = [
				'width' => $image_downsize[1],
				'height' => $image_downsize[2],
			];
		}
		$exact_size_int_w = (int) $exact_size['width'];
		$exact_size_int_h = (int) $exact_size['height'];
		if ( isset( $exact_size['width'] ) && $exact_size_int_w !== $exact_size_int_h ) {
			$img_size = $exact_size_int_w > $exact_size_int_h ? $exact_size['height'] . 'x' . $exact_size['height'] : $exact_size['width'] . 'x' . $exact_size['width'];
		}

		return $img_size;
	}

	/**
	 * Get title.
	 *
	 * @param string $title
	 * @return string
	 */
	protected function outputTitle( $title ) {
		return '';
	}

	/**
	 * Get title html output.
	 *
	 * @param string $title
	 * @return string
	 */
	protected function outputTitleTrue( $title ) {
		return '<h4 class="wpb_element_title">' . $title . ' ' . $this->settings( 'logo' ) . '</h4>';
	}
}
