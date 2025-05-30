<?php
/**
 * Woocomposer.
 *
 * @package Woocomposer.
 */

if ( ! defined( 'WOOCOMPOSER_VERSION' ) ) {
	define( 'WOOCOMPOSER_VERSION', '1.0' );
}
if ( ! class_exists( 'Ultimate_VC_Addons_WooComposer' ) ) {
	/**
	 * Woocomposer.
	 */
	class Ultimate_VC_Addons_WooComposer {
		/**
		 * Module_dir.
		 *
		 * @var $module_dir.
		 */
		public $module_dir = '';
		/**
		 * Initiator.
		 */
		public function __construct() {
			$this->module_dir = plugin_dir_path( __FILE__ ) . 'modules/';
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ), 1 );
			add_action( 'admin_init', array( $this, 'generate_shortcode_params' ) );
		} /* end constructor */
		/**
		 * Generate_shortcode_params.
		 */
		public function generate_shortcode_params() {
			/* Generate param type "woocomposer" */
			if ( defined( 'WPB_VC_VERSION' ) && version_compare( WPB_VC_VERSION, 4.8 ) >= 0 ) {
				if ( function_exists( 'vc_add_shortcode_param' ) ) {
					vc_add_shortcode_param( 'woocomposer', array( $this, 'woo_query_builder' ), plugins_url( 'admin/js/mapping.js', __FILE__ ) );
					vc_add_shortcode_param( 'product_search', array( $this, 'woo_product_search' ) );
					vc_add_shortcode_param( 'product_categories', array( $this, 'woo_product_categories' ) );
				}
			} else {
				if ( function_exists( 'add_shortcode_param' ) ) {
					add_shortcode_param( 'woocomposer', array( $this, 'woo_query_builder' ), plugins_url( 'admin/js/mapping.js', __FILE__ ) );
					add_shortcode_param( 'product_search', array( $this, 'woo_product_search' ) );
					add_shortcode_param( 'product_categories', array( $this, 'woo_product_categories' ) );
				}
			}
		}
		/**
		 * Woo_query_builder.
		 *
		 * @param array $settings Settings.
		 * @param array $value Value.
		 */
		public function woo_query_builder( $settings, $value ) {
			$output        = '';
			$asc           = '';
			$desc          = '';
			$post_count    = '';
			$shortcode_str = '';
			$cat_id        = '';
			$labels        = isset( $settings['labels'] ) ? $settings['labels'] : '';
			$pattern       = get_shortcode_regex();
			if ( '' !== $value ) {
				$shortcode = rawurldecode( base64_decode( wp_strip_all_tags( $value ) ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
				preg_match_all( '/' . $pattern . '/', $shortcode, $matches );
				$shortcode_str = str_replace( '"', '', str_replace( ' ', '&', trim( $matches[3][0] ) ) );
			}
			parse_str( $shortcode_str, $short_atts );
			if ( isset( $matches[2][0] ) ) :
				$display_type = $matches[2][0];
else :
	$display_type = '';
endif;
if ( ! isset( $columns ) ) :
	$columns = '4';
endif;
if ( ! isset( $per_page ) ) :
	$post_count = '12';
else :
	$post_count = $per_page;
endif;
if ( ! isset( $number ) ) :
	$per_page = '12';
else :
	$post_count = $number;
endif;
if ( ! isset( $order ) ) :
	$order = 'asc';
endif;
if ( ! isset( $orderby ) ) :
	$orderby = 'date';
endif;
if ( ! isset( $category ) ) :
	$category = '';
endif;
			$catobj = get_term_by( 'name', $category, 'product_cat' );
if ( is_object( $catobj ) ) {
	$cat_id = $catobj->term_id;
}
			$param_name  = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
			$type        = isset( $settings['type'] ) ? $settings['type'] : '';
			$class       = isset( $settings['class'] ) ? $settings['class'] : '';
			$module      = isset( $settings['module'] ) ? $settings['module'] : '';
			$displays    = array(
				__( 'Recent products', 'ultimate_vc' )    => 'recent_products',
				__( 'Featured Products', 'ultimate_vc' )  => 'featured_products',
				__( 'Top Rated Products', 'ultimate_vc' ) => 'top_rated_products',
				__( 'Product Category', 'ultimate_vc' )   => 'product_category',
				__( 'Product Categories', 'ultimate_vc' ) => 'product_categories',
				__( 'Products on Sale', 'ultimate_vc' )   => 'sale_products',
				__( 'Best Selling Products', 'ultimate_vc' ) => 'best_selling_products',
			);
			$orderby_arr = array(
				__( 'Date', 'ultimate_vc' )       => 'date',
				__( 'Title', 'ultimate_vc' )      => 'title',
				__( 'Product ID', 'ultimate_vc' ) => 'ID',
				__( 'Name', 'ultimate_vc' )       => 'name',
				__( 'Price', 'ultimate_vc' )      => 'price',
				__( 'Sales', 'ultimate_vc' )      => 'sales',
				__( 'Random', 'ultimate_vc' )     => 'rand',
			);
			$output     .= '<div class="display_type"><label for="display_type"><strong>' . __( $labels['products_from'], 'ultimate_vc' ) . '</strong></label>';
			$output     .= '<select id="display_type">';
			foreach ( $displays as $title => $display ) {
				if ( $display == $display_type ) {
					$output .= '<option value="' . $display . '" selected="selected">' . __( $title, 'ultimate_vc' ) . '</option>';
				} else {
					$output .= '<option value="' . $display . '">' . __( $title, 'ultimate_vc' ) . '</option>';
				}
			}
			$output .= '</select></div>';
			$output .= '<div class="per_page"><label for="per_page"><strong>' . __( $labels['per_page'], 'ultimate_vc' ) . '</strong></label>';
			$output .= '<input type="number" min="2" max="1000" id="per_page" value="' . $post_count . '"></div>';
			if ( 'grid' == $module ) {
				$output .= '<div class="columns"><label for="columns"><strong>' . __( $labels['columns'], 'ultimate_vc' ) . '</strong></label>';
				$output .= '<input type="number" min="2" max="4" id="columns" value="' . $columns . '"></div>';
			}
			$output .= '<div class="orderby"><label for="orderby"><strong>' . __( $labels['order_by'], 'ultimate_vc' ) . '</strong></label>';
			$output .= '<select id="orderby">';
			foreach ( $orderby_arr as $key => $val ) {
				if ( $orderby == $val ) {
					$output .= '<option value="' . $val . '" selected="selected">' . __( $key, 'ultimate_vc' ) . '</option>';
				} else {
					$output .= '<option value="' . $val . '">' . __( $key, 'ultimate_vc' ) . '</option>';
				}
			}
			$output .= '</select></div>';
			$output .= '<div class="order"><label for="order"><strong>' . __( $labels['order'], 'ultimate_vc' ) . '</strong></label>';
			$output .= '<select id="order">';
			if ( 'asc' == $order ) {
				$asc = 'selected="selected"';
			} else {
				$desc = 'selected="selected"';
			}
				$output .= '<option value="asc" ' . $asc . '>' . __( 'Ascending', 'ultimate_vc' ) . '</option>';
				$output .= '<option value="desc" ' . $desc . '>' . __( 'Descending', 'ultimate_vc' ) . '</option>';
			$output     .= '</select></div>';
			$output     .= '<div class="cat"><label for="cat"><strong>' . __( $labels['category'], 'ultimate_vc' ) . '</strong></label>';
			$output     .= wp_dropdown_categories(
				array(
					'taxonomy' => 'product_cat',
					'selected' => $cat_id,
					'echo'     => false,
				)
			) . '</div>';
			$output     .= '<!-- ' . $value . ' -->';
			$output     .= "<input type='hidden' name='" . $param_name . "' value='" . $value . "' class='wpb_vc_param_value " . $param_name . ' ' . $type . ' ' . $class . "' id='shortcode'>";
			return $output;
		} /* end woo_query_builder */
		/**
		 * Woo_product_search.
		 *
		 * @param array $settings Settings.
		 * @param array $value Value.
		 */
		public function woo_product_search( $settings, $value ) {
			$param_name = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
			$type       = isset( $settings['type'] ) ? $settings['type'] : '';
			$class      = isset( $settings['class'] ) ? $settings['class'] : '';

			$products_array = new WP_Query(
				array(
					'post_type'      => 'product',
					'posts_per_page' => -1,
					'post_status'    => 'publish',
				)
			);
			$output         = '';
			$output        .= '<select id="products" name="' . $param_name . '" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '">';
			while ( $products_array->have_posts() ) :
				$products_array->the_post();
				if ( get_the_ID() == $value ) {
					$selected = "selected='selected'";
				} else {
					$selected = '';
				}
				$output .= '<option ' . $selected . ' value="' . get_the_ID() . '">' . __( get_the_title(), 'ultimate_vc' ) . '</option>';
					endwhile;
			$output .= '</select>';

			return $output;
		} /* end woo_product_search */
		/**
		 * Woo_product_categories.
		 *
		 * @param array $settings Settings.
		 * @param array $value Value.
		 */
		public function woo_product_categories( $settings, $value ) {
			$param_name         = isset( $settings['param_name'] ) ? $settings['param_name'] : '';
			$type               = isset( $settings['type'] ) ? $settings['type'] : '';
			$class              = isset( $settings['class'] ) ? $settings['class'] : '';
			$product_categories = get_terms( 'product_cat', '' );
			$output             = '';
			$selected           = '';
			$ids                = '';
			if ( '' !== $value ) {
				$ids = explode( ',', $value );
				$ids = array_map( 'trim', $ids );
			} else {
				$ids = array();
			}
			$output .= '<select id="sel2_cat" multiple="multiple" style="min-width:200px;">';
			foreach ( $product_categories as $cat ) {
				if ( in_array( $cat->term_id, $ids ) ) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
				$output .= '<option ' . $selected . ' value="' . $cat->term_id . '">' . __( $cat->name, 'ultimate_vc' ) . '</option>';
			}
			$output .= '</select>';

			$output .= "<input type='hidden' name='" . $param_name . "' value='" . $value . "' class='wpb_vc_param_value " . $param_name . ' ' . $type . ' ' . $class . "' id='sel_cat'>";

			return $output;

		} /* end woo_product_categories*/
		/**
		 * Admin_scripts.
		 *
		 * @param string $hook Hook.
		 */
		public function admin_scripts( $hook ) {
			if ( 'post.php' == $hook || 'post-new.php' == $hook ) {
				if ( defined( 'WOOCOMMERCE_VERSION' ) && version_compare( '2.1.0', WOOCOMMERCE_VERSION, '<' ) ) {
					$bsf_dev_mode = bsf_get_option( 'dev_mode' );
					if ( 'enable' === $bsf_dev_mode ) {
						wp_register_style( 'ultimate-vc-addons-woocomposer-admin', plugins_url( 'admin/css/admin.css', __FILE__ ), null, WOOCOMPOSER_VERSION );
						wp_register_style( 'ultimate-vc-addons-woocomposer-select2-bootstrap', plugins_url( 'admin/css/select2-bootstrap.css', __FILE__ ), null, WOOCOMPOSER_VERSION );
						wp_register_style( 'ultimate-vc-addons-woocomposer-select2', plugins_url( 'admin/css/select2.css', __FILE__ ), null, WOOCOMPOSER_VERSION );
						wp_enqueue_style( 'ultimate-vc-addons-woocomposer-admin' );
					}
				}
			}
		} /* end admin scripts */
		/**
		 * Front_scripts.
		 *
		 * @param string $post Post.
		 */
		public function front_scripts( $post ) {
			if ( ! is_404() && ! is_search() && ! is_archive() ) {
				global $post; // PHPCS:Ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.VariableRedeclaration
				$count = 0;
				if ( null !== $post ) {
					$content    = $post->post_content;
					$shortcodes = array( 'woocomposer_product', 'woocomposer_list', 'woocomposer_grid', 'woocomposer_grid_cat', 'woocomposer_carousel_cat', 'woocomposer_carousel' );
					foreach ( $shortcodes as $shortcode ) {
						if ( has_shortcode( $content, $shortcode ) ) {
							$count++;
						}
					}

					wp_register_script( 'ultimate-vc-addons-woocomposer-unveil', plugins_url( 'assets/js/unveil.js', __FILE__ ), array( 'jquery' ), WOOCOMPOSER_VERSION, true );
					wp_register_script( 'ultimate-vc-addons-woocomposer-slick', plugins_url( 'assets/js/slick.js', __FILE__ ), array( 'jquery' ), WOOCOMPOSER_VERSION, true );
					wp_register_script( 'ultimate-vc-addons-woocomposer-js', plugins_url( 'assets/js/custom.js', __FILE__ ), array( 'jquery', 'ultimate-vc-addons-woocomposer-slick' ), WOOCOMPOSER_VERSION, true );

					wp_register_style( 'ultimate-vc-addons-woocomposer-front', plugins_url( 'assets/css/style.css', __FILE__ ), array(), WOOCOMPOSER_VERSION );
					wp_register_style( 'ultimate-vc-addons-woocomposer-front-wooicon', plugins_url( 'assets/css/wooicon.css', __FILE__ ), array(), WOOCOMPOSER_VERSION );
					wp_register_style( 'ultimate-vc-addons-woocomposer-front-slick', plugins_url( 'assets/css/slick.css', __FILE__ ), array(), WOOCOMPOSER_VERSION );
					wp_register_style( 'ultimate-vc-addons-woocomposer-animate', plugins_url( 'assets/css/animate.min.css', __FILE__ ), array(), WOOCOMPOSER_VERSION );

					wp_register_script( 'ultimate-vc-addons-woocomposer-script', plugins_url( 'assets/js/woocomposer.min.js', __FILE__ ), array( 'jquery' ), WOOCOMPOSER_VERSION, true );
					wp_register_style( 'ultimate-vc-addons-woocomposer-style', plugins_url( 'assets/css/woocomposer.min.css', __FILE__ ), array(), WOOCOMPOSER_VERSION );

					if ( defined( 'WOOCOMMERCE_VERSION' ) && version_compare( '2.1.0', WOOCOMMERCE_VERSION, '<' ) && 0 !== $count ) {
						$ultimate_css = get_option( 'ultimate_css' );
						$bsf_dev_mode = bsf_get_option( 'dev_mode' );
						if ( 'enable' == $ultimate_css && 'enable' !== $bsf_dev_mode ) {
							wp_enqueue_style( 'ultimate-vc-addons-woocomposer-style' );
						} else {
							wp_enqueue_style( 'ultimate-vc-addons-woocomposer-front' );
							wp_enqueue_style( 'ultimate-vc-addons-woocomposer-front-wooicon' );
							wp_enqueue_style( 'ultimate-vc-addons-woocomposer-front-slick' );
							wp_enqueue_style( 'ultimate-vc-addons-woocomposer-animate' );
						}

						$ultimate_js = get_option( 'ultimate_js' );
						wp_enqueue_script( 'jquery' );
						if ( 'enable' == $ultimate_js ) {
							wp_enqueue_script( 'ultimate-vc-addons-woocomposer-script' );
						} else {
							wp_enqueue_script( 'ultimate-vc-addons-woocomposer-unveil' );
							wp_enqueue_script( 'ultimate-vc-addons-woocomposer-slick' );
							wp_enqueue_script( 'ultimate-vc-addons-woocomposer-js' );
						}
					}
				}
			}
		}//end front_scripts()

	}
	new Ultimate_VC_Addons_WooComposer();
	add_action( 'admin_init', 'init_woocomposer' );
	/**
	 * Init_woocomposer.
	 */
	function init_woocomposer() {
		$required_vc = '3.7.2';
		if ( defined( 'WPB_VC_VERSION' ) ) {
			if ( version_compare( $required_vc, WPB_VC_VERSION, '>' ) ) {
				add_action( 'admin_notices', 'woocomposer_admin_notice_for_version' );
				add_action( 'network_admin_notices', 'woocomposer_admin_notice_for_version' );
			}
		} else {
			add_action( 'admin_notices', 'woocomposer_admin_notice_for_vc_activation' );
			add_action( 'network_admin_notices', 'woocomposer_admin_notice_for_vc_activation' );
		}

	}//end init_woocomposer()
	/**
	 * Woocomposer_admin_notice_for_version.
	 */
	function woocomposer_admin_notice_for_version() {
		$is_multisite     = is_multisite();
		$is_network_admin = is_network_admin();
		if ( ( $is_multisite && $is_network_admin ) || ! $is_multisite ) {
			echo '<div class="updated"><p>' . esc_html__( 'The', 'ultimate_vc' ) . ' <strong>WooComposer</strong> ' . esc_html__( 'plugin requires', 'ultimate_vc' ) . ' <strong>WPBakery Page Builder</strong> ' . esc_html__( 'version 3.7.2 or greater.', 'ultimate_vc' ) . '</p></div>';
		}
	}
	/**
	 * Woocomposer_admin_notice_for_vc_activation.
	 */
	function woocomposer_admin_notice_for_vc_activation() {
		$is_multisite     = is_multisite();
		$is_network_admin = is_network_admin();
		if ( ( $is_multisite && $is_network_admin ) || ! $is_multisite ) {
			echo '<div class="updated"><p>' . esc_html__( 'The', 'ultimate_vc' ) . ' <strong>WooComposer</strong> ' . esc_html__( 'plugin requires', 'ultimate_vc' ) . ' <strong>WPBakery Page Builder</strong> ' . esc_html__( 'Plugin installed and activated.' ) . '</p></div>';
		}
	}
}

// check the current post for the existence of a short code.
if ( ! function_exists( 'has_shortcode' ) ) {
	/**
	 * Has_shortcode.
	 *
	 * @param string $shortcode Shortcode.
	 */
	function has_shortcode( $shortcode = '' ) {

		$post_to_check = get_post( get_the_ID() );

		// false because we have to search through the post content first.
		$found = false;

		// if no short code was provided, return false.
		if ( ! $shortcode ) {
			return $found;
		}
		// check the post content for the short code.
		if ( false !== stripos( $post_to_check->post_content, '[' . $shortcode ) ) {
			// we have found the short code.
			$found = true;
		}

		// return our final results.
		return $found;
	}
}
