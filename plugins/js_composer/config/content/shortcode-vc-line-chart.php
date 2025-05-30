<?php
/**
 * Configuration file for [vc_line_chart] shortcode of 'Line Chart' element.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/ for more detailed information about element attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return [
	'name' => esc_html__( 'Line Chart', 'js_composer' ),
	'base' => 'vc_line_chart',
	'class' => '',
	'icon' => 'icon-wpb-vc-line-chart',
	'element_default_class' => 'wpb_content_element',
	'category' => esc_html__( 'Content', 'js_composer' ),
	'description' => esc_html__( 'Line and Bar charts', 'js_composer' ),
	'params' => [
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => esc_html__( 'Enter text used as widget title (Note: located above content element).', 'js_composer' ),
			'admin_label' => true,
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Design', 'js_composer' ),
			'param_name' => 'type',
			'value' => [
				esc_html__( 'Line', 'js_composer' ) => 'line',
				esc_html__( 'Bar', 'js_composer' ) => 'bar',
			],
			'std' => 'bar',
			'description' => esc_html__( 'Select type of chart.', 'js_composer' ),
			'admin_label' => true,
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Style', 'js_composer' ),
			'description' => esc_html__( 'Select chart color style.', 'js_composer' ),
			'param_name' => 'style',
			'value' => [
				esc_html__( 'Flat', 'js_composer' ) => 'flat',
				esc_html__( 'Modern', 'js_composer' ) => 'modern',
				esc_html__( 'Custom', 'js_composer' ) => 'custom',
			],
			'dependency' => [
				'callback' => 'vcChartCustomColorDependency',
			],
		],
		[
			'type' => 'checkbox',
			'heading' => esc_html__( 'Show legend?', 'js_composer' ),
			'param_name' => 'legend',
			'description' => esc_html__( 'If checked, chart will have legend.', 'js_composer' ),
			'value' => [ esc_html__( 'Yes', 'js_composer' ) => 'yes' ],
			'std' => 'yes',
		],
		[
			'type' => 'checkbox',
			'heading' => esc_html__( 'Show hover values?', 'js_composer' ),
			'param_name' => 'tooltips',
			'description' => esc_html__( 'If checked, chart will show values on hover.', 'js_composer' ),
			'value' => [ esc_html__( 'Yes', 'js_composer' ) => 'yes' ],
			'std' => 'yes',
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'X-axis values', 'js_composer' ),
			'param_name' => 'x_values',
			'description' => esc_html__( 'Enter values for axis (Note: separate values with ";").', 'js_composer' ),
			'value' => 'JAN; FEB; MAR; APR; MAY; JUN; JUL; AUG',
		],
		[
			'type' => 'param_group',
			'heading' => esc_html__( 'Values', 'js_composer' ),
			'param_name' => 'values',
			'value' => rawurlencode( wp_json_encode( [
				[
					'title' => esc_html__( 'One', 'js_composer' ),
					'y_values' => '10; 15; 20; 25; 27; 25; 23; 25',
					'color' => 'blue',
				],
				[
					'title' => esc_html__( 'Two', 'js_composer' ),
					'y_values' => '25; 18; 16; 17; 20; 25; 30; 35',
					'color' => 'pink',
				],
			] ) ),
			'params' => [
				[
					'type' => 'textfield',
					'heading' => esc_html__( 'Title', 'js_composer' ),
					'param_name' => 'title',
					'description' => esc_html__( 'Enter title for chart dataset.', 'js_composer' ),
					'admin_label' => true,
				],
				[
					'type' => 'textfield',
					'heading' => esc_html__( 'Y-axis values', 'js_composer' ),
					'param_name' => 'y_values',
					'description' => esc_html__( 'Enter values for axis (Note: separate values with ";").', 'js_composer' ),
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Color', 'js_composer' ),
					'param_name' => 'color',
					'value' => vc_get_shared( 'colors-dashed' ),
					'description' => esc_html__( 'Select chart color.', 'js_composer' ),
					'param_holder_class' => 'vc_colored-dropdown',
				],
				[
					'type' => 'colorpicker',
					'heading' => esc_html__( 'Custom color', 'js_composer' ),
					'param_name' => 'custom_color',
					'description' => esc_html__( 'Select custom chart color.', 'js_composer' ),
				],
			],
			'callbacks' => [
				'after_add' => 'vcChartParamAfterAddCallback',
			],
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Animation', 'js_composer' ),
			'description' => esc_html__( 'Select animation style.', 'js_composer' ),
			'param_name' => 'animation',
			'value' => vc_get_shared( 'animation styles' ),
			'std' => 'easeInOutCubic',
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
