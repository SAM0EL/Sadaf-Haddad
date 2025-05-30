<?php
/**
 * Button shortcode grid builder template.
 *
 * @var WPBakeryShortCode_Vc_Btn $vc_btn
 * @var WP_Post $post
 * @var array $atts
 * @var array $data
 * @var string $style
 * @var string $shape
 * @var string $color
 * @var string $custom_background
 * @var string $custom_text
 * @var string $size
 * @var string $align
 * @var string $link
 * @var string $title
 * @var string $button_block
 * @var string $el_id
 * @var string $el_class
 * @var string $outline_custom_color
 * @var string $outline_custom_hover_background
 * @var string $outline_custom_hover_text
 * @var string $add_icon
 * @var string $i_align
 * @var string $i_type
 * @var string $i_icon_fontawesome
 * @var string $i_icon_openiconic
 * @var string $i_icon_typicons
 * @var string $i_icon_entypo
 * @var string $i_icon_linecons
 * @var string $i_icon_pixelicons
 * @var string $css_animation
 * @var string $css
 * @var string $gradient_color_1
 * @var string $gradient_color_2
 * @var string $gradient_custom_color_1
 * @var string $gradient_custom_color_2
 * @var string $gradient_text_color
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$atts = [];
parse_str( $data, $atts );

VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_Vc_Btn' );
$vc_btn = new WPBakeryShortCode_Vc_Btn( [ 'base' => 'vc_btn' ] );

$style = $shape = $color = $size = $custom_background = $custom_text = $align = $link = $title = $button_block = $el_class = $outline_custom_color = $outline_custom_hover_background = $outline_custom_hover_text = $add_icon = $i_align = $i_type = $i_icon_entypo = $i_icon_fontawesome = $i_icon_linecons = $i_icon_pixelicons = $i_icon_typicons = $css = $css_animation = '';
$gradient_color_1 = $gradient_color_2 = $gradient_custom_color_1 = $gradient_custom_color_2 = $gradient_text_color = '';
$custom_onclick = $custom_onclick_code = '';
$a_href = $a_title = $a_target = $a_rel = '';
$styles = [];
$icon_wrapper = false;
$icon_html = false;
$attributes = [];

$atts = vc_map_get_attributes( $vc_btn->getShortcode(), $atts );
extract( $atts );
// parse link.
$link = trim( $link );
$use_link = strlen( $link ) > 0 && 'none' !== $link;

$wrapper_classes = [
	'vc_btn3-container',
	$vc_btn->getExtraClass( $el_class ),
	$vc_btn->getCSSAnimation( $css_animation ),
	'vc_btn3-' . $align,
];

$button_classes = [
	'vc_general',
	'vc_btn3',
	'vc_btn3-size-' . $size,
	'vc_btn3-shape-' . $shape,
	'vc_btn3-style-' . $style,
];

$button_html = $title;

if ( '' === trim( $title ) ) {
	$button_classes[] = 'vc_btn3-o-empty';
	$button_html = '<span class="vc_btn3-placeholder">&nbsp;</span>';
}
if ( 'true' === $button_block && 'inline' !== $align ) {
	$button_classes[] = 'vc_btn3-block';
}
if ( 'true' === $add_icon ) {
	$button_classes[] = 'vc_btn3-icon-' . $i_align;
	vc_icon_element_fonts_enqueue( $i_type );

	if ( isset( ${'i_icon_' . $i_type} ) ) {
		if ( 'pixelicons' === $i_type ) {
			$icon_wrapper = true;
		}
		$icon_class = ${'i_icon_' . $i_type};
	} else {
		$icon_class = 'fa fa-adjust';
	}

	if ( $icon_wrapper ) {
		$icon_html = '<i class="vc_btn3-icon"><span class="vc_btn3-icon-inner ' . esc_attr( $icon_class ) . '"></span></i>';
	} else {
		$icon_html = '<i class="vc_btn3-icon ' . esc_attr( $icon_class ) . '"></i>';
	}

	if ( 'left' === $i_align ) {
		$button_html = $icon_html . ' ' . $button_html;
	} else {
		$button_html .= ' ' . $icon_html;
	}
}

$output = '';
if ( 'custom' === $style ) {
	if ( $custom_background ) {
		$styles[] = vc_get_css_color( 'background-color', $custom_background );
	}

	if ( $custom_text ) {
		$styles[] = vc_get_css_color( 'color', $custom_text );
	}

	if ( ! $custom_background && ! $custom_text ) {
		$button_classes[] = 'vc_btn3-color-grey';
	}
} elseif ( 'outline-custom' === $style ) {
	if ( $outline_custom_color ) {
		$styles[] = vc_get_css_color( 'border-color', $outline_custom_color );
		$styles[] = vc_get_css_color( 'color', $outline_custom_color );
		$attributes[] = 'onmouseleave="this.style.borderColor=\'' . $outline_custom_color . '\'; this.style.backgroundColor=\'transparent\'; this.style.color=\'' . $outline_custom_color . '\'"';
	} else {
		$attributes[] = 'onmouseleave="this.style.borderColor=\'\'; this.style.backgroundColor=\'transparent\'; this.style.color=\'\'"';
	}

	$onmouseenter = [];
	if ( $outline_custom_hover_background ) {
		$onmouseenter[] = 'this.style.borderColor=\'' . $outline_custom_hover_background . '\';';
		$onmouseenter[] = 'this.style.backgroundColor=\'' . $outline_custom_hover_background . '\';';
	}
	if ( $outline_custom_hover_text ) {
		$onmouseenter[] = 'this.style.color=\'' . $outline_custom_hover_text . '\';';
	}
	if ( $onmouseenter ) {
		$attributes[] = 'onmouseenter="' . implode( ' ', $onmouseenter ) . '"';
	}

	if ( ! $outline_custom_color && ! $outline_custom_hover_background && ! $outline_custom_hover_text ) {
		$button_classes[] = 'vc_btn3-color-inverse';

		foreach ( $button_classes as $k => $v ) {
			if ( 'vc_btn3-style-outline-custom' === $v ) {
				unset( $button_classes[ $k ] );
				break;
			}
		}
		$button_classes[] = 'vc_btn3-style-outline';
	}
} elseif ( 'gradient' === $style || 'gradient-custom' === $style ) {

	$gradient_color_1 = vc_convert_vc_color( $gradient_color_1 );
	$gradient_color_2 = vc_convert_vc_color( $gradient_color_2 );

	$button_text_color = '#fff';
	if ( 'gradient-custom' === $style ) {
		$gradient_color_1 = $gradient_custom_color_1;
		$gradient_color_2 = $gradient_custom_color_2;
		$button_text_color = $gradient_text_color;
	}

	$gradient_css = [];
	$gradient_css[] = 'color: ' . $button_text_color;
	$gradient_css[] = 'border: none';
	$gradient_css[] = 'background-color: ' . $gradient_color_1;
	$gradient_css[] = 'background-image: -webkit-linear-gradient(left, ' . $gradient_color_1 . ' 0%, ' . $gradient_color_2 . ' 50%,' . $gradient_color_1 . ' 100%)';
	$gradient_css[] = 'background-image: linear-gradient(to right, ' . $gradient_color_1 . ' 0%, ' . $gradient_color_2 . ' 50%,' . $gradient_color_1 . ' 100%)';
	$gradient_css[] = '-webkit-transition: all .2s ease-in-out';
	$gradient_css[] = 'transition: all .2s ease-in-out';
	$gradient_css[] = 'background-size: 200% 100%';

	// hover css.
	$gradient_css_hover = [];
	$gradient_css_hover[] = 'color: ' . $button_text_color;
	$gradient_css_hover[] = 'background-color: ' . $gradient_color_2;
	$gradient_css_hover[] = 'border: none';
	$gradient_css_hover[] = 'background-position: 100% 0';

	$uid = uniqid();
	$first_tag = 'style';
	$output .= '<' . $first_tag . '>.vc_btn3-style-' . esc_attr( $style ) . '.vc_btn-gradient-btn-' . esc_attr( $uid ) . ':hover{' . esc_attr( implode( ';', $gradient_css_hover ) ) . ';}</' . $first_tag . '>';
	$output .= '<' . $first_tag . '>.vc_btn3-style-' . esc_attr( $style ) . '.vc_btn-gradient-btn-' . esc_attr( $uid ) . '{' . esc_attr( implode( ';', $gradient_css ) ) . ';}</' . $first_tag . '>';
	$button_classes[] = 'vc_btn-gradient-btn-' . $uid;
	$attributes[] = 'data-vc-gradient-1="' . $gradient_color_1 . '"';
	$attributes[] = 'data-vc-gradient-2="' . $gradient_color_2 . '"';
} else {
	$button_classes[] = 'vc_btn3-color-' . $color;
}

if ( $styles ) {
	$attributes[] = 'style="' . implode( ' ', $styles ) . '"';
}

$class_to_filter = implode( ' ', array_filter( $wrapper_classes ) );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $vc_btn->settings( 'base' ), $atts );

if ( $button_classes ) {
	$button_classes = esc_attr( apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $button_classes ) ), $vc_btn->settings( 'base' ), $atts ) );
	$attributes[] = 'class="' . trim( $button_classes ) . '"';
}

if ( $use_link ) {
	$link_output = vc_gitem_create_link_real( $atts, $post, 'vc_general vc_btn3 ' . trim( $button_classes ), $title );
	$attributes[] = $link_output;
}

if ( ! empty( $custom_onclick ) && $custom_onclick_code ) {
	$attributes[] = 'onclick="' . esc_attr( $custom_onclick_code ) . '"';
}

$attributes = implode( ' ', $attributes );

$output .= '<div class="' . esc_attr( trim( $css_class ) ) . '"' . ( ! empty( $el_id ) ? ' id="' . esc_attr( $el_id ) . '"' : '' ) . '>';

if ( $use_link ) {
	if ( preg_match( '/href=\"[^\"]+/', $link_output ) ) {
		$output .= '<a ' . $attributes . '>' . $button_html . '</a>';
	} elseif ( 'load-more-grid' === $link ) {
		$output .= '<a href="javascript:;" ' . $attributes . '>' . $button_html . '</a>';
	}
} else {
	$output .= '<button ' . $attributes . '>' . $button_html . '</button>';
}

$output .= '</div>';

return $output;
