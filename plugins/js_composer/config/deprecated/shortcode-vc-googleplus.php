<?php
/**
 * Configuration file for [vc_googleplus] shortcode of 'Google+ Button' element.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/ for more detailed information about element attributes.
 * @depreacted 6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return [
	'name' => esc_html__( 'Google+ Button', 'js_composer' ),
	'base' => 'vc_googleplus',
	'icon' => 'icon-wpb-application-plus',
	'deprecated' => '6.0',
	'category' => esc_html__( 'Social', 'js_composer' ),
	'description' => esc_html__( 'Recommend on Google', 'js_composer' ),
	'params' => [
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Button size', 'js_composer' ),
			'param_name' => 'type',
			'admin_label' => true,
			'value' => [
				esc_html__( 'Standard', 'js_composer' ) => 'standard',
				esc_html__( 'Small', 'js_composer' ) => 'small',
				esc_html__( 'Medium', 'js_composer' ) => 'medium',
				esc_html__( 'Tall', 'js_composer' ) => 'tall',
			],
			'description' => esc_html__( 'Select button size.', 'js_composer' ),
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Annotation', 'js_composer' ),
			'param_name' => 'annotation',
			'admin_label' => true,
			'value' => [
				esc_html__( 'Bubble', 'js_composer' ) => 'bubble',
				esc_html__( 'Inline', 'js_composer' ) => 'inline',
				esc_html__( 'None', 'js_composer' ) => 'none',
			],
			'std' => 'bubble',
			'description' => esc_html__( 'Select type of annotation.', 'js_composer' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Width', 'js_composer' ),
			'param_name' => 'widget_width',
			'dependency' => [
				'element' => 'annotation',
				'value' => [ 'inline' ],
			],
			'description' => esc_html__( 'Minimum width of 120px to display. If annotation is set to "inline", this parameter sets the width in pixels to use for button and its inline annotation. Default width is 450px.', 'js_composer' ),
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
		],
	],
];
