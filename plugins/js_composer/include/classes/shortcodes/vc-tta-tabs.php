<?php
/**
 * Class that handles specific [vc_tta_tabs] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_tta_tabs.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_Vc_Tta_Accordion' );

/**
 * Class WPBakeryShortCode_Vc_Tta_Tabs
 */
class WPBakeryShortCode_Vc_Tta_Tabs extends WPBakeryShortCode_Vc_Tta_Accordion {

	/**
	 * Layout type.
	 *
	 * @var string
	 */
	public $layout = 'tabs';

	/**
	 * Enqueue shortcode specific scripts.
	 */
	public function enqueueTtaScript() {
		wp_register_script( 'vc_tabs_script', vc_asset_url( 'lib/vc/vc_tabs/vc-tabs.min.js' ), [ 'vc_accordion_script' ], WPB_VC_VERSION, true );
		parent::enqueueTtaScript();
		wp_enqueue_script( 'vc_tabs_script' );
	}

	/**
	 * Add wrapper attributes.
	 *
	 * @return string
	 */
	public function getWrapperAttributes() {
		$attributes = [];
		$attributes[] = 'class="' . esc_attr( $this->getTtaContainerClasses() ) . '"';
		$attributes[] = 'data-vc-action="collapse"';

		if ( isset( $this->atts['autoplay'] ) ) {
			$autoplay = $this->atts['autoplay'];
			if ( $autoplay && 'none' !== $autoplay && intval( $autoplay ) > 0 ) {
				$attributes[] = 'data-vc-tta-autoplay="' . esc_attr( wp_json_encode( [
					'delay' => intval( $autoplay ) * 1000,
				] ) ) . '"';
			}
		}
		if ( ! empty( $this->atts['el_id'] ) ) {
			$attributes[] = 'id="' . esc_attr( $this->atts['el_id'] ) . '"';
		}
		return implode( ' ', $attributes );
	}

	/**
	 * Add specific tta classes.
	 *
	 * @return string
	 */
	public function getTtaGeneralClasses() {
		$classes = parent::getTtaGeneralClasses();

		if ( ! empty( $this->atts['no_fill_content_area'] ) ) {
			$classes .= ' vc_tta-o-no-fill';
		}

		if ( isset( $this->atts['tab_position'] ) ) {
			$classes .= ' ' . $this->getTemplateVariable( 'tab_position' );
		}

		$classes .= ' ' . $this->getParamAlignment( $this->atts, $this->content );

		return $classes;
	}

	/**
	 * Add attributes position.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamTabPosition( $atts, $content ) {
		if ( isset( $atts['tab_position'] ) && strlen( $atts['tab_position'] ) > 0 ) {
			return 'vc_tta-tabs-position-' . $atts['tab_position'];
		}

		return null;
	}

	/**
	 * Add tab position top attributes.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamTabsListTop( $atts, $content ) {
		if ( empty( $atts['tab_position'] ) || 'top' !== $atts['tab_position'] ) {
			return null;
		}

		return $this->getParamTabsList( $atts, $content );
	}

	/**
	 * Add tab position top attributes.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamTabsListBottom( $atts, $content ) {
		if ( empty( $atts['tab_position'] ) || 'bottom' !== $atts['tab_position'] ) {
			return null;
		}

		return $this->getParamTabsList( $atts, $content );
	}

	/**
	 * Pagination is on top only if tabs are at bottom
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamPaginationTop( $atts, $content ) {
		if ( empty( $atts['tab_position'] ) || 'bottom' !== $atts['tab_position'] ) {
			return null;
		}

		return $this->getParamPaginationList( $atts, $content );
	}

	/**
	 * Pagination is at bottom only if tabs are on top
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamPaginationBottom( $atts, $content ) {
		if ( empty( $atts['tab_position'] ) || 'top' !== $atts['tab_position'] ) {
			return null;
		}

		return $this->getParamPaginationList( $atts, $content );
	}

	/**
	 * Add icon to shortcode output.
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public function constructIcon( $atts ) {
		vc_icon_element_fonts_enqueue( $atts['i_type'] );

		$class = 'vc_tta-icon';

		if ( isset( $atts[ 'i_icon_' . $atts['i_type'] ] ) ) {
			$class .= ' ' . $atts[ 'i_icon_' . $atts['i_type'] ];
		} else {
			$class .= ' fa fa-adjust';
		}

		return '<i class="' . $class . '"></i>';
	}

	/**
	 * Get tabs list html.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public function getParamTabsList( $atts, $content ) {
		$is_page_editable = vc_is_page_editable();
		$html = [];
		$html[] = '<div class="vc_tta-tabs-container">';
		$html[] = '<ul class="vc_tta-tabs-list" role="tablist">';
		if ( ! $is_page_editable ) {
			$active_section = $this->getActiveSection( $atts, false );

			foreach ( WPBakeryShortCode_Vc_Tta_Section::$section_info as $nth => $section ) {
				$classes = [ 'vc_tta-tab' ];
				if ( ( $nth + 1 ) === $active_section ) {
					$classes[] = $this->activeClass;
				}

				$title = '<span class="vc_tta-title-text">' . wp_kses_post( $section['title'] ) . '</span>';
				if ( 'true' === $section['add_icon'] ) {
					$icon_html = $this->constructIcon( $section );
					if ( 'left' === $section['i_position'] ) {
						$title = $icon_html . $title;
					} else {
						$title = $title . $icon_html;
					}
				}
				$a_html = '<a href="#' . $section['tab_id'] . '" data-vc-tabs data-vc-container=".vc_tta" role="tab" aria-selected="false" id="' . esc_attr( "tab-{$section['tab_id']}" ) . '">' . $title . '</a>';
				$html[] = '<li class="' . implode( ' ', $classes ) . '" data-vc-tab role="presentation">' . $a_html . '</li>';
			}
		}

		$html[] = '</ul>';
		$html[] = '</div>';

        // phpcs:ignore:WordPress.NamingConventions.ValidHookName.UseUnderscores
		return implode( '', apply_filters( 'vc-tta-get-params-tabs-list', $html, $atts, $content, $this ) );
	}

	/**
	 * Add alignment to shortcode output.
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string|null
	 */
	public function getParamAlignment( $atts, $content ) {
		if ( isset( $atts['alignment'] ) && strlen( $atts['alignment'] ) > 0 ) {
			return 'vc_tta-controls-align-' . $atts['alignment'];
		}

		return null;
	}
}
