<?php
/**
 * Configuration file for [vc_single_image] shortcode of 'Single Image' element.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/ for more detailed information about element attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return [
	'name' => esc_html__( 'Single Image', 'js_composer' ),
	'base' => 'vc_single_image',
	'icon' => 'icon-wpb-single-image',
	'element_default_class' => 'wpb_content_element',
	'category' => esc_html__( 'Content', 'js_composer' ),
	'description' => esc_html__( 'Simple image with CSS animation', 'js_composer' ),
	'params' => [
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => esc_html__( 'Enter text used as widget title (Note: located above content element).', 'js_composer' ),
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Image source', 'js_composer' ),
			'param_name' => 'source',
			'value' => [
				esc_html__( 'Media library', 'js_composer' ) => 'media_library',
				esc_html__( 'External link', 'js_composer' ) => 'external_link',
				esc_html__( 'Featured Image', 'js_composer' ) => 'featured_image',
			],
			'std' => 'media_library',
			'description' => esc_html__( 'Select image source.', 'js_composer' ),
		],
		[
			'type' => 'attach_image',
			'heading' => esc_html__( 'Image', 'js_composer' ),
			'param_name' => 'image',
			'value' => '',
			'description' => esc_html__( 'Select image from media library.', 'js_composer' ),
			'dependency' => [
				'element' => 'source',
				'value' => 'media_library',
			],
			'admin_label' => true,
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'External link', 'js_composer' ),
			'param_name' => 'custom_src',
			'description' => esc_html__( 'Select external link.', 'js_composer' ),
			'dependency' => [
				'element' => 'source',
				'value' => 'external_link',
			],
			'admin_label' => true,
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Image size', 'js_composer' ),
			'param_name' => 'img_size',
			'value' => 'thumbnail',
			'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'js_composer' ),
			'dependency' => [
				'element' => 'source',
				'value' => [
					'media_library',
					'featured_image',
				],
			],
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Image size', 'js_composer' ),
			'param_name' => 'external_img_size',
			'value' => '',
			'description' => esc_html__( 'Enter image size in pixels. Example: 200x100 (Width x Height).', 'js_composer' ),
			'dependency' => [
				'element' => 'source',
				'value' => 'external_link',
			],
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Caption', 'js_composer' ),
			'param_name' => 'caption',
			'description' => esc_html__( 'Enter text for image caption.', 'js_composer' ),
			'dependency' => [
				'element' => 'source',
				'value' => 'external_link',
			],
		],
		[
			'type' => 'checkbox',
			'heading' => esc_html__( 'Add caption?', 'js_composer' ),
			'param_name' => 'add_caption',
			'description' => esc_html__( 'Add image caption.', 'js_composer' ),
			'value' => [ esc_html__( 'Yes', 'js_composer' ) => 'yes' ],
			'dependency' => [
				'element' => 'source',
				'value' => [
					'media_library',
					'featured_image',
				],
			],
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Image alignment', 'js_composer' ),
			'param_name' => 'alignment',
			'value' => [
				esc_html__( 'Left', 'js_composer' ) => 'left',
				esc_html__( 'Right', 'js_composer' ) => 'right',
				esc_html__( 'Center', 'js_composer' ) => 'center',
			],
			'description' => esc_html__( 'Select image alignment.', 'js_composer' ),
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Image style', 'js_composer' ),
			'param_name' => 'style',
			'value' => vc_get_shared( 'single image styles' ),
			'description' => esc_html__( 'Select image display style.', 'js_composer' ),
			'dependency' => [
				'element' => 'source',
				'value' => [
					'media_library',
					'featured_image',
				],
			],
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Image style', 'js_composer' ),
			'param_name' => 'external_style',
			'value' => vc_get_shared( 'single image external styles' ),
			'description' => esc_html__( 'Select image display style.', 'js_composer' ),
			'dependency' => [
				'element' => 'source',
				'value' => 'external_link',
			],
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Border color', 'js_composer' ),
			'param_name' => 'border_color',
			'value' => vc_get_shared( 'colors' ),
			'std' => 'grey',
			'dependency' => [
				'element' => 'style',
				'value' => [
					'vc_box_border',
					'vc_box_border_circle',
					'vc_box_outline',
					'vc_box_outline_circle',
					'vc_box_border_circle_2',
					'vc_box_outline_circle_2',
				],
			],
			'description' => esc_html__( 'Border color.', 'js_composer' ),
			'param_holder_class' => 'vc_colored-dropdown',
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Border color', 'js_composer' ),
			'param_name' => 'external_border_color',
			'value' => vc_get_shared( 'colors' ),
			'std' => 'grey',
			'dependency' => [
				'element' => 'external_style',
				'value' => [
					'vc_box_border',
					'vc_box_border_circle',
					'vc_box_outline',
					'vc_box_outline_circle',
				],
			],
			'description' => esc_html__( 'Border color.', 'js_composer' ),
			'param_holder_class' => 'vc_colored-dropdown',
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'On click action', 'js_composer' ),
			'param_name' => 'onclick',
			'value' => [
				esc_html__( 'None', 'js_composer' ) => '',
				esc_html__( 'Link to large image', 'js_composer' ) => 'img_link_large',
				esc_html__( 'Open Lightbox', 'js_composer' ) => 'link_image',
				esc_html__( 'Open custom link', 'js_composer' ) => 'custom_link',
				esc_html__( 'Zoom', 'js_composer' ) => 'zoom',
			],
			'description' => esc_html__( 'Select action for click action.', 'js_composer' ),
			'std' => '',
		],
		[
			'type' => 'href',
			'heading' => esc_html__( 'Image link', 'js_composer' ),
			'param_name' => 'link',
			'description' => esc_html__( 'Enter URL if you want this image to have a link (Note: parameters like "mailto:" are also accepted).', 'js_composer' ),
			'dependency' => [
				'element' => 'onclick',
				'value' => 'custom_link',
			],
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Link Target', 'js_composer' ),
			'param_name' => 'img_link_target',
			'value' => vc_target_param_list(),
			'dependency' => [
				'element' => 'onclick',
				'value' => [
					'custom_link',
					'img_link_large',
				],
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
				'margin-bottom' => '35px',
			],
		],
		// backward compatibility. since 4.6.
		[
			'type' => 'hidden',
			'param_name' => 'img_link_large',
		],
	],
];
