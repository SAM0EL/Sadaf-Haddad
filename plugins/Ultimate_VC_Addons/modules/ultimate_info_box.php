<?php
/**
 * Add-on Name: Info Box
 * Add-on URI: https://www.brainstormforce.com
 *
 * @package Ultimate_VC_Addons_Icons_Box.
 */

if ( ! class_exists( 'Ultimate_VC_Addons_Icons_Box' ) ) {
	/**
	 * Class Ultimate_VC_Addons_Icons_Box.
	 *
	 * @class Ultimate_VC_Addons_Icons_Box.
	 */
	class Ultimate_VC_Addons_Icons_Box {
		/**
		 * Constructor function that constructs default values for the Ultimate_List_Icon.
		 *
		 * @method __construct
		 */
		public function __construct() {
			if ( Ultimate_VC_Addons::$uavc_editor_enable ) {
				// Initialize the icon box component for WPBakery Page Builder.
				add_action( 'init', array( &$this, 'icon_box_init' ) );
			}
			// Add shortcode for icon box.
			add_shortcode( 'bsf-info-box', array( &$this, 'icon_boxes' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'icon_box_scripts' ), 1 );

		}
		/** Shortcode handler function for icon-box.
		 *
		 * @param array  $atts Attributes.
		 * @param string $content Content.
		 */
		public function icon_boxes( $atts, $content = null ) {
			$css_class             = '';
			$target                = '';
			$link_title            = '';
			$rel                   = '';
			$ult_info_box_settings = shortcode_atts(
				array(
					'icon_type'              => 'selector',
					'icon'                   => 'none',
					'icon_img'               => '',
					'img_width'              => '48',
					'icon_size'              => '32',
					'icon_color'             => '#333',
					'icon_style'             => 'none',
					'icon_color_bg'          => '#ffffff',
					'icon_color_border'      => '#333333',
					'icon_border_style'      => '',
					'icon_border_size'       => '1',
					'icon_border_radius'     => '500',
					'icon_border_spacing'    => '50',
					'icon_animation'         => '',
					'title'                  => '',
					'link'                   => '',
					'hover_effect'           => 'style_1',
					'pos'                    => 'default',
					'box_min_height'         => '',
					'box_border_style'       => '',
					'box_border_width'       => '',
					'box_border_color'       => '',
					'box_bg_color'           => '',
					'read_more'              => 'none',
					'read_text'              => 'Read More',
					'heading_tag'            => 'h3',
					'title_font'             => '',
					'title_font_style'       => '',
					'title_font_size'        => '',
					'title_font_line_height' => '',
					'title_font_color'       => '',
					'desc_font'              => '',
					'desc_font_style'        => '',
					'desc_font_size'         => '',
					'desc_font_color'        => '',
					'desc_font_line_height'  => '',
					'el_class'               => '',
					'css_info_box'           => '',
				),
				$atts,
				'bsf-info-box'
			);

			// Ensure heading_tag is a valid HTML tag.
			$valid_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'p', 'span' );

			// Style option for Ribbon Module.
			if ( ! in_array( $ult_info_box_settings['heading_tag'], $valid_tags ) ) {
				$ult_info_box_settings['heading_tag'] = 'h3';
			}

			$html             = '';
			$target           = '';
			$suffix           = '';
			$prefix           = '';
			$title_style      = '';
			$desc_style       = '';
			$inf_design_style = '';
			$inf_design_style = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $ult_info_box_settings['css_info_box'], ' ' ), 'bsf-info-box', $atts );
			$inf_design_style = esc_attr( $inf_design_style );
			$box_icon         = do_shortcode( '[just_icon icon_type="' . esc_attr( $ult_info_box_settings['icon_type'] ) . '" icon="' . esc_attr( $ult_info_box_settings['icon'] ) . '" icon_img="' . esc_attr( $ult_info_box_settings['icon_img'] ) . '" img_width="' . esc_attr( $ult_info_box_settings['img_width'] ) . '" icon_size="' . esc_attr( $ult_info_box_settings['icon_size'] ) . '" icon_color="' . esc_attr( $ult_info_box_settings['icon_color'] ) . '" icon_style="' . esc_attr( $ult_info_box_settings['icon_style'] ) . '" icon_color_bg="' . esc_attr( $ult_info_box_settings['icon_color_bg'] ) . '" icon_color_border="' . esc_attr( $ult_info_box_settings['icon_color_border'] ) . '"  icon_border_style="' . esc_attr( $ult_info_box_settings['icon_border_style'] ) . '" icon_border_size="' . esc_attr( $ult_info_box_settings['icon_border_size'] ) . '" icon_border_radius="' . esc_attr( $ult_info_box_settings['icon_border_radius'] ) . '" icon_border_spacing="' . esc_attr( $ult_info_box_settings['icon_border_spacing'] ) . '" icon_animation="' . esc_attr( $ult_info_box_settings['icon_animation'] ) . '"]' );
			$prefix          .= '<div class="aio-icon-component ' . esc_attr( $inf_design_style ) . ' ' . esc_attr( $css_class ) . ' ' . esc_attr( $ult_info_box_settings['el_class'] ) . ' ' . esc_attr( $ult_info_box_settings['hover_effect'] ) . '">';
			$suffix          .= '</div> <!-- aio-icon-component -->';

			$ex_class = '';
			$ic_class = '';
			if ( '' != $ult_info_box_settings['pos'] ) {
				$ex_class .= $ult_info_box_settings['pos'] . '-icon';
				$ic_class  = 'aio-icon-' . $ult_info_box_settings['pos'];
			}

			/* title */
			if ( '' != $ult_info_box_settings['title_font'] ) {
				$font_family = get_ultimate_font_family( $ult_info_box_settings['title_font'] );
				if ( '' != $font_family ) {
					$title_style .= 'font-family:\'' . esc_attr( $font_family ) . '\';';
				}
			}
			if ( '' != $ult_info_box_settings['title_font_style'] ) {
				$title_style .= esc_attr( get_ultimate_font_style( $ult_info_box_settings['title_font_style'] ) );
			}

			if ( is_numeric( $ult_info_box_settings['title_font_size'] ) ) {
				$ult_info_box_settings['title_font_size'] = 'desktop:' . esc_attr( $ult_info_box_settings['title_font_size'] ) . 'px;';
			}
			if ( is_numeric( $ult_info_box_settings['title_font_line_height'] ) ) {
				$ult_info_box_settings['title_font_line_height'] = 'desktop:' . esc_attr( $ult_info_box_settings['title_font_line_height'] ) . 'px;';
			}
			$info_box_id        = 'Info-box-wrap-' . wp_rand( 1000, 9999 );
			$info_box_args      = array(
				'target'      => '#' . $info_box_id . ' .aio-icon-title', // set targeted element e.g. unique class/id etc.
				'media_sizes' => array(
					'font-size'   => $ult_info_box_settings['title_font_size'], // set 'css property' & 'ultimate_responsive' sizes. Here $title_responsive_font_size holds responsive font sizes from user input.
					'line-height' => $ult_info_box_settings['title_font_line_height'],
				),
			);
			$info_box_data_list = get_ultimate_vc_responsive_media_css( $info_box_args );

			if ( '' != $ult_info_box_settings['title_font_color'] ) {
				$title_style .= 'color:' . $ult_info_box_settings['title_font_color'] . ';';
			}

			/* description */
			if ( '' != $ult_info_box_settings['desc_font'] ) {
				$font_family = get_ultimate_font_family( $ult_info_box_settings['desc_font'] );
				if ( '' !== $font_family ) {
					$desc_style .= 'font-family:\'' . esc_attr( $font_family ) . '\';';
				}
			}
			if ( '' != $ult_info_box_settings['desc_font_style'] ) {
				$desc_style .= esc_attr( get_ultimate_font_style( $ult_info_box_settings['desc_font_style'] ) );
			}

			if ( is_numeric( $ult_info_box_settings['desc_font_size'] ) ) {
				$ult_info_box_settings['desc_font_size'] = 'desktop:' . esc_attr( $ult_info_box_settings['desc_font_size'] ) . 'px;';
			}
			if ( is_numeric( $ult_info_box_settings['desc_font_line_height'] ) ) {
				$ult_info_box_settings['desc_font_line_height'] = 'desktop:' . esc_attr( $ult_info_box_settings['desc_font_line_height'] ) . 'px;';
			}

			$info_box_desc_args      = array(
				'target'      => '#' . $info_box_id . ' .aio-icon-description', // set targeted element e.g. unique class/id etc.
				'media_sizes' => array(
					'font-size'   => $ult_info_box_settings['desc_font_size'], // set 'css property' & 'ultimate_responsive' sizes. Here $title_responsive_font_size holds responsive font sizes from user input.
					'line-height' => $ult_info_box_settings['desc_font_line_height'],
				),
			);
			$info_box_desc_data_list = get_ultimate_vc_responsive_media_css( $info_box_desc_args );
			if ( '' != $ult_info_box_settings['desc_font_color'] ) {
				$desc_style .= 'color:' . esc_attr( $ult_info_box_settings['desc_font_color'] ) . ';';
			}

			$box_style      = '';
			$box_style_data = '';
			if ( 'square_box' == $ult_info_box_settings['pos'] ) {
				if ( '' != $ult_info_box_settings['box_min_height'] ) {
					$box_style_data .= "data-min-height='" . esc_attr( $ult_info_box_settings['box_min_height'] ) . "px'";
				}
				if ( '' != $ult_info_box_settings['box_border_color'] ) {
					$box_style .= 'border-color:' . $ult_info_box_settings['box_border_color'] . ';';
				}
				if ( '' != $ult_info_box_settings['box_border_style'] ) {
					$box_style .= 'border-style:' . esc_attr( $ult_info_box_settings['box_border_style'] ) . ';';
				}
				if ( '' != $ult_info_box_settings['box_border_width'] ) {
					$box_style .= 'border-width:' . intval( $ult_info_box_settings['box_border_width'] ) . 'px;';
				}
				if ( '' != $ult_info_box_settings['box_bg_color'] ) {
					$box_style .= 'background-color:' . esc_attr( $ult_info_box_settings['box_bg_color'] ) . ';';
				}
			}
			$html .= '<div id="' . esc_attr( $info_box_id ) . '" class="aio-icon-box ' . esc_attr( $ex_class ) . '" style="' . esc_attr( $box_style ) . '" ' . $box_style_data . ' >';

			if ( 'heading-right' == $ult_info_box_settings['pos'] || 'right' == $ult_info_box_settings['pos'] ) {
				if ( 'right' == $ult_info_box_settings['pos'] ) {
					$html .= '<div class="aio-ibd-block" >';
				}
				if ( '' !== $ult_info_box_settings['title'] ) {
					$html       .= '<div class="aio-icon-header" >';
					$link_prefix = '';
					$link_sufix  = '';
					if ( 'none' !== $ult_info_box_settings['link'] ) {
						if ( 'title' == $ult_info_box_settings['read_more'] ) {
							$href = vc_build_link( $ult_info_box_settings['link'] );

							$url         = ( isset( $href['url'] ) && '' !== $href['url'] ) ? esc_url( $href['url'] ) : '';
							$target      = ( isset( $href['target'] ) && '' !== $href['target'] ) ? esc_attr( trim( $href['target'] ) ) : '';
							$link_title  = ( isset( $href['title'] ) && '' !== $href['title'] ) ? esc_attr( $href['title'] ) : '';
							$rel         = ( isset( $href['rel'] ) && '' !== $href['rel'] ) ? esc_attr( $href['rel'] ) : '';
							$link_prefix = '<a class="aio-icon-box-link" ' . Ultimate_VC_Addons::uavc_link_init( $url, $target, $link_title, $rel ) . '>';
							$link_sufix  = '</a>';
						}
					}
					$html .= $link_prefix . '<' . esc_attr( sanitize_key( $ult_info_box_settings['heading_tag'] ) ) . ' class="aio-icon-title ult-responsive" ' . $info_box_data_list . ' style="' . esc_attr( $title_style ) . '">' . $ult_info_box_settings['title'] . '</' . esc_attr( sanitize_key( $ult_info_box_settings['heading_tag'] ) ) . '>' . $link_sufix;
					$html .= '</div> <!-- header -->';
				}
				if ( 'right' !== $ult_info_box_settings['pos'] ) {
					if ( 'none' !== $ult_info_box_settings['icon'] || '' !== $ult_info_box_settings['icon_img'] ) {
						$html .= '<div class="' . esc_attr( $ic_class ) . '" >' . $box_icon . '</div>';
					}
				}
				if ( '' !== $content ) {
					$html .= '<div class="aio-icon-description ult-responsive" ' . $info_box_desc_data_list . ' style="' . esc_attr( $desc_style ) . '">';
					$html .= do_shortcode( $content );
					if ( 'none' !== $ult_info_box_settings['link'] ) {
						if ( 'more' == $ult_info_box_settings['read_more'] ) {
							$href = vc_build_link( $ult_info_box_settings['link'] );

							$url        = ( isset( $href['url'] ) && '' !== $href['url'] ) ? esc_url( $href['url'] ) : '';
							$target     = ( isset( $href['target'] ) && '' !== $href['target'] ) ? esc_attr( trim( $href['target'] ) ) : '';
							$link_title = ( isset( $href['title'] ) && '' !== $href['title'] ) ? esc_attr( $href['title'] ) : '';
							$rel        = ( isset( $href['rel'] ) && '' !== $href['rel'] ) ? esc_attr( $href['rel'] ) : '';

							$more_link  = '<a class="aio-icon-read x" ' . Ultimate_VC_Addons::uavc_link_init( $url, $target, $link_title, $rel ) . '>';
							$more_link .= $ult_info_box_settings['read_text'];
							$more_link .= '&nbsp;&raquo;';
							$more_link .= '</a>';
							$html      .= $more_link;
						}
					}
					$html .= '</div> <!-- description -->';
				}
				if ( 'right' == $ult_info_box_settings['pos'] ) {
					$html .= '</div> <!-- aio-ibd-block -->';
					if ( 'none' !== $ult_info_box_settings['icon'] || '' !== $ult_info_box_settings['icon_img'] ) {
						$html .= '<div class="' . esc_attr( $ic_class ) . '">' . $box_icon . '</div>';
					}
				}
			} else {
				if ( 'none' !== $ult_info_box_settings['icon'] || '' != $ult_info_box_settings['icon_img'] ) {
					$html .= '<div class="' . esc_attr( $ic_class ) . '">' . $box_icon . '</div>';
				}
				if ( 'left' == $ult_info_box_settings['pos'] ) {
					$html .= '<div class="aio-ibd-block">';
				}
				if ( '' !== $ult_info_box_settings['title'] ) {
					$html       .= '<div class="aio-icon-header" >';
					$link_prefix = '';
					$link_sufix  = '';
					if ( 'none' !== $ult_info_box_settings['link'] ) {
						if ( 'title' == $ult_info_box_settings['read_more'] ) {
							$href = vc_build_link( $ult_info_box_settings['link'] );

							$url        = ( isset( $href['url'] ) && '' !== $href['url'] ) ? esc_url( $href['url'] ) : '';
							$target     = ( isset( $href['target'] ) && '' !== $href['target'] ) ? esc_attr( trim( $href['target'] ) ) : '';
							$link_title = ( isset( $href['title'] ) && '' !== $href['title'] ) ? esc_attr( $href['title'] ) : '';
							$rel        = ( isset( $href['rel'] ) && '' !== $href['rel'] ) ? esc_attr( $href['rel'] ) : '';

							$link_prefix = '<a class="aio-icon-box-link" ' . Ultimate_VC_Addons::uavc_link_init( $url, $target, $link_title, $rel ) . '>';
							$link_sufix  = '</a>';
						}
					}
					$html .= $link_prefix . '<' . esc_attr( sanitize_key( $ult_info_box_settings['heading_tag'] ) ) . ' class="aio-icon-title ult-responsive" ' . $info_box_data_list . ' style="' . esc_attr( $title_style ) . '">' . $ult_info_box_settings['title'] . '</' . esc_attr( sanitize_key( $ult_info_box_settings['heading_tag'] ) ) . '>' . $link_sufix;
					$html .= '</div> <!-- header -->';
				}
				if ( '' !== $content ) {
					$html .= '<div class="aio-icon-description ult-responsive" ' . $info_box_desc_data_list . ' style="' . esc_attr( $desc_style ) . '">';
					$html .= do_shortcode( $content );
					if ( 'none' !== $ult_info_box_settings['link'] ) {
						if ( 'more' == $ult_info_box_settings['read_more'] ) {
							$href = vc_build_link( $ult_info_box_settings['link'] );

							$url        = ( isset( $href['url'] ) && '' !== $href['url'] ) ? esc_url( $href['url'] ) : '';
							$target     = ( isset( $href['target'] ) && '' !== $href['target'] ) ? esc_attr( trim( $href['target'] ) ) : '';
							$link_title = ( isset( $href['title'] ) && '' !== $href['title'] ) ? esc_attr( $href['title'] ) : '';
							$rel        = ( isset( $href['rel'] ) && '' !== $href['rel'] ) ? esc_attr( $href['rel'] ) : '';

							$more_link  = '<a class="aio-icon-read xx" ' . Ultimate_VC_Addons::uavc_link_init( $url, $target, $link_title, $rel ) . '>';
							$more_link .= $ult_info_box_settings['read_text'];
							$more_link .= '&nbsp;&raquo;';
							$more_link .= '</a>';
							$html      .= $more_link;
						}
					}
					$html .= '</div> <!-- description -->';
				}
				if ( 'left' == $ult_info_box_settings['pos'] ) {
					$html .= '</div> <!-- aio-ibd-block -->';
				}
			}

			$html .= '</div> <!-- aio-icon-box -->';
			if ( 'none' !== $ult_info_box_settings['link'] ) {
				if ( 'box' == $ult_info_box_settings['read_more'] ) {
					$href = vc_build_link( $ult_info_box_settings['link'] );

					$url        = ( isset( $href['url'] ) && '' !== $href['url'] ) ? esc_url( $href['url'] ) : '';
					$target     = ( isset( $href['target'] ) && '' !== $href['target'] ) ? esc_attr( trim( $href['target'] ) ) : '';
					$link_title = ( isset( $href['title'] ) && '' !== $href['title'] ) ? esc_attr( $href['title'] ) : '';
					$rel        = ( isset( $href['rel'] ) && '' !== $href['rel'] ) ? esc_attr( $href['rel'] ) : '';

					$output = $prefix . '<a class="aio-icon-box-link" ' . Ultimate_VC_Addons::uavc_link_init( $url, $target, $link_title, $rel ) . '>' . $html . '</a>' . $suffix;
				} else {
					$output = $prefix . $html . $suffix;
				}
			} else {
				$output = $prefix . $html . $suffix;
			}
			$is_preset = false; // Display settings for Preset.
			if ( isset( $_GET['preset'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verification is NOT needed as It does not modify any data.
				$is_preset = true;
			}
			if ( $is_preset ) {
				$text = 'array ( ';
				foreach ( $atts as $key => $att ) {
					$text .= '<br/>	\'' . $key . '\' => \'' . $att . '\',';
				}
				if ( '' != $content ) {
					$text .= '<br/>	\'content\' => \'' . $content . '\',';
				}
				$text   .= '<br/>)';
				$output .= '<pre>';
				$output .= $text;
				$output .= '</pre>';
			}
			return $output;
		}
		/**
		 * Add icon box Component.
		 */
		public function icon_box_init() {
			if ( function_exists( 'vc_map' ) ) {
				vc_map(
					array(
						'name'                    => __( 'Info Box', 'ultimate_vc' ),
						'base'                    => 'bsf-info-box',
						'icon'                    => 'vc_info_box',
						'class'                   => 'info_box',
						'category'                => 'Ultimate VC Addons',
						'description'             => __( 'Adds icon box with custom font icon', 'ultimate_vc' ),
						'controls'                => 'full',
						'show_settings_on_create' => true,
						'params'                  => array(
							array(
								'type'        => 'dropdown',
								'class'       => '',
								'heading'     => __( 'Icon to display:', 'ultimate_vc' ),
								'param_name'  => 'icon_type',
								'value'       => array(
									__( 'Font Icon Manager', 'ultimate_vc' ) => 'selector',
									__( 'Custom Image Icon', 'ultimate_vc' ) => 'custom',
								),
								'description' => __( 'Use an existing font icon or upload a custom image.', 'ultimate_vc' ),
							),
							array(
								'type'        => 'icon_manager',
								'class'       => '',
								'heading'     => __( 'Select Icon ', 'ultimate_vc' ),
								'param_name'  => 'icon',
								'value'       => '',
								'description' => __( "Click and select icon of your choice. If you can't find the one that suits for your purpose", 'ultimate_vc' ) . ', ' . __( 'you can', 'ultimate_vc' ) . " <a href='admin.php?page=bsf-font-icon-manager' target='_blank' rel='noopener'>" . __( 'add new here', 'ultimate_vc' ) . '</a>.',
								'dependency'  => array(
									'element' => 'icon_type',
									'value'   => array( 'selector' ),
								),
							),
							array(
								'type'        => 'ult_img_single',
								'class'       => '',
								'heading'     => __( 'Upload Image Icon:', 'ultimate_vc' ),
								'param_name'  => 'icon_img',
								'value'       => '',
								'description' => __( 'Upload the custom image icon.', 'ultimate_vc' ),
								'dependency'  => array(
									'element' => 'icon_type',
									'value'   => array( 'custom' ),
								),
							),
							array(
								'type'        => 'number',
								'class'       => '',
								'heading'     => __( 'Image Width', 'ultimate_vc' ),
								'param_name'  => 'img_width',
								'value'       => 48,
								'min'         => 16,
								'max'         => 512,
								'suffix'      => 'px',
								'description' => __( 'Provide image width', 'ultimate_vc' ),
								'dependency'  => array(
									'element' => 'icon_type',
									'value'   => array( 'custom' ),
								),
							),
							array(
								'type'        => 'number',
								'class'       => '',
								'heading'     => __( 'Size of Icon', 'ultimate_vc' ),
								'param_name'  => 'icon_size',
								'value'       => 32,
								'min'         => 12,
								'max'         => 72,
								'suffix'      => 'px',
								'description' => __( 'How big would you like it?', 'ultimate_vc' ),
								'dependency'  => array(
									'element' => 'icon_type',
									'value'   => array( 'selector' ),
								),
							),
							array(
								'type'        => 'colorpicker',
								'class'       => '',
								'heading'     => __( 'Color', 'ultimate_vc' ),
								'param_name'  => 'icon_color',
								'value'       => '#333333',
								'description' => __( 'Give it a nice paint!', 'ultimate_vc' ),
								'dependency'  => array(
									'element' => 'icon_type',
									'value'   => array( 'selector' ),
								),
							),
							array(
								'type'        => 'dropdown',
								'class'       => '',
								'heading'     => __( 'Icon Style', 'ultimate_vc' ),
								'param_name'  => 'icon_style',
								'value'       => array(
									__( 'Simple', 'ultimate_vc' ) => 'none',
									__( 'Circle Background', 'ultimate_vc' ) => 'circle',
									__( 'Square Background', 'ultimate_vc' ) => 'square',
									__( 'Design your own', 'ultimate_vc' ) => 'advanced',
								),
								'description' => __( 'We have given three quick preset if you are in a hurry. Otherwise, create your own with various options.', 'ultimate_vc' ),
							),
							array(
								'type'        => 'colorpicker',
								'class'       => '',
								'heading'     => __( 'Background Color', 'ultimate_vc' ),
								'param_name'  => 'icon_color_bg',
								'value'       => '#ffffff',
								'description' => __( 'Select background color for icon.', 'ultimate_vc' ),
								'dependency'  => array(
									'element' => 'icon_style',
									'value'   => array( 'circle', 'square', 'advanced' ),
								),
							),
							array(
								'type'        => 'dropdown',
								'class'       => '',
								'heading'     => __( 'Icon Border Style', 'ultimate_vc' ),
								'param_name'  => 'icon_border_style',
								'value'       => array(
									__( 'None', 'ultimate_vc' ) => '',
									__( 'Solid', 'ultimate_vc' ) => 'solid',
									__( 'Dashed', 'ultimate_vc' ) => 'dashed',
									__( 'Dotted', 'ultimate_vc' ) => 'dotted',
									__( 'Double', 'ultimate_vc' ) => 'double',
									__( 'Inset', 'ultimate_vc' ) => 'inset',
									__( 'Outset', 'ultimate_vc' ) => 'outset',
								),
								'description' => __( 'Select the border style for icon.', 'ultimate_vc' ),
								'dependency'  => array(
									'element' => 'icon_style',
									'value'   => array( 'advanced' ),
								),
							),
							array(
								'type'        => 'colorpicker',
								'class'       => '',
								'heading'     => __( 'Border Color', 'ultimate_vc' ),
								'param_name'  => 'icon_color_border',
								'value'       => '#333333',
								'description' => __( 'Select border color for icon.', 'ultimate_vc' ),
								'dependency'  => array(
									'element'   => 'icon_border_style',
									'not_empty' => true,
								),
							),
							array(
								'type'        => 'number',
								'class'       => '',
								'heading'     => __( 'Border Width', 'ultimate_vc' ),
								'param_name'  => 'icon_border_size',
								'value'       => 1,
								'min'         => 1,
								'max'         => 10,
								'suffix'      => 'px',
								'description' => __( 'Thickness of the border.', 'ultimate_vc' ),
								'dependency'  => array(
									'element'   => 'icon_border_style',
									'not_empty' => true,
								),
							),
							array(
								'type'        => 'number',
								'class'       => '',
								'heading'     => __( 'Border Radius', 'ultimate_vc' ),
								'param_name'  => 'icon_border_radius',
								'value'       => 500,
								'min'         => 1,
								'max'         => 500,
								'suffix'      => 'px',
								'description' => __( '0 pixel value will create a square border. As you increase the value, the shape convert in circle slowly. (e.g 500 pixels).', 'ultimate_vc' ),
								'dependency'  => array(
									'element'   => 'icon_border_style',
									'not_empty' => true,
								),
							),
							array(
								'type'        => 'number',
								'class'       => '',
								'heading'     => __( 'Background Size', 'ultimate_vc' ),
								'param_name'  => 'icon_border_spacing',
								'value'       => 50,
								'min'         => 30,
								'max'         => 500,
								'suffix'      => 'px',
								'description' => __( 'Spacing from center of the icon till the boundary of border / background', 'ultimate_vc' ),
								'dependency'  => array(
									'element' => 'icon_style',
									'value'   => array( 'advanced' ),
								),
							),
							array(
								'type'        => 'dropdown',
								'class'       => '',
								'heading'     => __( 'Animation', 'ultimate_vc' ),
								'param_name'  => 'icon_animation',
								'value'       => array(
									__( 'No Animation', 'ultimate_vc' ) => '',
									__( 'Swing', 'ultimate_vc' ) => 'swing',
									__( 'Pulse', 'ultimate_vc' ) => 'pulse',
									__( 'Fade In', 'ultimate_vc' ) => 'fadeIn',
									__( 'Fade In Up', 'ultimate_vc' ) => 'fadeInUp',
									__( 'Fade In Down', 'ultimate_vc' ) => 'fadeInDown',
									__( 'Fade In Left', 'ultimate_vc' ) => 'fadeInLeft',
									__( 'Fade In Right', 'ultimate_vc' ) => 'fadeInRight',
									__( 'Fade In Up Long', 'ultimate_vc' ) => 'fadeInUpBig',
									__( 'Fade In Down Long', 'ultimate_vc' ) => 'fadeInDownBig',
									__( 'Fade In Left Long', 'ultimate_vc' ) => 'fadeInLeftBig',
									__( 'Fade In Right Long', 'ultimate_vc' ) => 'fadeInRightBig',
									__( 'Slide In Down', 'ultimate_vc' ) => 'slideInDown',
									__( 'Slide In Left', 'ultimate_vc' ) => 'slideInLeft',
									__( 'Slide In Left', 'ultimate_vc' ) => 'slideInLeft',
									__( 'Bounce In', 'ultimate_vc' ) => 'bounceIn',
									__( 'Bounce In Up', 'ultimate_vc' ) => 'bounceInUp',
									__( 'Bounce In Down', 'ultimate_vc' ) => 'bounceInDown',
									__( 'Bounce In Left', 'ultimate_vc' ) => 'bounceInLeft',
									__( 'Bounce In Right', 'ultimate_vc' ) => 'bounceInRight',
									__( 'Rotate In', 'ultimate_vc' ) => 'rotateIn',
									__( 'Light Speed In', 'ultimate_vc' ) => 'lightSpeedIn',
									__( 'Roll In', 'ultimate_vc' ) => 'rollIn',
								),
								'description' => __( 'Like CSS3 Animations? We have several options for you!', 'ultimate_vc' ),
							),
							// Icon Box Heading.
							array(
								'type'             => 'textfield',
								'class'            => '',
								'heading'          => __( 'Title', 'ultimate_vc' ),
								'param_name'       => 'title',
								'admin_label'      => true,
								'value'            => '',
								'description'      => __( 'Provide the title for this icon box.', 'ultimate_vc' ),
								'edit_field_class' => 'vc_col-sm-8',
							),
							array(
								'type'             => 'dropdown',
								'heading'          => esc_html__( 'Tag', 'ultimate_vc' ),
								'param_name'       => 'heading_tag',
								'value'            => array(
									esc_html__( 'Default', 'ultimate_vc' ) => 'h3',
									esc_html__( 'H1', 'ultimate_vc' ) => 'h1',
									esc_html__( 'H2', 'ultimate_vc' ) => 'h2',
									esc_html__( 'H4', 'ultimate_vc' ) => 'h4',
									esc_html__( 'H5', 'ultimate_vc' ) => 'h5',
									esc_html__( 'H6', 'ultimate_vc' ) => 'h6',
									esc_html__( 'Div', 'ultimate_vc' ) => 'div',
									esc_html__( 'p', 'ultimate_vc' )  => 'p',
									esc_html__( 'span', 'ultimate_vc' ) => 'span',
								),
								'description'      => __( 'Default is H3', 'ultimate_vc' ),
								'edit_field_class' => 'vc_col-sm-4',
							),
							// Add some description.
							array(
								'type'             => 'textarea_html',
								'class'            => '',
								'heading'          => __( 'Description', 'ultimate_vc' ),
								'param_name'       => 'content',
								'value'            => '',
								'description'      => __( 'Provide the description for this icon box.', 'ultimate_vc' ),
								'edit_field_class' => 'ult_hide_editor_fullscreen vc_col-xs-12 vc_column wpb_el_type_textarea_html vc_wrapper-param-type-textarea_html vc_shortcode-param',
							),
							// Select link option - to box or with read more text.
							array(
								'type'        => 'dropdown',
								'class'       => '',
								'heading'     => __( 'Apply link to:', 'ultimate_vc' ),
								'param_name'  => 'read_more',
								'value'       => array(
									__( 'No Link', 'ultimate_vc' ) => 'none',
									__( 'Complete Box', 'ultimate_vc' ) => 'box',
									__( 'Box Title', 'ultimate_vc' ) => 'title',
									__( 'Display Read More', 'ultimate_vc' ) => 'more',
								),
								'description' => __( 'Select whether to use color for icon or not.', 'ultimate_vc' ),
							),
							// Add link to existing content or to another resource.
							array(
								'type'        => 'vc_link',
								'class'       => '',
								'heading'     => __( 'Add Link', 'ultimate_vc' ),
								'param_name'  => 'link',
								'value'       => '',
								'description' => __( 'Add a custom link or select existing page. You can remove existing link as well.', 'ultimate_vc' ),
								'dependency'  => array(
									'element' => 'read_more',
									'value'   => array( 'box', 'title', 'more' ),
								),
							),
							// Link to traditional read more.
							array(
								'type'        => 'textfield',
								'class'       => '',
								'heading'     => __( 'Read More Text', 'ultimate_vc' ),
								'param_name'  => 'read_text',
								'value'       => 'Read More',
								'description' => __( 'Customize the read more text.', 'ultimate_vc' ),
								'dependency'  => array(
									'element' => 'read_more',
									'value'   => array( 'more' ),
								),
							),
							// Hover Effect type.
							array(
								'type'        => 'dropdown',
								'class'       => '',
								'heading'     => __( 'Select Hover Effect type', 'ultimate_vc' ),
								'param_name'  => 'hover_effect',
								'value'       => array(
									__( 'No Effect', 'ultimate_vc' ) => 'style_1',
									__( 'Icon Zoom', 'ultimate_vc' ) => 'style_2',
									__( 'Icon Bounce Up', 'ultimate_vc' ) => 'style_3',
								),
								'description' => __( 'Select the type of effct you want on hover', 'smile' ),
							),
							// Position the icon box.
							array(
								'type'        => 'dropdown',
								'class'       => '',
								'heading'     => __( 'Box Style', 'ultimate_vc' ),
								'param_name'  => 'pos',
								'value'       => array(
									__( 'Icon at Left with heading', 'ultimate_vc' ) => 'default',
									__( 'Icon at Right with heading', 'ultimate_vc' ) => 'heading-right',
									__( 'Icon at Left', 'ultimate_vc' ) => 'left',
									__( 'Icon at Right', 'ultimate_vc' ) => 'right',
									__( 'Icon at Top', 'ultimate_vc' ) => 'top',
									__( 'Boxed Style', 'ultimate_vc' ) => 'square_box',
								),
								'description' => __( 'Select icon position. Icon box style will be changed according to the icon position.', 'ultimate_vc' ),
							),
							array(
								'type'        => 'number',
								'class'       => '',
								'heading'     => __( 'Box Min Height', 'ultimate_vc' ),
								'param_name'  => 'box_min_height',
								'value'       => '',
								'suffix'      => 'px',
								'dependency'  => array(
									'element' => 'pos',
									'value'   => array( 'square_box' ),
								),
								'description' => __( 'Select Min Height for Box.', 'ultimate_vc' ),
							),
							array(
								'type'        => 'dropdown',
								'class'       => '',
								'heading'     => __( 'Box Border Style', 'ultimate_vc' ),
								'param_name'  => 'box_border_style',
								'value'       => array(
									__( 'None', 'ultimate_vc' ) => '',
									__( 'Solid', 'ultimate_vc' ) => 'solid',
									__( 'Dashed', 'ultimate_vc' ) => 'dashed',
									__( 'Dotted', 'ultimate_vc' ) => 'dotted',
									__( 'Double', 'ultimate_vc' ) => 'double',
									__( 'Inset', 'ultimate_vc' ) => 'inset',
									__( 'Outset', 'ultimate_vc' ) => 'outset',
								),
								'dependency'  => array(
									'element' => 'pos',
									'value'   => array( 'square_box' ),
								),
								'description' => __( 'Select Border Style for box border.', 'ultimate_vc' ),
							),
							array(
								'type'        => 'number',
								'class'       => '',
								'heading'     => __( 'Box Border Width', 'ultimate_vc' ),
								'param_name'  => 'box_border_width',
								'value'       => '',
								'suffix'      => '',
								'dependency'  => array(
									'element' => 'pos',
									'value'   => array( 'square_box' ),
								),
								'description' => __( 'Select Width for Box Border.', 'ultimate_vc' ),
							),
							array(
								'type'        => 'colorpicker',
								'class'       => '',
								'heading'     => __( 'Box Border Color', 'ultimate_vc' ),
								'param_name'  => 'box_border_color',
								'value'       => '',
								'dependency'  => array(
									'element' => 'pos',
									'value'   => array( 'square_box' ),
								),
								'description' => __( 'Select Border color for border box.', 'ultimate_vc' ),
							),
							array(
								'type'        => 'colorpicker',
								'class'       => '',
								'heading'     => __( 'Box Background Color', 'ultimate_vc' ),
								'param_name'  => 'box_bg_color',
								'value'       => '',
								'dependency'  => array(
									'element' => 'pos',
									'value'   => array( 'square_box' ),
								),
								'description' => __( 'Select Box background color.', 'ultimate_vc' ),
							),
							// Customize everything.
							array(
								'type'        => 'textfield',
								'class'       => '',
								'heading'     => __( 'Extra Class', 'ultimate_vc' ),
								'param_name'  => 'el_class',
								'value'       => '',
								'description' => __( 'Add extra class name that will be applied to the icon box, and you can use this class for your customizations.', 'ultimate_vc' ),
							),
							array(
								'type'             => 'ult_param_heading',
								'param_name'       => 'title_text_typography',
								'heading'          => __( 'Title settings', 'ultimate_vc' ),
								'value'            => '',
								'group'            => 'Typography',
								'edit_field_class' => 'ult-param-heading-wrapper no-top-margin vc_column vc_col-sm-12',
							),
							array(
								'type'       => 'ultimate_google_fonts',
								'heading'    => __( 'Font Family', 'ultimate_vc' ),
								'param_name' => 'title_font',
								'value'      => '',
								'group'      => 'Typography',
							),
							array(
								'type'       => 'ultimate_google_fonts_style',
								'heading'    => __( 'Font Style', 'ultimate_vc' ),
								'param_name' => 'title_font_style',
								'value'      => '',
								'group'      => 'Typography',
							),
							array(
								'type'       => 'ultimate_responsive',
								'class'      => '',
								'heading'    => __( 'Font size', 'ultimate_vc' ),
								'param_name' => 'title_font_size',
								'unit'       => 'px',
								'media'      => array(
									'Desktop'          => '',
									'Tablet'           => '',
									'Tablet Portrait'  => '',
									'Mobile Landscape' => '',
									'Mobile'           => '',
								),
								'group'      => 'Typography',
							),
							array(
								'type'       => 'ultimate_responsive',
								'class'      => '',
								'heading'    => __( 'Line Height', 'ultimate_vc' ),
								'param_name' => 'title_font_line_height',
								'unit'       => 'px',
								'media'      => array(
									'Desktop'          => '',
									'Tablet'           => '',
									'Tablet Portrait'  => '',
									'Mobile Landscape' => '',
									'Mobile'           => '',
								),
								'group'      => 'Typography',
							),
							array(
								'type'       => 'colorpicker',
								'param_name' => 'title_font_color',
								'heading'    => __( 'Color', 'ultimate_vc' ),
								'group'      => 'Typography',
							),
							array(
								'type'             => 'ult_param_heading',
								'param_name'       => 'desc_text_typography',
								'heading'          => __( 'Description settings', 'ultimate_vc' ),
								'value'            => '',
								'group'            => 'Typography',
								'edit_field_class' => 'ult-param-heading-wrapper vc_column vc_col-sm-12',
							),
							array(
								'type'       => 'ultimate_google_fonts',
								'heading'    => __( 'Font Family', 'ultimate_vc' ),
								'param_name' => 'desc_font',
								'value'      => '',
								'group'      => 'Typography',
							),
							array(
								'type'       => 'ultimate_google_fonts_style',
								'heading'    => __( 'Font Style', 'ultimate_vc' ),
								'param_name' => 'desc_font_style',
								'value'      => '',
								'group'      => 'Typography',
							),
							array(
								'type'       => 'ultimate_responsive',
								'class'      => '',
								'heading'    => __( 'Font size', 'ultimate_vc' ),
								'param_name' => 'desc_font_size',
								'unit'       => 'px',
								'media'      => array(
									'Desktop'          => '',
									'Tablet'           => '',
									'Tablet Portrait'  => '',
									'Mobile Landscape' => '',
									'Mobile'           => '',
								),
								'group'      => 'Typography',
							),
							array(
								'type'       => 'ultimate_responsive',
								'class'      => '',
								'heading'    => __( 'Line Height', 'ultimate_vc' ),
								'param_name' => 'desc_font_line_height',
								'unit'       => 'px',
								'media'      => array(
									'Desktop'          => '',
									'Tablet'           => '',
									'Tablet Portrait'  => '',
									'Mobile Landscape' => '',
									'Mobile'           => '',
								),
								'group'      => 'Typography',
							),
							array(
								'type'       => 'colorpicker',
								'param_name' => 'desc_font_color',
								'heading'    => __( 'Color', 'ultimate_vc' ),
								'group'      => 'Typography',
							),
							array(
								'type'             => 'ult_param_heading',
								'text'             => "<span style='display: block;'><a href='http://bsf.io/kqzzi' target='_blank' rel='noopener'>" . __( 'Watch Video Tutorial', 'ultimate_vc' ) . " &nbsp; <span class='dashicons dashicons-video-alt3' style='font-size:30px;vertical-align: middle;color: #e52d27;'></span></a></span>",
								'param_name'       => 'notification',
								'edit_field_class' => 'ult-param-important-wrapper ult-dashicon ult-align-right ult-bold-font ult-blue-font vc_column vc_col-sm-12',
							),
							array(
								'type'             => 'css_editor',
								'heading'          => __( 'Css', 'ultimate_vc' ),
								'param_name'       => 'css_info_box',
								'group'            => __( 'Design ', 'ultimate_vc' ),
								'edit_field_class' => 'vc_col-sm-12 vc_column no-vc-background no-vc-border creative_link_css_editor',
							),
						), // end params array.
					) // end vc_map array.
				); // end vc_map.
			} // end function check 'vc_map'.
		}//end icon_box_init()

		/** Icon Box Scripts.
		 */
		public function icon_box_scripts() {
			Ultimate_VC_Addons::ultimate_register_style( 'ultimate-vc-addons-info-box-style', 'info-box' );

			Ultimate_VC_Addons::ultimate_register_script( 'ultimate-vc-addons-info_box_js', 'info-box' );
		}
	}//end class
}
if ( class_exists( 'Ultimate_VC_Addons_Icons_Box' ) ) {
	$aio_icons_box = new Ultimate_VC_Addons_Icons_Box();
}
