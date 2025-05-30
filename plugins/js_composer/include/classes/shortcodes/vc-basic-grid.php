<?php
/**
 * Class that handles specific [vc_basic_grid] shortcode.
 *
 * @see js_composer/include/templates/shortcodes/vc_basic_grid.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once vc_path_dir( 'SHORTCODES_DIR', 'paginator/class-vc-pageable.php' );
require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-btn.php' );

/**
 * Class WPBakeryShortCode_Vc_Basic_Grid
 */
class WPBakeryShortCode_Vc_Basic_Grid extends WPBakeryShortCode_Vc_Pageable {
	/**
	 * Pagination type for the grid.
	 *
	 * @var string
	 */
	public $pagable_type = 'grid';

	/**
	 * Array of grid items.
	 *
	 * @var array
	 */
	public $items = [];
	/**
	 * Excluded ids list.
	 *
	 * @var array
	 */
	public static $excluded_ids = [];

	/**
	 * Template for the grid element.
	 *
	 * @var string
	 */
	protected $element_template = '';

	/**
	 * Default maximum number of items.
	 *
	 * @var int
	 */
	protected static $default_max_items = 1000;

	/**
	 * Post ID for the grid.
	 *
	 * @var int|false
	 */
	public $post_id = false;
	/**
	 * Grid item object.
	 *
	 * @var Vc_Grid_Item $grid_item
	 */
	public $grid_item = false;

	/**
	 * Grid item object.
	 *
	 * @var Vc_Grid_Item|false
	 */
	protected $filter_terms;

	/**
	 * Default attributes for the shortcode.
	 *
	 * @var array
	 */
	public $attributes_defaults = [
		'initial_loading_animation' => 'zoomIn',
		'full_width' => '',
		'layout' => '',
		'element_width' => '4',
		'items_per_page' => '5',
		'gap' => '',
		'style' => 'all',
		'show_filter' => '',
		'filter_default_title' => 'all',
		'exclude_filter' => '',
		'filter_style' => '',
		'filter_size' => 'md',
		'filter_align' => '',
		'filter_color' => '',
		'arrows_design' => '',
		'arrows_position' => '',
		'arrows_color' => '',
		'paging_design' => '',
		'paging_color' => '',
		'paging_animation_in' => '',
		'paging_animation_out' => '',
		'loop' => '',
		'autoplay' => '',
		'post_type' => 'post',
		'filter_source' => 'category',
		'orderby' => '',
		'order' => 'DESC',
		// @codingStandardsIgnoreLine
		'meta_key' => '',
		'max_items' => '10',
		'offset' => '0',
		'taxonomies' => '',
		'custom_query' => '',
		'data_type' => 'query',
		'include' => '',
		'exclude' => '',
		'item' => 'none',
		'grid_id' => '',
		// disabled, needed for-BC.
		'button_style' => '',
		'button_color' => '',
		'button_size' => '',
		// New button3.
		'btn_title' => '',
		'btn_style' => 'modern',
		'btn_el_id' => '',
		'btn_custom_background' => '#ededed',
		'btn_custom_text' => '#666',
		'btn_outline_custom_color' => '#666',
		'btn_outline_custom_hover_background' => '#666',
		'btn_outline_custom_hover_text' => '#fff',
		'btn_shape' => 'rounded',
		'btn_color' => 'blue',
		'btn_size' => 'md',
		'btn_align' => 'inline',
		'btn_button_block' => '',
		'btn_add_icon' => '',
		'btn_i_align' => 'left',
		'btn_i_type' => 'fontawesome',
		'btn_i_icon_fontawesome' => 'fa fa-adjust',
		'btn_i_icon_openiconic' => 'vc-oi vc-oi-dial',
		'btn_i_icon_typicons' => 'typcn typcn-adjust-brightness',
		'btn_i_icon_entypo' => 'entypo-icon entypo-icon-note',
		'btn_i_icon_linecons' => 'vc_li vc_li-heart',
		'btn_i_icon_pixelicons' => 'vc_pixel_icon vc_pixel_icon-alert',
		'btn_custom_onclick' => '',
		'btn_custom_onclick_code' => '',
		// fix template.
		'page_id' => '',
	];

	/**
	 * Settings for the grid.
	 *
	 * @var array
	 */
	protected $grid_settings = [];

	/**
	 * Unique name for the grid ID.
	 *
	 * @var string
	 */
	protected $grid_id_unique_name = 'vc_gid'; // if you change this also change in hook-vc-grid.php.

	/**
	 * Query object.
	 *
	 * @var WP_Query
	 */
	protected $query;

	/**
	 * List of grid item design types that has lightbox functionality.
	 *
	 * @var array
	 */
	public $lightbox_list = [
		'mediaGrid_Default',
		'mediaGrid_SimpleOverlay',
		'mediaGrid_FadeInWithIcon',
		'mediaGrid_BorderedScaleWithTitle',
		'mediaGrid_ScaleWithRotation',
		'mediaGrid_SlideOutCaption',
		'mediaGrid_HorizontalFlipWithFade',
		'mediaGrid_BlurWithContentBlock',
		'mediaGrid_SlideInTitle',
		'mediaGrid_ScaleInWithIcon',
		'masonryMedia_Default',
		'masonryMedia_BorderedScale',
		'masonryMedia_SolidBlurOut',
		'masonryMedia_ScaleWithRotationLight',
		'masonryMedia_SlideWithTitleAndCaption',
		'masonryMedia_ScaleWithContentBlock',
		'masonryMedia_SimpleOverlay',
		'masonryMedia_SlideTop',
		'masonryMedia_SimpleBlurWithScale',
	];

	/**
	 * WPBakeryShortCode_Vc_Basic_Grid constructor.
	 *
	 * @param array $settings
	 */
	public function __construct( $settings ) {
		parent::__construct( $settings );
		$this->attributes_defaults['btn_title'] = esc_html__( 'Load more', 'js_composer' );
		$this->shortcodeScripts();
	}

	/**
	 * Register scripts.
	 */
	public function shortcodeScripts() {
		parent::shortcodeScripts();

		wp_register_script( 'vc_grid-js-imagesloaded', vc_asset_url( 'lib/vendor/node_modules/imagesloaded/imagesloaded.pkgd.min.js' ), [ 'jquery-core' ], WPB_VC_VERSION, true );
		wp_register_script( 'vc_grid', vc_asset_url( 'js/dist/vc_grid.min.js' ), [
			'jquery-core',
			'underscore',
			'vc_waypoints',
		], WPB_VC_VERSION, true );
	}

	/**
	 * Enqueue scripts.
	 */
	public function enqueueScripts() {
		if ( $this->isGridPageable() ) {
			parent::enqueueScripts();
		}
		if ( $this->isGridLightbox() ) {
			wp_enqueue_script( 'lightbox2' );
			wp_enqueue_style( 'lightbox2' );
		}
		wp_enqueue_style( 'vc_animate-css' );
		wp_enqueue_script( 'vc_grid-js-imagesloaded' );
		wp_enqueue_script( 'vc_grid' );
	}

	/**
	 * Check is grid need pagination functionality.
	 *
	 * @return bool
	 */
	public function isGridPageable() {
		$is_pageable = true;

		// we don't need pagination if we should show all items.
		if ( isset( $this->atts['style'] ) && 'all' === $this->atts['style'] ) {
			$is_pageable = false;
		}

		return $is_pageable;
	}

	/**
	 * Check is grid has lightbox functionality.
	 *
	 * @return bool
	 */
	public function isGridLightbox() {
		if ( in_array( $this->atts['item'], $this->lightbox_list ) ) {
			return true;
		}
		// if we have grid builder item in grid element template with lightbox functionality.
		$grid_builder_id = intval( $this->atts['item'] );
		if ( $grid_builder_id ) {
			$content = get_post_field( 'post_content', $grid_builder_id );
			if ( strpos( $content, 'image_lightbox' ) !== false ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Add id to exclude list.
	 *
	 * @param int $id
	 */
	public static function addExcludedId( $id ) {
		self::$excluded_ids[] = $id;
	}

	/**
	 * Get id exclude list.
	 *
	 * @return array
	 */
	public static function excludedIds() {
		return self::$excluded_ids;
	}

	/**
	 * Get id.
	 *
	 * @param array $atts
	 * @param string $content
	 * @return false|mixed|string|void
	 */
	public function getId( $atts, $content ) {
		if ( vc_is_page_editable() || is_preview() ) {
			/*
			 * We are in Frontend editor
			 * We need to send RAW shortcode data, so hash is just json_encode of atts and content
			 */
			return rawurlencode( wp_json_encode( [
				'tag' => $this->shortcode,
				'atts' => $atts,
				'content' => $content,
			] ) );
		}

		$id_pattern = '/' . $this->grid_id_unique_name . '\:([\w\-_]+)/';

		$id_value = isset( $atts['grid_id'] ) ? $atts['grid_id'] : '';

		preg_match( $id_pattern, $id_value, $id_matches );
		$id_to_save = wp_json_encode( [ 'failed_to_get_id' => esc_attr( $id_value ) ] );

		if ( ! empty( $id_matches ) ) {
			$id_to_save = $id_matches[1];
		}

		return $id_to_save;
	}

	/**
	 * Find a shortcode by ID in a specific post.
	 *
	 * @param int $page_id
	 * @param string $grid_id
	 * @return array|mixed|object|void
	 */
	public function findPostShortcodeById( $page_id, $grid_id ) {
		if ( $this->currentUserCanManage( $page_id ) && preg_match( '/\"tag\"\:/', urldecode( $grid_id ) ) ) {
			return json_decode( urldecode( $grid_id ), true ); // if frontend, no hash exists - just RAW data.
		}
		$post_meta = get_post_meta( (int) $page_id, '_vc_post_settings' );
		$shortcode = false;
		if ( is_array( $post_meta ) ) {
			foreach ( $post_meta as $meta ) {
				if ( isset( $meta['vc_grid_id'] ) && ! empty( $meta['vc_grid_id']['shortcodes'] ) && isset( $meta['vc_grid_id']['shortcodes'][ $grid_id ] ) ) {
					$shortcode = $meta['vc_grid_id']['shortcodes'][ $grid_id ];
					break;
				}
			}
		}

		return apply_filters( 'vc_basic_grid_find_post_shortcode', $shortcode, $page_id, $grid_id );
	}

	/**
	 * Render items.
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function renderItems() {
		$output = '';
		$items = '';
		$this->buildGridSettings();
		$atts = $this->atts;
		$settings = $this->grid_settings;
		$filter_terms = $this->filter_terms;
		$is_end = isset( $this->is_end ) && $this->is_end;
		$css_classes = 'vc_grid vc_row' . esc_attr( $atts['gap'] > 0 ? ' vc_grid-gutter-' . (int) $atts['gap'] . 'px' : '' );
		$current_scope = WPBMap::getScope();
		if ( is_array( $this->items ) && ! empty( $this->items ) ) {
			// Adding before vc_map.
			WPBMap::setScope( Vc_Grid_Item_Editor::postType() );
			require_once vc_path_dir( 'PARAMS_DIR', 'vc_grid_item/class-vc-grid-item.php' );
			$this->grid_item = new Vc_Grid_Item();
			$this->grid_item->setGridAttributes( $atts );
			$this->grid_item->setIsEnd( $is_end );
			$this->grid_item->setTemplateById( $atts['item'] );
			$output .= $this->grid_item->addShortcodesCustomCss();
			ob_start();
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				wp_print_styles();
			}
			$output .= ob_get_clean();
			$attributes = [
				'filter_terms' => $filter_terms,
				'atts' => $atts,
				'grid_item',
				$this->grid_item,
			];
			$output .= apply_filters( 'vc_basic_grid_template_filter', vc_get_template( 'shortcodes/vc_basic_grid_filter.php', $attributes ), $attributes );
			global $post;
			$backup = $post;
			foreach ( $this->items as $post_item ) {
				$this->query->setup_postdata( $post_item );
				// @codingStandardsIgnoreLine
				$post = $post_item;
				$items .= $this->grid_item->renderItem( $post_item );
			}
			wp_reset_postdata();
			$post = $backup;
		} else {
			return '';
		}
		$items = apply_filters( $this->shortcode . '_items_list', $items );
		$output .= $this->renderPagination( $atts['style'], $settings, $items, $css_classes );
		WPBMap::setScope( $current_scope );

		return $output;
	}

	/**
	 * Set content limits.
	 */
	public function setContentLimits() {
		$atts = $this->atts;
		if ( 'ids' === $this->atts['post_type'] ) {
			$this->atts['max_items'] = 0;
			$this->atts['offset'] = 0;
			$this->atts['items_per_page'] = apply_filters( 'vc_basic_grid_max_items', self::$default_max_items );
		} else {
			$offset = isset( $atts['offset'] ) ? (int) $atts['offset'] : $this->attributes_defaults['offset'];
			$this->atts['offset'] = $offset;
			$this->atts['max_items'] = isset( $atts['max_items'] ) ? (int) $atts['max_items'] : (int) $this->attributes_defaults['max_items'];
			$this->atts['items_per_page'] = ! isset( $atts['items_per_page'] ) ? (int) $this->attributes_defaults['items_per_page'] : (int) $atts['items_per_page'];
			if ( $this->atts['max_items'] < 1 ) {
				$this->atts['max_items'] = apply_filters( 'vc_basic_grid_max_items', self::$default_max_items );
			}
		}
		$this->setPagingAll( $this->atts['max_items'] );
	}

	/**
	 * Set paging all attributes.
	 *
	 * @param int $max_items
	 */
	protected function setPagingAll( $max_items ) {
		$atts = $this->atts;
		$this->atts['query_items_per_page'] = $max_items > 0 ? $max_items : apply_filters( 'vc_basic_grid_items_per_page_all_max_items', self::$default_max_items );
		$this->atts['items_per_page'] = $this->atts['query_items_per_page'];
		$this->atts['query_offset'] = isset( $atts['offset'] ) ? (int) $atts['offset'] : $this->attributes_defaults['offset'];
	}

	/**
	 * Render ajax.
	 *
	 * @param array $vc_request_param
	 * @return false|mixed|string|void
	 * @throws \Exception
	 */
	public function renderAjax( $vc_request_param ) {
		$this->items = []; // clear this items array (if used more than once).
		$id = isset( $vc_request_param['shortcode_id'] ) ? $vc_request_param['shortcode_id'] : false;
		$shortcode = false;
		if ( ! isset( $vc_request_param['page_id'] ) ) {
			return wp_json_encode( [ 'status' => 'Nothing found' ] );
		}
		if ( $id ) {
			$shortcode = $this->findPostShortcodeById( $vc_request_param['page_id'], $id );
		}
		if ( ! is_array( $shortcode ) ) {
			return wp_json_encode( [ 'status' => 'Nothing found' ] );
		}
		wpbakery()->registerAdminCss();
		wpbakery()->registerAdminJavascript();
		// Set post id.
		$this->post_id = (int) $vc_request_param['page_id'];

		$shortcode_atts = $shortcode['atts'];
		$this->shortcode_content = $shortcode['content'];
		$this->buildAtts( $shortcode_atts, $shortcode['content'] );

		$this->buildItems();

		return $this->renderItems();
	}

	/**
	 * Get post id.
	 *
	 * @return bool|false|int
	 */
	public function postID() {
		if ( ! $this->post_id ) {
			$this->post_id = get_the_ID();
		}

		return $this->post_id;
	}

	/**
	 * Build main element attributes.
	 *
	 * @param array $atts
	 * @param string $content
	 * @throws \Exception
	 */
	public function buildAtts( $atts, $content ) {
		$this->post_id = false;
		$this->grid_settings = [];
		$this->filter_terms = null;
		$this->items = [];
		$arr_keys = array_keys( $atts );
		$count = count( $atts );
		for ( $i = 0; $i < $count; $i++ ) {
			$atts[ $arr_keys[ $i ] ] = html_entity_decode( $atts[ $arr_keys[ $i ] ], ENT_QUOTES, 'utf-8' );
		}
		if ( isset( $atts['grid_id'] ) && ! empty( $atts['grid_id'] ) ) {
			$id_to_save = $this->getId( $atts, $content );
		}

		$atts = $this->convertButton2ToButton3( $atts );
		$atts = shortcode_atts( $this->attributes_defaults, vc_map_get_attributes( $this->getShortcode(), $atts ) );
		$this->atts = $atts;
		if ( isset( $id_to_save ) ) {
			$this->atts['shortcode_id'] = $id_to_save;
		}

		$this->atts['page_id'] = $this->postID();

		$this->element_template = $content;
		// @since 4.4.3
		if ( 'custom' === $this->attr( 'post_type' ) ) {
			$this->atts['style'] = 'all';
		}
	}

	/**
	 * Getter attribute.
	 *
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	public function attr( $key ) {
		return isset( $this->atts[ $key ] ) ? $this->atts[ $key ] : null;
	}

	/**
	 * Build grid settings.
	 */
	public function buildGridSettings() {
		$this->grid_settings = [
			'page_id' => $this->atts['page_id'],
			// used in basic grid for initialization.
			'style' => $this->atts['style'],
			'action' => 'vc_get_vc_grid_data',
		];
		// used in ajax request for items.
		if ( isset( $this->atts['shortcode_id'] ) && ! empty( $this->atts['shortcode_id'] ) ) {
			$this->grid_settings['shortcode_id'] = $this->atts['shortcode_id'];
		} elseif ( isset( $this->atts['shortcode_hash'] ) && ! empty( $this->atts['shortcode_hash'] ) ) {
			// @deprecated since 4.4.3.
			$this->grid_settings['shortcode_hash'] = $this->atts['shortcode_hash'];
		}
		if ( 'load-more' === $this->atts['style'] ) {
			$this->grid_settings = array_merge( $this->grid_settings, [
				// used in display style load more button, lazy, pagination.
				'items_per_page' => $this->atts['items_per_page'],
				'btn_data' => vc_map_integrate_parse_atts( $this->shortcode, 'vc_btn', $this->atts, 'btn_' ),
			] );
		} elseif ( 'lazy' === $this->atts['style'] ) {
			$this->grid_settings = array_merge( $this->grid_settings, [
				'items_per_page' => $this->atts['items_per_page'],
			] );
		} elseif ( 'pagination' === $this->atts['style'] ) {
			$this->grid_settings = array_merge( $this->grid_settings, [
				'items_per_page' => $this->atts['items_per_page'],
				// used in pagination style.
				'auto_play' => $this->atts['autoplay'] > 0 ? true : false,
				'gap' => (int) $this->atts['gap'],
				// not used yet, but can be used in isotope.
				'speed' => (int) $this->atts['autoplay'] * 1000,
				'loop' => $this->atts['loop'],
				'animation_in' => $this->atts['paging_animation_in'],
				'animation_out' => $this->atts['paging_animation_out'],
				'arrows_design' => $this->atts['arrows_design'],
				'arrows_color' => $this->atts['arrows_color'],
				'arrows_position' => $this->atts['arrows_position'],
				'paging_design' => $this->atts['paging_design'],
				'paging_color' => $this->atts['paging_color'],
			] );
		}
		$this->grid_settings['tag'] = $this->shortcode;
	}

	/**
	 * Build query.
	 *
	 * @param array $atts
	 * @return array
	 */
	public function buildQuery( $atts ) {
		// Set include & exclude.
		if ( 'ids' !== $atts['post_type'] && ! empty( $atts['exclude'] ) ) {
			$atts['exclude'] .= ',' . implode( ',', $this->excludedIds() );
		} else {
			$atts['exclude'] = implode( ',', $this->excludedIds() );
		}
		if ( 'ids' !== $atts['post_type'] ) {
			$settings = [
				'posts_per_page' => $atts['query_items_per_page'],
				'offset' => $atts['query_offset'],
				'orderby' => $atts['orderby'],
				'order' => $atts['order'],
				'meta_key' => in_array( $atts['orderby'], [
					'meta_value',
					'meta_value_num',
				], true ) ? $atts['meta_key'] : '',
				'post_type' => $atts['post_type'],
				'exclude' => $atts['exclude'],
			];
			if ( ! empty( $atts['taxonomies'] ) ) {
				$vc_taxonomies_types = get_taxonomies( [ 'public' => true ] );
				// phpcs:ignore
				$terms = get_terms( array_keys( $vc_taxonomies_types ), array(
					'hide_empty' => false,
					'include' => $atts['taxonomies'],
				) );
				$tax_queries = []; // List of taxonomies.
				foreach ( $terms as $term ) {
					if ( ! isset( $tax_queries[ $term->taxonomy ] ) ) {
						$tax_queries[ $term->taxonomy ] = [
							'taxonomy' => $term->taxonomy,
							'field' => 'id',
							'terms' => [ $term->term_id ],
							'relation' => 'IN',
						];
					} else {
						$tax_queries[ $term->taxonomy ]['terms'][] = $term->term_id;
					}
				}
				$settings['tax_query'] = array_values( $tax_queries );
				$settings['tax_query']['relation'] = 'OR';
			}
		} else {
			if ( empty( $atts['include'] ) ) {
				$atts['include'] = - 1;
			} elseif ( ! empty( $atts['exclude'] ) ) {
				$include = array_map( 'trim', explode( ',', $atts['include'] ) );
				$exclude = array_map( 'trim', explode( ',', $atts['exclude'] ) );
				$diff = array_diff( $include, $exclude );
				$atts['include'] = implode( ', ', $diff );
			}
			$settings = [
				'include' => $atts['include'],
				'posts_per_page' => $atts['query_items_per_page'],
				'offset' => $atts['query_offset'],
				'post_type' => 'any',
				'orderby' => 'post__in',
			];
			$this->atts['items_per_page'] = - 1;
		}

		return $settings;
	}

	/**
	 * Build grid items.
	 */
	public function buildItems() {
		$this->filter_terms = $this->items = [];

		$this->query = new WP_Query();

		$this->setContentLimits();

		$this->addExcludedId( $this->postID() );
		if ( 'custom' === $this->atts['post_type'] && ! empty( $this->atts['custom_query'] ) ) {
			$query = html_entity_decode( vc_value_from_safe( $this->atts['custom_query'] ), ENT_QUOTES, 'utf-8' );
			$query = apply_filters( 'vc_basic_grid_filter_query_filters', $query, $this->atts, $this->shortcode );
			$post_data = $this->query->query( $query );
			$this->atts['items_per_page'] = - 1;
		} elseif ( false !== $this->atts['query_items_per_page'] ) {
			$settings = $this->filterQuerySettings( $this->buildQuery( $this->atts ) );
			$post_data = $this->query->query( $settings );
		} else {
			return;
		}
		if ( $this->atts['items_per_page'] > 0 && count( $post_data ) > $this->atts['items_per_page'] ) {
			$post_data = array_slice( $post_data, 0, $this->atts['items_per_page'] );
		}
		foreach ( $post_data as $post ) {
			$post->filter_terms = wp_get_object_terms( $post->ID, $this->atts['filter_source'], [ 'fields' => 'ids' ] );
			$this->filter_terms = wp_parse_args( $this->filter_terms, $post->filter_terms );
			$this->items[] = $post;
		}
	}

	/**
	 * Filter query settings.
	 *
	 * @param array $args
	 * @return array
	 */
	public function filterQuerySettings( $args ) {
		$defaults = [
			'numberposts' => 5,
			'offset' => 0,
			'category' => 0,
			'orderby' => 'date',
			'order' => 'DESC',
			'include' => [],
			'exclude' => [],
			'meta_key' => '',
			'meta_value' => '',
			'post_type' => 'post',
			'suppress_filters' => apply_filters( 'vc_basic_grid_filter_query_suppress_filters', true ),
			'public' => true,
		];

		$r = wp_parse_args( $args, $defaults );
		if ( empty( $r['post_status'] ) ) {
			$r['post_status'] = ( 'attachment' === $r['post_type'] ) ? 'inherit' : 'publish';
		}
		if ( ! empty( $r['numberposts'] ) && empty( $r['posts_per_page'] ) ) {
			$r['posts_per_page'] = $r['numberposts'];
		}
		if ( ! empty( $r['category'] ) ) {
			$r['cat'] = $r['category'];
		}
		if ( ! empty( $r['include'] ) ) {
			$incposts = wp_parse_id_list( $r['include'] );
			$r['posts_per_page'] = count( $incposts );  // only the number of posts included.
			$r['post__in'] = $incposts;
		} elseif ( ! empty( $r['exclude'] ) ) {
			$r['post__not_in'] = wp_parse_id_list( $r['exclude'] );
		}

		$r['ignore_sticky_posts'] = true;
		$r['no_found_rows'] = true;

		return $r;
	}

	/**
	 * Convert attributes button old to button new.
	 *
	 * @param array $atts
	 * @return mixed
	 */
	public static function convertButton2ToButton3( $atts ) {
		if ( ! empty( $atts['button_style'] ) || ! empty( $atts['button_size'] ) || ! empty( $atts['button_color'] ) ) {
			// we use old button 2 attributes.
			$style = isset( $atts['button_style'] ) ? $atts['button_style'] : 'rounded';
			$size = isset( $atts['button_size'] ) ? $atts['button_size'] : 'md';
			$color = isset( $atts['button_color'] ) ? $atts['button_color'] : 'blue';
			$old_data = [
				'style' => $style,
				'size' => $size,
				'color' => str_replace( '_', '-', $color ),
			];
			// remove attributes on save.
			$atts['button_style'] = '';
			$atts['button_size'] = '';
			$atts['button_color'] = '';
			$new_data = WPBakeryShortCode_Vc_Btn::convertAttributesToButton3( $old_data );
			foreach ( $new_data as $key => $value ) {
				$atts[ 'btn_' . $key ] = $value;
			}
		}

		return $atts;
	}
}
