<?php
/**
 * Configuration file for [vc_custom_heading] shortcode of 'Custom Heading' element.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/ for more detailed information about element attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 *
 * @return array
 */
function vc_custom_heading_element_params() {
	return [
		'name' => esc_html__( 'Custom Heading', 'js_composer' ),
		'base' => 'vc_custom_heading',
		'icon' => 'icon-wpb-ui-custom_heading',
		'element_default_class' => 'vc_do_custom_heading',
		'show_settings_on_create' => true,
		'category' => esc_html__( 'Content', 'js_composer' ),
		'description' => esc_html__( 'Text with custom fonts', 'js_composer' ),
		'params' => [
			[
				'type' => 'dropdown',
				'heading' => esc_html__( 'Text source', 'js_composer' ),
				'param_name' => 'source',
				'value' => [
					esc_html__( 'Custom text', 'js_composer' ) => '',
					esc_html__( 'Post or Page Title', 'js_composer' ) => 'post_title',
				],
				'std' => '',
				'description' => esc_html__( 'Select text source.', 'js_composer' ),
			],
			[
				'type' => 'textarea',
				'heading' => esc_html__( 'Text', 'js_composer' ),
				'param_name' => 'text',
				'admin_label' => true,
				'value' => esc_html__( 'This is custom heading element', 'js_composer' ),
				'description' => esc_html__( 'Note: If you are using non-latin characters be sure to activate them under Settings/WPBakery Page Builder/General Settings.', 'js_composer' ),
				'dependency' => [
					'element' => 'source',
					'is_empty' => true,
				],
			],
			[
				'type' => 'vc_link',
				'heading' => esc_html__( 'URL (Link)', 'js_composer' ),
				'param_name' => 'link',
				'description' => esc_html__( 'Add link to custom heading.', 'js_composer' ),
				// compatible with btn2 and converted from href{btn1}.
			],
			[
				'type' => 'font_container',
				'param_name' => 'font_container',
				'value' => 'tag:h2|text_align:left',
				'settings' => [
					'fields' => [
						'tag' => 'h2',
						// default value h2.
						'text_align',
						'font_size',
						'line_height',
						'color',
						'default_colorpicker_color' => '#111111',
						'tag_description' => esc_html__( 'Select element tag.', 'js_composer' ),
						'text_align_description' => esc_html__( 'Select text alignment.', 'js_composer' ),
						'font_size_description' => esc_html__( 'Enter font size.', 'js_composer' ),
						'line_height_description' => esc_html__( 'Enter line height.', 'js_composer' ),
						'color_description' => esc_html__( 'Select heading color.', 'js_composer' ),
					],
				],
			],
			[
				'type' => 'checkbox',
				'heading' => esc_html__( 'Use theme default font family?', 'js_composer' ),
				'param_name' => 'use_theme_fonts',
				'value' => [ esc_html__( 'Yes', 'js_composer' ) => 'yes' ],
				'description' => esc_html__( 'Use font family from the theme.', 'js_composer' ),
			],
			[
				'type' => 'google_fonts',
				'param_name' => 'google_fonts',
				'value' => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
				'settings' => [
					'fields' => [
						'font_family_description' => esc_html__( 'Select font family.', 'js_composer' ),
						'font_style_description' => esc_html__( 'Select font styling.', 'js_composer' ),
					],
				],
				'dependency' => [
					'element' => 'use_theme_fonts',
					'value_not_equal_to' => 'yes',
				],
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
					'margin-bottom' => '0.625rem',
					'margin-top' => '0',
				],
			],
		],
	];
}
