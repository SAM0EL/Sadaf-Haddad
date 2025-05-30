<?php
/**
 * Configuration file for [vc_separator] shortcode of 'Separator' element.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/ for more detailed information about element attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return [
	'name' => esc_html__( 'Separator', 'js_composer' ),
	'base' => 'vc_separator',
	'icon' => 'icon-wpb-ui-separator',
	'element_default_class' => 'wpb_content_element',
	'show_settings_on_create' => true,
	'category' => esc_html__( 'Content', 'js_composer' ),
	'description' => esc_html__( 'Horizontal separator line', 'js_composer' ),
	'params' => [
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Color', 'js_composer' ),
			'param_name' => 'color',
			'value' => array_merge( vc_get_shared( 'colors' ), [ esc_html__( 'Custom color', 'js_composer' ) => 'custom' ] ),
			'std' => 'grey',
			'description' => esc_html__( 'Select color of separator.', 'js_composer' ),
			'param_holder_class' => 'vc_colored-dropdown',
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Alignment', 'js_composer' ),
			'param_name' => 'align',
			'value' => [
				esc_html__( 'Center', 'js_composer' ) => 'align_center',
				esc_html__( 'Left', 'js_composer' ) => 'align_left',
				esc_html__( 'Right', 'js_composer' ) => 'align_right',
			],
			'description' => esc_html__( 'Select separator alignment.', 'js_composer' ),
		],
		[
			'type' => 'colorpicker',
			'heading' => esc_html__( 'Custom Border Color', 'js_composer' ),
			'param_name' => 'accent_color',
			'description' => esc_html__( 'Select border color for your element.', 'js_composer' ),
			'default_colorpicker_color' => '#EBEBEB',
			'dependency' => [
				'element' => 'color',
				'value' => [ 'custom' ],
			],
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Style', 'js_composer' ),
			'param_name' => 'style',
			'value' => vc_get_shared( 'separator styles' ),
			'description' => esc_html__( 'Separator display style.', 'js_composer' ),
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Border width', 'js_composer' ),
			'param_name' => 'border_width',
			'value' => vc_get_shared( 'separator border widths' ),
			'description' => esc_html__( 'Select border width (pixels).', 'js_composer' ),
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Element width', 'js_composer' ),
			'param_name' => 'el_width',
			'value' => vc_get_shared( 'separator widths' ),
			'description' => esc_html__( 'Select separator width (percentage).', 'js_composer' ),
		],
		vc_map_add_css_animation(),
		[
			'type' => 'el_id',
			'heading' => esc_html__( 'Element ID', 'js_composer' ),
			'param_name' => 'el_id',
			'description' => sprintf( esc_html__( 'Enter element ID (Note: make sure it is unique and valid according to %1$sw3c specification%2$s).', 'js_composer' ), '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">', '</a>' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
		],
		[
			'type' => 'css_editor',
			'heading' => esc_html__( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => esc_html__( 'Design Options', 'js_composer' ),
			'value' => [
				'margin-bottom' => '35px',
			],
		],
	],
];
