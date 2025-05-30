<?php
/**
 * Configuration file for [vc_wp_pages] shortcode of 'WP Pages' element.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/ for more detailed information about element attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return [
	'name' => 'WP ' . esc_html__( 'Pages' ),
	'base' => 'vc_wp_pages',
	'icon' => 'icon-wpb-wp',
	'category' => esc_html__( 'WordPress Widgets', 'js_composer' ),
	'class' => 'wpb_vc_wp_widget',
	'weight' => - 50,
	'description' => esc_html__( 'Your sites WordPress Pages', 'js_composer' ),
	'params' => [
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => esc_html__( 'What text use as a widget title. Leave blank to use default widget title.', 'js_composer' ),
			'value' => esc_html__( 'Pages' ),
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Order by', 'js_composer' ),
			'param_name' => 'sortby',
			'value' => [
				esc_html__( 'Page title', 'js_composer' ) => 'post_title',
				esc_html__( 'Page order', 'js_composer' ) => 'menu_order',
				esc_html__( 'Page ID', 'js_composer' ) => 'ID',
			],
			'description' => esc_html__( 'Select how to sort pages.', 'js_composer' ),
			'admin_label' => true,
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Exclude', 'js_composer' ),
			'param_name' => 'exclude',
			'description' => esc_html__( 'Enter page IDs to be excluded (Note: separate values by commas (,)).', 'js_composer' ),
			'admin_label' => true,
		],
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
	],
];
