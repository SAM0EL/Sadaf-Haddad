<?php
/**
 * Param type 'param_group'.
 *
 * Use it add group level for prams.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/#vc_map()-ParametersofparamsArray
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'EDITORS_DIR', 'class-vc-edit-form-fields.php' );

/**
 * Class Vc_ParamGroup_Edit_Form_Fields
 *
 * @since 4.4
 */
class Vc_ParamGroup_Edit_Form_Fields extends Vc_Edit_Form_Fields {
	/**
	 * Vc_ParamGroup_Edit_Form_Fields constructor.
	 *
	 * @noinspection PhpMissingParentConstructorInspection
	 * @param array $settings
	 * @since 4.4
	 */
	public function __construct( $settings ) {
		$this->setSettings( $settings );
	}

	/**
	 * Get shortcode attribute value wrapper for params group.
	 *
	 * This function checks if value isn't set then it uses std or value fields in param settings.
	 *
	 * @param array $params_settings
	 * @param null $value
	 *
	 * @return mixed;
	 * @since 5.2.1
	 */
	public function getParamGroupAttributeValue( $params_settings, $value = null ) {
		return $this->parseShortcodeAttributeValue( $params_settings, $value );
	}
}

/**
 * Class Vc_ParamGroup
 *
 * @since 4.4
 */
class Vc_ParamGroup {
	/**
	 * Settings for the parameter group.
	 *
	 * @since 4.4
	 * @var array
	 */
	protected $settings;

	/**
	 * Parsed values for the parameter group.
	 *
	 * @since 4.4
	 * @var array|mixed
	 */
	protected $value;

	/**
	 * Map of parameters.
	 *
	 * @since 4.4
	 * @var array
	 */
	protected $map;

	/**
	 * Attributes associated with the parameter group.
	 *
	 * @since 4.4
	 * @var array
	 */
	protected $atts;

	/**
	 * Original unparsed value of the parameter group.
	 *
	 * @since 4.4
	 * @var string
	 */
	public $unparsed_value;

	/**
	 * Vc_ParamGroup constructor.
	 *
	 * @param array $settings
	 * @param array $value
	 * @param string $tag
	 *
	 * @since 4.4
	 */
	public function __construct( $settings, $value, $tag ) {
		$this->settings = $settings;
		$this->settings['base'] = $tag;
		$this->value = vc_param_group_parse_atts( $value );
		$this->unparsed_value = $value;
	}

	/**
	 * Convert parameters to array.
	 *
	 * @param string $param_name
	 * @param array $arr
	 *
	 * @return array
	 * @since 4.4
	 */
	public function params_to_arr( $param_name, $arr ) {
		$data = [];
		foreach ( $arr as $param ) {
			$data[ $param_name . '_' . $param['param_name'] ] = $param['type'];
		}

		return $data;
	}

	/**
	 * Renders the HTML output.
	 *
	 * @return mixed|string
	 * @since 4.4
	 */
	public function render() {
		$output = '';
		$edit_form = new Vc_ParamGroup_Edit_Form_Fields( $this->settings );

		$settings = $this->settings;
		$output .= '<ul class="vc_param_group-list vc_settings" data-settings="' . htmlentities( wp_json_encode( $settings ), ENT_QUOTES, 'utf-8' ) . '">';

		$template = vc_include_template( 'params/param_group/content.tpl.php' );

		// Parsing values.
		if ( ! empty( $this->value ) ) {
			foreach ( $this->value as $values ) {
				$output .= $template;
				$value_block = "<div class='vc_param_group-wrapper vc_clearfix'>";
				$data = $values;
				foreach ( $this->settings['params'] as $param ) {
					$param_value = isset( $data[ $param['param_name'] ] ) ? $data[ $param['param_name'] ] : ( isset( $param['value'] ) ? $param['value'] : null );
					$param['param_name'] = $this->settings['param_name'] . '_' . $param['param_name'];
					$value = $edit_form->getParamGroupAttributeValue( $param, $param_value );
					$value_block .= $edit_form->renderField( $param, $value );
				}
				$value_block .= '</div>';
				$output = str_replace( '%content%', $value_block, $output );
			}
		} else {
			$output .= $template;

		}

		// Empty fields wrapper and Add new fields wrapper.
		$content = "<div class='vc_param_group-wrapper vc_clearfix'>";
		foreach ( $this->settings['params'] as $param ) {
			$param['param_name'] = $this->settings['param_name'] . '_' . $param['param_name'];
			$value = $edit_form->getParamGroupAttributeValue( $param );
			$content .= $edit_form->renderField( $param, $value );
		}
		$content .= '</div>';
		$output = str_replace( '%content%', $content, $output );

		// And button on bottom.
		$output .= '<li class="wpb_column_container vc_container_for_children vc_param_group-add_content vc_empty-container"></li></ul>';

		$add_template = vc_include_template( 'params/param_group/add.tpl.php' );
		$add_template = str_replace( '%content%', $content, $add_template );

		$custom_tag = 'script';
		$output .= '<' . $custom_tag . ' type="text/html" class="vc_param_group-template">' . wp_json_encode( $add_template ) . '</' . $custom_tag . '>';
		$output .= '<input name="' . $this->settings['param_name'] . '" class="wpb_vc_param_value  ' . $this->settings['param_name'] . ' ' . $this->settings['type'] . '_field" type="hidden" value="' . $this->unparsed_value . '" />';

		return $output;
	}
}

/**
 * Function for rendering param in edit form (add element)
 * Parse settings from vc_map and entered values.
 *
 * @param array $param_settings
 * @param array $param_value
 * @param string $tag
 *
 * @return mixed rendered template for params in edit form
 * @since 4.4
 *
 * vc_filter: vc_param_group_render_filter
 */
function vc_param_group_form_field( $param_settings, $param_value, $tag ) {
	$param_group = new Vc_ParamGroup( $param_settings, $param_value, $tag );

	return apply_filters( 'vc_param_group_render_filter', $param_group->render() );
}

add_action( 'wp_ajax_vc_param_group_clone', 'vc_param_group_clone' );

/**
 * Handles the cloning of param.
 *
 * @since 4.4
 */
function vc_param_group_clone() {
	vc_user_access()->checkAdminNonce()->validateDie()->wpAny( 'edit_posts', 'edit_pages' )->validateDie();

	$param = vc_post_param( 'param' );
	$value = vc_post_param( 'value' );
	$tag = vc_post_param( 'shortcode' );
	wp_send_json_success( vc_param_group_clone_by_data( $tag, json_decode( rawurldecode( $param ), true ), json_decode( rawurldecode( $value ), true ) ) );
}

/**
 * Clone param by data.
 *
 * @param string $tag
 * @param array $params
 * @param array $data
 *
 * @return mixed|string
 * @since 4.4
 */
function vc_param_group_clone_by_data( $tag, $params, $data ) {
	$output = '';
	$params['base'] = $tag;
	$edit_form = new Vc_ParamGroup_Edit_Form_Fields( $params );
	$edit_form->loadDefaultParams();

	$template = vc_include_template( 'params/param_group/content.tpl.php' );
	$output .= $template;
	$value_block = "<div class='vc_param_group-wrapper vc_clearfix'>";

	$data = $data[0];
	if ( isset( $params['params'] ) && is_array( $params['params'] ) ) {
		foreach ( $params['params'] as $param ) {
			$param_data = isset( $data[ $param['param_name'] ] ) ? $data[ $param['param_name'] ] : ( isset( $param['value'] ) ? $param['value'] : '' );
			$param['param_name'] = $params['param_name'] . '_' . $param['param_name'];
			$value_block .= $edit_form->renderField( $param, $param_data );
		}
	}
	$value_block .= '</div>';
	$output = str_replace( '%content%', $value_block, $output );

	return $output;
}

/**
 * Parses attributes string into an associative array.
 *
 * @param string $atts_string
 *
 * @return array|mixed
 * @since 4.4
 */
function vc_param_group_parse_atts( $atts_string ) {
	$array = json_decode( urldecode( $atts_string ), true );

	return $array;
}

add_filter( 'vc_map_get_param_defaults', 'vc_param_group_param_defaults', 10, 2 );
/**
 * Filters the default values for a parameter group.
 *
 * @param string $value
 * @param array $param
 * @return string
 */
function vc_param_group_param_defaults( $value, $param ) {
	if ( 'param_group' === $param['type'] && isset( $param['params'] ) && empty( $value ) ) {
		$defaults = vc_map_get_params_defaults( $param['params'] );
		$value = rawurlencode( wp_json_encode( [ $defaults ] ) );
	}

	return $value;
}
