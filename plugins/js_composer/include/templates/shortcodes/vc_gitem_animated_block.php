<?php
/**
 * The template for displaying [vc_gitem_animated_block] shortcode output.
 *
 * This template can be overridden by copying it to yourtheme/vc_templates/vc_gitem_animated_block.php
 *
 * @see https://kb.wpbakery.com/docs/developers-how-tos/change-shortcodes-html-output
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $css
 * @var $animation
 * @var $content - shortcode content
 * Shortcode class
 * @var WPBakeryShortCode_Vc_Gitem_Animated_Block $this
 */
$css = $animation = $animation_attr = '';

extract( shortcode_atts( [
	'css' => '',
	// unmapped.
	'animation' => '',
], $atts ) );

$css_style = '';
$css_class = 'vc_gitem-animated-block ' . vc_shortcode_custom_css_class( $css, ' ' );
if ( ! empty( $animation ) ) {
	$css_class .= ' vc_gitem-animate vc_gitem-animate-' . $animation;
	$animation_attr .= ' data-vc-animation="' . esc_attr( $animation ) . '"';
} elseif ( 'vc_gitem_preview' !== vc_request_param( 'action' ) && vc_verify_admin_nonce() && ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) ) {
	$content = preg_replace( '/(?<=\[)(vc_gitem_zone_b\b)/', '$1 render="no"', $content );
}

$output = '';
$output .= '<div class="' . trim( esc_attr( $css_class ) ) . '" ' . $animation_attr . ( empty( $css_style ) ? '' : ' style="' . esc_attr( $css_style ) . '"' ) . '>';
$output .= do_shortcode( $content );
$output .= '</div>';

echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
