<?php

use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/the7-update-utility-functions.php';

function the7_update_550_fancy_titles_parallax() {
	global $wpdb;

	$parallax_speed_meta = $wpdb->get_results( "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = '_dt_fancy_header_parallax_speed'" );
	$fixed_bg_meta       = $wpdb->get_results( "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = '_dt_fancy_header_bg_fixed'", OBJECT_K );
	foreach ( $parallax_speed_meta as $_meta ) {
		if ( ! empty( $_meta->meta_value ) ) {
			// Setup parallax.
			add_post_meta( $_meta->post_id, '_dt_fancy_header_scroll_effect', 'parallax', true );
			add_post_meta( $_meta->post_id, '_dt_fancy_header_bg_parallax', $_meta->meta_value, true );
		} elseif ( array_key_exists( $_meta->post_id, $fixed_bg_meta ) && ! empty( $fixed_bg_meta[ $_meta->post_id ]->meta_value ) ) {
			// Setup fixed bg.
			add_post_meta( $_meta->post_id, '_dt_fancy_header_scroll_effect', 'fixed', true );
		}
		delete_post_meta( $_meta->post_id, '_dt_fancy_header_parallax_speed' );
		delete_post_meta( $_meta->post_id, '_dt_fancy_header_bg_fixed' );
	}
}

function the7_update_550_fancy_titles_font_size() {
	global $wpdb;

	$title_font_size_meta = $wpdb->get_results( "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = '_dt_fancy_header_title_size'" );

	foreach ( $title_font_size_meta as $font_size_meta ) {
		$old_font_size = $font_size_meta->meta_value;
		if ( in_array( $old_font_size, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ) {
			$font_size_option   = "fonts-{$old_font_size}_font_size";
			$line_height_option = "fonts-{$old_font_size}_line_height";
		} elseif ( in_array( $old_font_size, array( 'big', 'normal', 'small' ), true ) ) {
			$font_size_option   = "fonts-{$old_font_size}_size";
			$line_height_option = "fonts-{$old_font_size}_size_line_height";
		} else {
			continue;
		}

		$post_id   = $font_size_meta->post_id;
		$font_size = of_get_option( $font_size_option );
		if ( $font_size ) {
			add_post_meta( $post_id, '_dt_fancy_header_title_font_size', $font_size, true );
		}

		$line_height = of_get_option( $line_height_option );
		if ( $line_height ) {
			add_post_meta( $post_id, '_dt_fancy_header_title_line_height', $line_height, true );
		}

		delete_post_meta( $post_id, '_dt_fancy_header_title_size' );
	}
}

function the7_update_550_fancy_subtitles_font_size() {
	global $wpdb;

	$subtitle_font_size_meta = $wpdb->get_results( "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = '_dt_fancy_header_subtitle_size'" );

	foreach ( $subtitle_font_size_meta as $font_size_meta ) {
		$old_font_size = $font_size_meta->meta_value;
		if ( in_array( $old_font_size, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ) {
			$font_size_option   = "fonts-{$old_font_size}_font_size";
			$line_height_option = "fonts-{$old_font_size}_line_height";
		} elseif ( in_array( $old_font_size, array( 'big', 'normal', 'small' ), true ) ) {
			$font_size_option   = "fonts-{$old_font_size}_size";
			$line_height_option = "fonts-{$old_font_size}_size_line_height";
		} else {
			continue;
		}

		$post_id   = $font_size_meta->post_id;
		$font_size = of_get_option( $font_size_option );
		if ( $font_size ) {
			add_post_meta( $post_id, '_dt_fancy_header_subtitle_font_size', $font_size, true );
		}

		$line_height = of_get_option( $line_height_option );
		if ( $line_height ) {
			add_post_meta( $post_id, '_dt_fancy_header_subtitle_line_height', $line_height, true );
		}

		delete_post_meta( $post_id, '_dt_fancy_header_subtitle_size' );
	}
}

function the7_update_611_page_transparent_top_bar_migration() {
	global $wpdb;

	$posts_with_fancy_header = $wpdb->get_results( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_dt_header_title' AND meta_value IN ('fancy', 'slideshow')" );
	if ( ! $posts_with_fancy_header ) {
		return false;
	}

	$fancy_title_posts             = implode( ',', wp_list_pluck( $posts_with_fancy_header, 'post_id' ) );
	$posts_with_transparent_header = $wpdb->get_results( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_dt_header_background' AND meta_value = 'transparent' AND post_id IN ($fancy_title_posts)" );
	if ( ! $posts_with_transparent_header ) {
		return false;
	}

	$color_obj               = new The7_Less_Vars_Value_Color( of_get_option( 'top_bar-bg-color' ) );
	$top_bar_with_bg         = $color_obj->get_opacity() > 0;
	$top_bar_with_decoration = in_array(
		of_get_option( 'top_bar-bg-style' ),
		array(
			'fullwidth_line',
			'content_line',
		),
		true
	);
	$top_bar_opacity         = '0';
	if ( ! $top_bar_with_decoration && $top_bar_with_bg ) {
		$top_bar_opacity = '25';
	}
	$post_ids = wp_list_pluck( $posts_with_transparent_header, 'post_id' );
	foreach ( $post_ids as $post_id ) {
		if ( get_post_meta( $post_id, '_dt_header_transparent_top_bar_bg_color', true ) ) {
			continue;
		}
		update_post_meta( $post_id, '_dt_header_transparent_top_bar_bg_color', '#ffffff' );
		update_post_meta( $post_id, '_dt_header_transparent_top_bar_bg_opacity', $top_bar_opacity );
	}
}

function the7_update_630_microsite_content_visibility_settings_migration() {
	global $wpdb;

	$microsite_posts = $wpdb->get_results( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_page_template' AND meta_value = 'template-microsite.php'" );
	if ( ! $microsite_posts ) {
		return false;
	}

	$posts = wp_list_pluck( $microsite_posts, 'post_id' );
	foreach ( $posts as $post_id ) {
		$hidden_page_parts = get_post_meta( $post_id, '_dt_microsite_hidden_parts' );
		if ( ! in_array( 'content', $hidden_page_parts, true ) ) {
			continue;
		}

		// Hide bottom bar and footer.
		if ( ! in_array( 'bottom_bar', $hidden_page_parts, true ) ) {
			add_post_meta( $post_id, '_dt_microsite_hidden_parts', 'bottom_bar' );
		}
		update_post_meta( $post_id, '_dt_footer_show', '0' );
	}
}

function the7_update_641_carousel_backward_compatibility() {
	global $wpdb;

	$cache_key = 'the7_update_641_carousel_backward_compatibility_processed_posts';

	$processed_posts = get_option( $cache_key );
	if ( ! $processed_posts || ! is_array( $processed_posts ) ) {
		$processed_posts = array( '0' );
	}

	$processed_posts_str   = implode( ',', $processed_posts );
	$posts_with_inline_css = $wpdb->get_results( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'the7_shortcodes_dynamic_css' AND post_id NOT IN ($processed_posts_str)" );

	if ( ! $posts_with_inline_css ) {
		delete_option( $cache_key );

		return false;
	}

	$post_ids            = wp_list_pluck( $posts_with_inline_css, 'post_id' );
	$post_ids_str        = implode( ',', $post_ids );
	$posts_content       = $wpdb->get_results( "SELECT ID, post_content FROM $wpdb->posts WHERE ID IN ({$post_ids_str})" );
	$posts_content_array = wp_list_pluck( $posts_content, 'post_content', 'ID' );

	if ( ! class_exists( 'The7_Shortcode_Id_Crutch', false ) ) {
		include PRESSCORE_SHORTCODES_INCLUDES_DIR . '/class-the7-shortcode-id-crutch.php';
	}

	/**
	 * Little crutch to overcome short codes inner id issue.
	 *
	 * On each output short code increments inner id, which lead to fatal issues when trying to process many posts at once.
	 * First post processed normally but short codes id's in the next one will start not from 1, and inline css wil be generated with invalid selectors.
	 * This class can fix the issue. It can reset short code inner id on each iteration which emulates normal post save process.
	 */
	$id_crutch_obj = new The7_Shortcode_Id_Crutch();

	/**
	 * Hook to reset short code inner id.
	 */
	add_action( 'the7_after_shortcode_init', array( $id_crutch_obj, 'reset_id' ) );

	$tags = array(
		'dt_blog_carousel'         => 3,
		'dt_products_carousel'     => 3,
		'dt_carousel'              => 3,
		'dt_portfolio_carousel'    => 3,
		'dt_team_carousel'         => 4,
		'dt_testimonials_carousel' => 3,
	);
	foreach ( $post_ids as $post_id ) {
		if ( empty( $posts_content_array[ $post_id ] ) || wp_is_post_revision( $post_id ) ) {
			continue;
		}

		/**
		 * Reset processed tags on each iteration.
		 */
		$id_crutch_obj->reset_processed_tags();

		$save_post = false;
		$content   = $posts_content_array[ $post_id ];

		if ( ! $content ) {
			continue;
		}

		preg_match_all( '/' . get_shortcode_regex( array_keys( $tags ) ) . '/', $content, $shortcodes );
		foreach ( $shortcodes[2] as $index => $tag ) {
			$atts = shortcode_parse_atts( trim( $shortcodes[3][ $index ] ) );
			if ( isset( $atts['slides_on_wide_desk'] ) ) {
				continue;
			}

			$columns = $tags[ $tag ];
			if ( isset( $atts['slides_on_desk'] ) ) {
				$columns = (int) $atts['slides_on_desk'];
			}

			$replace    = '[' . $tag . $shortcodes[3][ $index ];
			$replace_to = $replace . ' slides_on_wide_desk="' . $columns . '"';
			$content    = str_replace( $replace, $replace_to, $content );

			$save_post = true;
		}

		if ( $save_post ) {
			wp_update_post(
				array(
					'ID'           => $post_id,
					'post_content' => $content,
				)
			);
		}

		$processed_posts[] = $post_id;
		update_option( $cache_key, $processed_posts, false );
	}

	delete_option( $cache_key );
}

function the7_update_650_disable_options_autoload() {
	global $wpdb;

	$wpdb->query( "UPDATE $wpdb->options SET autoload = 'no' WHERE option_name = 'ultimate_google_fonts'" );
}

function the7_update_693_migrate_custom_menu_widgets() {
	$sidebars_widgets = get_option( 'sidebars_widgets' );

	foreach ( $sidebars_widgets as $sidebar => &$widgets ) {
		if ( ! is_array( $widgets ) ) {
			continue;
		}

		$widgets = preg_replace(
			array(
				'/presscore-custom-menu-1(.*)/',
				'/presscore-custom-menu-2(.*)/',
			),
			array( 'presscore-custom-menu-one$1', 'presscore-custom-menu-two$1' ),
			$widgets
		);
	}
	unset( $widgets );

	update_option( 'sidebars_widgets', $sidebars_widgets );

	$widget_settings = array(
		'widget_presscore-custom-menu-1' => 'widget_presscore-custom-menu-one',
		'widget_presscore-custom-menu-2' => 'widget_presscore-custom-menu-two',
	);
	foreach ( $widget_settings as $old_id => $new_id ) {
		$old_value = get_option( $old_id );
		if ( $old_value && ! get_option( $new_id ) ) {
			update_option( $new_id, $old_value );
		}
	}
}

/**
 * Migrate shortcodes gradients.
 *
 * @param array $atts Shortcode attributes.
 *
 * @return array
 */
function the7_update_700_migrate_shortcodes_gradients( $atts ) {
	$new_atts = (array) $atts;
	if ( ! isset( $atts['image_hover_bg_color'] ) && ! empty( $atts['custom_rollover_bg_color'] ) ) {
		$new_atts['image_hover_bg_color'] = 'solid_rollover_bg';
	} elseif ( isset( $atts['image_hover_bg_color'] ) && $atts['image_hover_bg_color'] === 'solid_rollover_bg' && empty( $atts['custom_rollover_bg_color'] ) ) {
		unset( $new_atts['image_hover_bg_color'] );
	} elseif ( isset( $atts['image_hover_bg_color'] ) && $atts['image_hover_bg_color'] === 'gradient_rollover_bg' && empty( $atts['custom_rollover_bg_color_1'] ) ) {
		unset( $new_atts['image_hover_bg_color'] );
	} elseif ( isset( $atts['image_hover_bg_color'] ) && $atts['image_hover_bg_color'] === 'gradient_rollover_bg' && ! empty( $atts['custom_rollover_bg_color_1'] ) && ! empty( $atts['custom_rollover_bg_color_2'] ) ) {
		$color_1 = $atts['custom_rollover_bg_color_1'];
		$color_2 = $atts['custom_rollover_bg_color_2'];
		$angle   = isset( $atts['custom_rollover_gradient_deg'] ) ? $atts['custom_rollover_gradient_deg'] : '135deg';

		$new_atts['custom_rollover_bg_gradient'] = "$angle|$color_1 30%|$color_2 100%";
		unset( $new_atts['custom_rollover_bg_color_1'], $new_atts['custom_rollover_bg_color_2'], $new_atts['custom_rollover_gradient_deg'] );
	}

	return $new_atts;
}

function the7_update_700_shortcodes_gradient_backward_compatibility() {
	$tags = array(
		'dt_media_gallery_carousel',
		'dt_gallery_masonry',
	);

	the7_migrate_shortcodes_in_all_posts( 'the7_update_700_migrate_shortcodes_gradients', $tags, __FUNCTION__ );
}

function the7_update_730_set_fancy_title_zero_top_padding() {
	global $wpdb;

	$cache_key = 'the7_update_730_processed_posts_with_transparent_fancy_title';

	$processed_posts = get_option( $cache_key );
	if ( ! $processed_posts || ! is_array( $processed_posts ) ) {
		$processed_posts = array( '0' );
	}

	$processed_posts_str    = implode( ',', array_map( 'absint', $processed_posts ) );
	$posts_with_fancy_title = $wpdb->get_results( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_dt_header_title' AND meta_value = 'fancy' AND post_id NOT IN ($processed_posts_str)" );

	if ( $posts_with_fancy_title ) {
		$post_ids_str                  = implode( ',', array_map( 'absint', wp_list_pluck( $posts_with_fancy_title, 'post_id' ) ) );
		$posts_with_transparent_header = $wpdb->get_results( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_dt_header_background' AND meta_value = 'transparent' AND post_id IN ($post_ids_str)" );

		foreach ( $posts_with_transparent_header as $affected_post ) {
			update_post_meta( $affected_post->post_id, '_dt_fancy_header_padding-top', '0px' );
			$processed_posts[] = $affected_post->post_id;
			update_option( $cache_key, $processed_posts, false );
		}
	}

	delete_option( $cache_key );
}

function the7_update_730_fancy_title_responsiveness_settings() {
	global $wpdb;

	$defaults = array(
		'_dt_fancy_header_responsiveness'                  => 'enabled',
		'_dt_fancy_header_responsiveness_switch'           => '778px',
		'_dt_fancy_header_responsive_height'               => '70',
		'_dt_fancy_header_responsive_font_size'            => '30',
		'_dt_fancy_header_responsive_title_line_height'    => '38',
		'_dt_fancy_header_responsive_subtitle_font_size'   => '20',
		'_dt_fancy_header_responsive_subtitle_line_height' => '28',
		'_dt_fancy_header_responsive_breadcrumbs'          => 'disabled',
	);

	$posts_with_fancy_title = $wpdb->get_results( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_dt_header_title' AND meta_value = 'fancy'" );
	foreach ( $posts_with_fancy_title as $post ) {
		foreach ( $defaults as $meta_name => $meta_value ) {
			if ( '' === get_post_meta( $post->post_id, $meta_name, true ) ) {
				update_post_meta( $post->post_id, $meta_name, $meta_value );
			}
		}

		the7_update_post_css_on_save( $post->post_id );
	}
}

/**
 * Migrate fancy title uppercase post meta.
 *
 * @since 7.4.0
 *
 * @global $wpdb
 */
function the7_update_740_fancy_title_uppercase_migration() {
	global $wpdb;

	$post_meta_migration = array(
		'_dt_fancy_header_uppercase'          => '_dt_fancy_header_text_transform',
		'_dt_fancy_header_subtitle_uppercase' => '_dt_fancy_header_subtitle_text_transform',
	);

	$posts_with_fancy_title = $wpdb->get_results( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_dt_header_title' AND meta_value = 'fancy'" );
	foreach ( $posts_with_fancy_title as $post ) {
		foreach ( $post_meta_migration as $old_meta => $new_meta ) {
			$old_value = get_post_meta( $post->post_id, $old_meta, true );
			$new_value = (int) $old_value ? 'uppercase' : 'none';
			add_post_meta( $post->post_id, $new_meta, $new_value );
			delete_post_meta( $post->post_id, $old_meta );
		}
	}
}

/**
 * Migrate blog back button urls.
 *
 * @since 7.4.3
 *
 * @global $wpdb
 */
function the7_update_743_back_button_migration() {
	global $wpdb;

	// Find only integer meta values.
	$posts_with_back_buttons = $wpdb->get_results( "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = '_dt_post_options_back_button' AND concat('',meta_value * 1) = meta_value" );
	foreach ( $posts_with_back_buttons as $post ) {
		if ( $post->meta_value === '' ) {
			continue;
		}

		$new_value = '';
		if ( (int) $post->meta_value ) {
			$new_value = wp_make_link_relative( get_permalink( (int) $post->meta_value ) );
		}

		update_post_meta( $post->post_id, '_dt_post_options_back_button', $new_value );
	}
}

/**
 * Migrate the7 mega menu settings.
 *
 * @since 7.6.0
 */
function the7_update_760_mega_menu_migration() {
	global $wpdb;

	$menu_items_simple_migration = array(
		'_menu_item_dt_mega_menu_enabled'     => 'mega-menu',
		'_menu_item_dt_mega_menu_fullwidth'   => 'mega-menu-fullwidth',
		'_menu_item_dt_mega_menu_columns'     => 'mega-menu-columns',
		'_menu_item_dt_mega_menu_hide_title'  => 'mega-menu-hide-title',
		'_menu_item_dt_mega_menu_remove_link' => 'mega-menu-remove-link',
		'_menu_item_dt_mega_menu_new_row'     => 'mega-menu-start-new-row',
		'_menu_item_dt_mega_menu_new_column'  => 'mega-menu-start-new-column',
	);

	$menu_items = $wpdb->get_results( "SELECT ID FROM $wpdb->posts WHERE post_type = 'nav_menu_item'" );

	foreach ( $menu_items as $menu_item ) {
		$post_id            = $menu_item->ID;
		$mega_menu_settings = array();

		if ( get_post_meta( $post_id, '_menu_item_the7_mega_menu_settings', true ) ) {
			continue;
		}

		foreach ( $menu_items_simple_migration as $old_meta => $new_meta ) {
			$old_meta_value = get_post_meta( $post_id, $old_meta, true );
			if ( $old_meta_value ) {
				$mega_menu_settings[ $new_meta ] = $old_meta_value;
			}
		}

		$icon_type_meta_value = get_post_meta( $post_id, '_menu_item_dt_mega_menu_icon', true );
		if ( $icon_type_meta_value === 'iconfont' ) {
			$mega_menu_settings['menu-item-icon-type'] = 'html';
			$mega_menu_settings['menu-item-icon-html'] = (string) get_post_meta(
				$post_id,
				'_menu_item_dt_mega_menu_iconfont',
				true
			);
		}
		update_post_meta(
			$post_id,
			'_menu_item_the7_mega_menu_settings',
			$mega_menu_settings
		);
	}
}

function the7_update_761_dashboard_settings_migration() {
	$dashboard_settings = get_option( The7_Admin_Dashboard_Settings::SETTINGS_ID, array() );
	if ( ! isset( $dashboard_settings['admin-icons-bar'] ) && isset( $dashboard_settings['icons-bar'] ) ) {
		$dashboard_settings['admin-icons-bar'] = $dashboard_settings['icons-bar'];
	}
	unset( $dashboard_settings['icons-bar'] );
	update_option( The7_Admin_Dashboard_Settings::SETTINGS_ID, $dashboard_settings );
}

/**
 * Migrate blog shortcodes.
 *
 * @param array $atts Shortcode attributes.
 *
 * @return array
 */
function the7_update_770_migrate_blog_shortcodes( $atts ) {
	$new_atts = (array) $atts;
	if ( isset( $atts['image_scale_animation_on_hover'] ) ) {
		$old_animation = $atts['image_scale_animation_on_hover'];
		if ( $old_animation === 'n' ) {
			$new_atts['image_scale_animation_on_hover'] = 'disabled';
		} elseif ( $old_animation === 'y' ) {
			$new_atts['image_scale_animation_on_hover'] = 'slow_scale';
		}
	}
	if ( isset( $atts['image_hover_bg_color'] ) ) {
		$old_bg_color = $atts['image_hover_bg_color'];
		if ( $old_bg_color === 'n' ) {
			$new_atts['image_hover_bg_color'] = 'disabled';
		} elseif ( $old_bg_color === 'y' ) {
			$new_atts['image_hover_bg_color'] = 'default';
		}
	}

	return $new_atts;
}

/**
 * This function launch content migration for blog shortcodes.
 *
 * @see the7_update_770_migrate_blog_shortcodes
 */
function the7_update_770_shortcodes_blog_backward_compatibility() {
	the7_migrate_shortcodes_in_all_posts( 'the7_update_770_migrate_blog_shortcodes', array( 'dt_blog_list' ), __FUNCTION__ );
}

/**
 * Migrate blog shortcodes.
 *
 * @param array $atts Shortcode attributes.
 *
 * @return array
 */
function the7_update_771_migrate_blog_shortcodes( $atts ) {
	$new_atts = the7_update_770_migrate_blog_shortcodes( $atts );

	if ( isset( $atts['layout'] ) && $atts['layout'] === 'gradient_overlay' ) {
		if ( isset( $atts['content_bg'] ) && $atts['content_bg'] === 'n' ) {
			$new_atts['image_hover_bg_color'] = 'disabled';
		} elseif ( empty( $atts['custom_content_bg_color'] ) ) {
			$new_atts['image_hover_bg_color'] = 'default';
		} else {
			$new_atts['image_hover_bg_color']     = 'solid_rollover_bg';
			$new_atts['custom_rollover_bg_color'] = $atts['custom_content_bg_color'];
		}
	}

	return $new_atts;
}

/**
 * This function launch content migration for blog shortcodes.
 *
 * @see the7_update_771_migrate_blog_shortcodes
 */
function the7_update_771_shortcodes_blog_backward_compatibility() {
	the7_migrate_shortcodes_in_all_posts( 'the7_update_771_migrate_blog_shortcodes', array( 'dt_blog_masonry', 'dt_blog_carousel' ), __FUNCTION__ );
}

/**
 * Migrate button shortcodes.
 *
 * @param array $atts Shortcode attributes.
 *
 * @return array
 */
function the7_update_771_migrate_button_shortcodes( $atts ) {
	$new_atts = $atts;
	if ( ! isset( $atts['btn_decoration'] ) && isset( $atts['size'] ) && $atts['size'] === 'custom' ) {
		$opt_to_att_array   = array(
			'3d'     => 'btn_3d',
			'shadow' => 'btn_shadow',
		);
		$buttons_decoration = of_get_option( 'buttons-style' );
		if ( $buttons_decoration && array_key_exists( $buttons_decoration, $opt_to_att_array ) ) {
			$new_atts['btn_decoration'] = $opt_to_att_array[ $buttons_decoration ];
		}
	}

	return $new_atts;
}

/**
 * This function launch content migration for button shortcodes.
 *
 * @see the7_update_771_migrate_button_shortcodes
 */
function the7_update_771_shortcodes_button_backward_compatibility() {
	the7_migrate_shortcodes_in_all_posts( 'the7_update_771_migrate_button_shortcodes', array( 'dt_default_button' ), __FUNCTION__ );
}

/**
 * Enable Font Awesome compatibility mode after theme update, if needed.
 */
function the7_update_775_fontawesome_compatibility() {
	if ( The7_Admin_Dashboard_Settings::get( 'fontawesome-4-compatibility' ) ) {
		The7_Icon_Manager::enable_fontawesome4();
	} else {
		The7_Icon_Manager::enable_fontawesome5();
	}
}

/**
 * Migrate default button shortcode.
 *
 * @param array $atts Shortcode attributes.
 *
 * @return array
 */
function the7_update_780_migrate_default_buttons_shortcodes( $atts ) {
	$new_atts = $atts;

	$sizes = array(
		'small'  => 's',
		'medium' => 'm',
		'big'    => 'l',
	);

	$defaults = array(
		'text_color'                     => '',
		'default_btn_bg'                 => 'y',
		'default_btn_bg_hover'           => 'y',
		'default_btn_bg_color'           => '',
		'default_btn_border_color'       => '',
		'text_hover_color'               => '',
		'bg_hover_color'                 => '',
		'default_btn_border_hover_color' => '',
		'icon_size'                      => '11px',
	);

	$current_size = 'small';
	if ( isset( $atts['size'] ) ) {
		$current_size = $atts['size'];
	}

	if ( array_key_exists( $current_size, $sizes ) ) {
		foreach ( $defaults as $param => $val ) {
			if ( isset( $atts[ $param ] ) && $atts[ $param ] !== $val ) {
				$new_atts['size'] = 'custom';
				$suffix           = $sizes[ $current_size ];
				$custom_atts      = array(
					'button_padding' => of_get_option( "buttons-{$suffix}_padding" ),
					'border_radius'  => of_get_option( "buttons-{$suffix}_border_radius" ),
					'border_width'   => of_get_option( "buttons-{$suffix}_border_width" ),
				);
				$typography       = of_get_option( "buttons-{$suffix}-typography" );

				if ( isset( $typography['font_size'] ) ) {
					$custom_atts['icon_size'] = $typography['font_size'];
					$custom_atts['font_size'] = $typography['font_size'];
				}

				$new_atts = array_merge( $custom_atts, $new_atts );
				ksort( $new_atts );
				break;
			}
		}
	}

	return $new_atts;
}

/**
 * Migrate shortcodes.
 */
function the7_update_780_shortcodes_backward_compatibility() {
	the7_migrate_shortcodes_in_all_posts( 'the7_update_780_migrate_default_buttons_shortcodes', array( 'dt_default_button' ), __FUNCTION__ );
}

/**
 * Silence plugins purchase notification.
 */
function the7_update_790_silence_plugins_purchase_notification() {
	The7_Admin_Dashboard_Settings::set( 'silence-purchase-notification', true );
}

/**
 * Ensure that post padding have units.
 */
function the7_update_830_fix_post_padding_meta() {
	global $wpdb;

	$query         = "select post_id, meta_key, meta_value from $wpdb->postmeta where meta_key like '_dt_page_overrides_%' and meta_value != ''";
	$paddings_meta = $wpdb->get_results( $query );

	foreach ( $paddings_meta as $padding_meta ) {
		$meta_value = $padding_meta->meta_value;
		if ( $meta_value === (string) intval( $meta_value ) ) {
			update_post_meta( $padding_meta->post_id, $padding_meta->meta_key, $meta_value . 'px' );
		}
	}
}

/**
 * Duplicate post padding to post mobile padding.
 */
function the7_update_830_migrate_post_mobile_padding() {
	global $wpdb;

	$query         = "select post_id, meta_key, meta_value from $wpdb->postmeta where meta_key like '_dt_page_overrides_%' and meta_value != ''";
	$paddings_meta = $wpdb->get_results( $query );

	$padding_to_mobile = array(
		'_dt_page_overrides_top_margin'    => '_dt_mobile_page_padding_top',
		'_dt_page_overrides_right_margin'  => '_dt_mobile_page_padding_right',
		'_dt_page_overrides_bottom_margin' => '_dt_mobile_page_padding_bottom',
		'_dt_page_overrides_left_margin'   => '_dt_mobile_page_padding_left',
	);

	foreach ( $paddings_meta as $padding_meta ) {
		$padding_meta_key = $padding_meta->meta_key;

		if ( ! array_key_exists( $padding_meta_key, $padding_to_mobile ) ) {
			continue;
		}

		$mobile_meta_key = $padding_to_mobile[ $padding_meta_key ];
		$post_id         = $padding_meta->post_id;
		if ( metadata_exists( 'post', $post_id, $mobile_meta_key ) ) {
			continue;
		}

		update_post_meta( $post_id, $mobile_meta_key, $padding_meta->meta_value );
	}
}

function the7_update_850_migrate_post_footer_visibility() {
	global $wpdb;

	$posts_with_empty_footer_meta = $wpdb->get_results( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_dt_footer_show' AND meta_value = ''" );
	if ( empty( $posts_with_empty_footer_meta ) ) {
		return false;
	}

	$posts = wp_list_pluck( $posts_with_empty_footer_meta, 'post_id' );
	foreach ( $posts as $post_id ) {
		update_post_meta( $post_id, '_dt_footer_show', '1' );
	}
}

function the7_update_8502_migrate_post_footer_source_for_elementor() {
	global $wpdb;

	$posts_without_footer_source = $wpdb->get_results( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_elementor_edit_mode' AND meta_value = 'builder'" );
	if ( empty( $posts_without_footer_source ) ) {
		return false;
	}

	$posts = wp_list_pluck( $posts_without_footer_source, 'post_id' );
	foreach ( $posts as $post_id ) {
		add_metadata( 'post', $post_id, '_dt_footer_elementor_source', 'the7', true );
	}
}

function the7_update_890_elementor_the7_elements() {
	if ( ! the7_elementor_is_active() ) {
		return false;
	}

	require_once PRESSCORE_MODS_DIR . '/compatibility/elementor/upgrade/widgets/class-the7-elementor-masonry-migrations.php';

	$updater = new \The7\Mods\Compatibility\Elementor\Upgrade\Updater();
	\The7\Mods\Compatibility\Elementor\Upgrade\Widgets\The7_Elementor_Masonry_Migrations::run( '_8_9_0_migration', $updater );

	the7_elementor_flush_css_cache();

	return false;
}

function the7_update_912_elementor_the7_elements() {
	if ( ! the7_elementor_is_active() || the7_is_elementor2() ) {
		return false;
	}

	$function_names = array(
		'_v_3_0_0_move_general_settings_to_kit',
		'_v_3_0_0_move_default_colors_to_kit',
		'_v_3_0_0_move_saved_colors_to_kit',
		'_v_3_0_0_move_default_typography_to_kit',
	);

	$updater           = new \The7\Mods\Compatibility\Elementor\Upgrade\Updater();
	$upgrade_callbacks = the7_update_elementor_get_upgrade_callbacks( $function_names );
	foreach ( $upgrade_callbacks as $callback ) {
		$callback( $updater );
	}

	// Fix incorrect slider format after settings to kit migration.
	$active_kit_id = \Elementor\Plugin::$instance->kits_manager->get_active_id();
	$kit           = \Elementor\Plugin::$instance->documents->get( $active_kit_id );
	if ( $kit ) {
		$meta_key     = \Elementor\Core\Settings\Page\Manager::META_KEY;
		$kit_settings = $kit->get_meta( $meta_key );
		$update_kit   = false;

		if ( ! isset( $kit_settings['space_between_widgets'] ) ) {
			if ( get_option( 'elementor_space_between_widgets' ) === '0' ) {
				$update_kit = true;
			}
		} elseif ( (string) $kit_settings['space_between_widgets'] === '0' ) {
			$update_kit = true;
		}

		if ( $update_kit ) {
			$kit_settings['space_between_widgets'] = [
				'unit' => 'px',
				'size' => '0',
			];

			$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );
			$page_settings_manager->save_settings( $kit_settings, $active_kit_id );
		}
	}

	the7_elementor_flush_css_cache();

	return false;
}

function the7_update_elementor_get_upgrade_callbacks( $function_names ) {
	$prefix              = '_v_';
	$upgrades_class      = \Elementor\Plugin::$instance->upgrade->get_upgrades_class();
	$upgrades_reflection = new \ReflectionClass( $upgrades_class );

	$callbacks = [];

	foreach ( $upgrades_reflection->getMethods() as $method ) {
		$method_name = $method->getName();
		if ( false === strpos( $method_name, $prefix ) ) {
			continue;
		}

		if ( ! in_array( $method_name, $function_names, true ) ) {
			continue;
		}

		$callbacks[] = [ $upgrades_class, $method_name ];
	}

	return $callbacks;
}

function the7_update_931_elementor_the7_photo_scroller() {
	if ( ! the7_elementor_is_active() ) {
		return false;
	}

	require_once PRESSCORE_MODS_DIR . '/compatibility/elementor/upgrade/widgets/class-the7-elementor-photo-scroller-migrations.php';

	$updater = new \The7\Mods\Compatibility\Elementor\Upgrade\Updater();
	\The7\Mods\Compatibility\Elementor\Upgrade\Widgets\The7_Elementor_Photo_Scroller_Migrations::run( '_9_3_1_migration', $updater );

	the7_elementor_flush_css_cache();

	return false;
}

function the7_update_940_elementor_the7_posts_masonry() {
	if ( ! the7_elementor_is_active() ) {
		return false;
	}

	require_once PRESSCORE_MODS_DIR . '/compatibility/elementor/upgrade/widgets/class-the7-elementor-masonry-migrations.php';

	$updater = new \The7\Mods\Compatibility\Elementor\Upgrade\Updater();
	\The7\Mods\Compatibility\Elementor\Upgrade\Widgets\The7_Elementor_Masonry_Migrations::run( '_9_4_0_migration', $updater );

	the7_elementor_flush_css_cache();

	return false;
}

function the7_update_940_theme_options() {
	require_once __DIR__ . '/patches/class-the7-db-patch-090400.php';
	the7_apply_theme_options_migration( new The7_DB_Patch_090400() );
}

function the7_update_9402_theme_options() {
	require_once __DIR__ . '/patches/class-the7-db-patch-090402.php';
	the7_apply_theme_options_migration( new The7_DB_Patch_090402() );
}

function the7_update_9600_theme_options() {
	require_once __DIR__ . '/patches/class-the7-db-patch-090600.php';
	the7_apply_theme_options_migration( new The7_DB_Patch_090600() );
}

function the7_update_9142_theme_options() {
	require_once __DIR__ . '/patches/class-the7-db-patch-091402.php';
	the7_apply_theme_options_migration( new The7_DB_Patch_091402() );
}

function the7_update_960_elementor_the7_posts_carousel() {
	if ( ! the7_elementor_is_active() ) {
		return false;
	}

	require_once PRESSCORE_MODS_DIR . '/theme-update/migrations/v9_6_0/posts-carousel-widget-migration.php';

	The7\Inc\Mods\ThemeUpdate\Migrations\v9_6_0\Posts_Carousel_Widget_Migration::migrate();

	the7_elementor_flush_css_cache();

	return false;
}

function the7_update_9130_elementor_the7_posts_carousel() {
	if ( ! the7_elementor_is_active() ) {
		return false;
	}

	require_once PRESSCORE_MODS_DIR . '/theme-update/migrations/v9_13_0/posts-carousel-widget-migration.php';

	The7\Inc\Mods\ThemeUpdate\Migrations\v9_13_0\Posts_Carousel_Widget_Migration::migrate();

	the7_elementor_flush_css_cache();

	return false;
}

function the7_update_9130_elementor_the7_testimonials_carousel() {
	if ( ! the7_elementor_is_active() ) {
		return false;
	}

	require_once PRESSCORE_MODS_DIR . '/theme-update/migrations/v9_13_0/testimonials-carousel-widget-migration.php';

	The7\Inc\Mods\ThemeUpdate\Migrations\v9_13_0\Testimonials_Carousel_Widget_Migration::migrate();

	the7_elementor_flush_css_cache();

	return false;
}

function the7_update_9130_elementor_the7_text_and_icon_carousel() {
	if ( ! the7_elementor_is_active() ) {
		return false;
	}

	require_once PRESSCORE_MODS_DIR . '/theme-update/migrations/v9_13_0/text-and-icon-carousel-widget-migration.php';

	The7\Inc\Mods\ThemeUpdate\Migrations\v9_13_0\Text_And_Icon_Carousel_Widget_Migration::migrate();

	the7_elementor_flush_css_cache();

	return false;
}

/**
 * Migrate simple posts widget.
 */
function the7_update_9140_simple_posts_widget() {
	The7\Mods\Theme_Update\Migrations\v09_14_0\Simple_Posts_Widget_Migration::migrate();
}

/**
 * Migrate simple posts carousel widget.
 */
function the7_update_9140_simple_posts_carousel_widget() {
	The7\Mods\Theme_Update\Migrations\v09_14_0\Simple_Posts_Carousel_Widget_Migration::migrate();
}

/**
 * Migrate simple products widget.
 */
function the7_update_9140_simple_products_widget() {
	The7\Mods\Theme_Update\Migrations\v09_14_0\Simple_Products_Widget_Migration::migrate();
}

/**
 * Migrate simple product carousel widget.
 */
function the7_update_9140_simple_products_carousel_widget() {
	The7\Mods\Theme_Update\Migrations\v09_14_0\Simple_Products_Carousel_Widget_Migration::migrate();
}

/**
 * Migrate simple product category widget.
 */
function the7_update_9140_simple_product_category_widget() {
	The7\Mods\Theme_Update\Migrations\v09_14_0\Simple_Product_Category_Widget_Migration::migrate();
}

/**
 * Migrate simple product category carousel widget.
 */
function the7_update_9140_simple_product_category_carousel_widget() {
	The7\Mods\Theme_Update\Migrations\v09_14_0\Simple_Product_Category_Carousel_Widget_Migration::migrate();
}

/**
 * Migrate posts carousel widget.
 */
function the7_update_9150_posts_carousel_widget() {
	The7\Mods\Theme_Update\Migrations\v09_15_1\Carousel_Widget_Width_Migration::migrate( 'the7_elements_carousel' );
}

/**
 * Migrate multipurpose carousel widget.
 */
function the7_update_9150_multipurpose_carousel_widget() {
	The7\Mods\Theme_Update\Migrations\v09_15_1\Carousel_Widget_Width_Migration::migrate( 'the7_content_carousel' );
}

/**
 * Migrate testimonials carousel widget.
 */
function the7_update_9150_testimonials_carousel_widget() {
	The7\Mods\Theme_Update\Migrations\v09_15_1\Carousel_Widget_Width_Migration::migrate( 'the7_testimonials_carousel' );
}

/**
 * Migrate simple posts widget.
 */
function the7_update_91501_simple_posts_widget_border() {
	The7\Mods\Theme_Update\Migrations\v09_15_1\Simple_Widgets_Border_Migration::migrate( 'the7-elements-simple-posts' );
}

/**
 * Migrate simple posts carousel widget.
 */
function the7_update_91501_simple_posts_carousel_widget_border() {
	The7\Mods\Theme_Update\Migrations\v09_15_1\Simple_Widgets_Border_Migration::migrate( 'the7-elements-simple-posts-carousel' );
}

/**
 * Migrate simple products categories widget.
 */
function the7_update_91501_simple_products_categories_widget_border() {
	The7\Mods\Theme_Update\Migrations\v09_15_1\Simple_Widgets_Border_Migration::migrate( 'the7-elements-simple-product-categories' );
}

/**
 * Migrate simple products categories carousel widget.
 */
function the7_update_91501_simple_products_categories_carousel_widget_border() {
	The7\Mods\Theme_Update\Migrations\v09_15_1\Simple_Widgets_Border_Migration::migrate( 'the7-simple-product-categories-carousel' );
}

/**
 * Migrate simple products widget.
 */
function the7_update_91501_simple_products_widget_border() {
	The7\Mods\Theme_Update\Migrations\v09_15_1\Simple_Widgets_Border_Migration::migrate( 'the7-elements-woo-simple-products' );
}

/**
 * Migrate simple products carousel widget.
 */
function the7_update_91501_simple_products_carousel_widget_border() {
	The7\Mods\Theme_Update\Migrations\v09_15_1\Simple_Widgets_Border_Migration::migrate( 'the7-elements-woo-simple-products-carouse' );
}

/**
 * Turn off elementor buttons integration.
 */
function the7_update_9160_set_buttons_integration_off() {
	if ( ! The7_Admin_Dashboard_Settings::setting_exists( 'elementor-buttons-integration' ) ) {
		The7_Admin_Dashboard_Settings::set( 'elementor-buttons-integration', false );
	}
}

/**
 * Migrate button controls in posts masonry widget.
 */
function the7_update_9160_posts_masonry_widget_buttons() {
	The7\Mods\Theme_Update\Migrations\v09_16_0\Posts_Widget_Button_Migration::migrate( 'the7_elements' );
}

/**
 * Migrate button controls in posts carousel widget.
 */
function the7_update_9160_posts_carousel_widget_buttons() {
	The7\Mods\Theme_Update\Migrations\v09_16_0\Posts_Widget_Button_Migration::migrate( 'the7_elements_carousel' );
}

/**
 * Migrate button controls in icon box grid widget.
 */
function the7_update_9160_icon_box_grid_widget_buttons() {
	The7\Mods\Theme_Update\Migrations\v09_16_0\Icon_Box_Grid_Widget_Button_Migration::migrate( 'the7_icon_box_grid_widget' );
}

/**
 * Migrate button controls in simple posts carousel widget.
 */
function the7_update_9160_simple_posts_carousel_widget_buttons() {
	The7\Mods\Theme_Update\Migrations\v09_16_0\Posts_Widget_Button_Migration::migrate( 'the7-elements-simple-posts-carousel' );
}

/**
 * Migrate button controls in simple posts widget.
 */
function the7_update_9160_simple_posts_widget_buttons() {
	The7\Mods\Theme_Update\Migrations\v09_16_0\Posts_Widget_Button_Migration::migrate( 'the7-elements-simple-posts' );
}

/**
 * Migrate button controls in simple product categories widget.
 */
function the7_update_9160_simple_product_categories_widget_buttons() {
	The7\Mods\Theme_Update\Migrations\v09_16_0\Posts_Widget_Button_Migration::migrate( 'the7-elements-simple-product-categories' );
}

/**
 * Migrate button controls in simple product categories carousel widget.
 */
function the7_update_9160_simple_product_categories_carousel_widget_buttons() {
	The7\Mods\Theme_Update\Migrations\v09_16_0\Posts_Widget_Button_Migration::migrate( 'the7-simple-product-categories-carousel' );
}

/**
 * Migrate button controls in simple products widget.
 */
function the7_update_9160_simple_products_widget_buttons() {
	The7\Mods\Theme_Update\Migrations\v09_16_0\Simple_Product_Widgets_Button_Migration::migrate( 'the7-elements-woo-simple-products' );
}

/**
 * Migrate button controls in simple products carousel widget.
 */
function the7_update_9160_simple_products_carousel_widget_buttons() {
	The7\Mods\Theme_Update\Migrations\v09_16_0\Simple_Product_Widgets_Button_Migration::migrate( 'the7-elements-woo-simple-products-carousel' );
}

/**
 * Migrate button controls in products widget.
 */
function the7_update_9160_products_widget_buttons() {
	The7\Mods\Theme_Update\Migrations\v09_16_0\Products_Widget_Button_Migration::migrate( 'the7-wc-products' );
}

/**
 * Migrate button controls in testimonials widget.
 */
function the7_update_9160_testimonials_widget_buttons() {
	The7\Mods\Theme_Update\Migrations\v09_16_0\Icon_Box_Grid_Widget_Button_Migration::migrate( 'the7_testimonials_carousel' );
}

/**
 * Migrate button controls in text and icon carousel widget.
 */
function the7_update_9160_text_and_icon_carousel_widget_buttons() {
	The7\Mods\Theme_Update\Migrations\v09_16_0\Icon_Box_Grid_Widget_Button_Migration::migrate( 'the7_content_carousel' );
}

/**
 * Migrate button controls in text and icon carousel widget.
 */
function the7_update_9170_the7_nav_menu_widget() {
	\The7\Mods\Theme_Update\Migrations\v09_17_0\Vertical_Menu_Widget_Migration::migrate();
}


/**
 * Manage flags upon migration.
 */
function the7_update_10_0_0_manage_flags() {
	delete_site_option( 'the7-beta-tester' );

	// Used in `the7-dashboard.php`.
	add_option( 'the7-theme-style-migrate-first', true );
}

/**
 * Migrate WC `add to cart` icon for `The7 Products` widget.
 */
function the7_update_10_1_0_products_add_to_cart_icon_migration() {
	The7\Mods\Theme_Update\Migrations\v10_1_0\Products_Add_To_Cart_Icon_Migration::migrate( 'the7-wc-products' );
}

/**
 * Migrate WC `add to cart` icon for `The7 Products Carousel` widget.
 */
function the7_update_10_1_0_products_carousel_add_to_cart_icon_migration() {
	The7\Mods\Theme_Update\Migrations\v10_1_0\Products_Add_To_Cart_Icon_Migration::migrate( 'the7-wc-products-carousel' );
}

/**
 * Migrate Horizontal Menu widget gap.
 */
function the7_update_10_2_0_horizontal_menu_gap_migration() {
	The7\Mods\Theme_Update\Migrations\v10_2_0\Horizontal_Menu_Gap_Migration::migrate();
}

/**
 * Activate deprecated widgets by default.
 */
function the7_update_10_3_0_activate_deprecated_elementor_widgets() {
	if ( ! The7_Admin_Dashboard_Settings::setting_exists( 'deprecated_elementor_widgets' ) ) {
		the7_elementor_activate_deprecated_widgets();
	}
}

/**
 * Migrate portfolio breadcrumbs text.
 */
function the7_update_10_3_0_migrate_portfolio_bredcrumbs_text() {
	$portfolio_breadcrumbs = of_get_option( 'portfolio-breadcrumbs-text' );
	if ( $portfolio_breadcrumbs && ! The7_Admin_Dashboard_Settings::setting_exists( 'portfolio-breadcrumbs-text' ) ) {
		The7_Admin_Dashboard_Settings::set( 'portfolio-breadcrumbs-text', (string) $portfolio_breadcrumbs );
	}
}

/**
 * Activate deprecated megamenu by default.
 */
function the7_update_10_3_0_activate_deprecated_mega_menu_settings() {
	The7_Admin_Dashboard_Settings::set( 'deprecated_mega_menu_settings', true );
}

/**
 * Migrate filter settings in `Posts Masonry & Grid` widget.
 *
 * @return void
 */
function the7_update_10_4_0_posts_masonry_grid_filters_migration() {
	\The7\Mods\Theme_Update\Migrations\v10_4_0\Posts_Filter_Gap_Migration::migrate();
}

/**
 * Replace Andale Mono font with Space Mono, in theme options, since it is not fully cross-platform.
 *
 * @return void
 */
function the7_update_10_4_0_replace_andale_mono_font_in_theme_options() {
	$option_values         = optionsframework_get_options();
	$option_definitions    = _optionsframework_options();
	$updated_option_values = [];

	foreach ( $option_definitions as $option ) {
		if ( ! isset( $option['id'] ) || ! isset( $option['type'] ) ) {
			continue;
		}

		$id = $option['id'];

		if ( ! isset( $option_values[ $id ] ) ) {
			continue;
		}

		$option_value = $option_values[ $id ];

		if ( $option['type'] === 'typography' && isset( $option_value['font_family'] ) && $option_value['font_family'] === 'Andale Mono' ) {
			$updated_option_values[ $id ]                = $option_value;
			$updated_option_values[ $id ]['font_family'] = 'Space Mono';
		} elseif ( $option['type'] === 'web_fonts' && $option_value === 'Andale Mono' ) {
			$updated_option_values[ $id ] = 'Space Mono';
		}
	}

	if ( $updated_option_values ) {
		update_option( optionsframework_get_options_id(), array_merge( $option_values, $updated_option_values ) );
	}
}

/**
 * Turn on `Additional Custom Breakpoints` experiment if it is in use.
 *
 * @return void
 */
function the7_update_10_4_3_maybe_turn_on_elementor_custom_breakpoints() {
	$experiment = get_option( 'elementor_experiment-additional_custom_breakpoints' );
	if ( $experiment && $experiment !== 'default' ) {
		return;
	}

	if ( ! class_exists( 'Elementor\Plugin' ) ) {
		return;
	}

	$kit_id = \Elementor\Plugin::$instance->kits_manager->get_active_id();
	$kit    = \Elementor\Plugin::$instance->documents->get( $kit_id );

	if ( ! $kit ) {
		return;
	}

	$active_break_points = $kit->get_settings( 'active_breakpoints' );

	if ( ! is_array( $active_break_points ) || count( $active_break_points ) < 3 ) {
		return;
	}

	update_option( 'elementor_experiment-additional_custom_breakpoints', 'active' );
}

/**
 * Turn off elementor last spacing
 *
 * @return void
 */
function the7_update_10_5_0_turn_off_elementor_paragraph_last_spacing() {
	$option = 'elementor-zero-paragraph-last-spacing';
	if ( ! The7_Admin_Dashboard_Settings::setting_exists( $option ) ) {
		The7_Admin_Dashboard_Settings::set( $option, false );
	}
}

/**
 * Turn off elementor last spacing
 *
 * @return void
 */
function the7_update_11_13_0_turn_off_elementor_canvas() {
    $option = 'elementor-canvas';
    if ( ! The7_Admin_Dashboard_Settings::setting_exists( $option ) ) {
        The7_Admin_Dashboard_Settings::set( $option, false );
    }
}

/**
 * Migrate scroll to top button position settings.
 *
 * @return void
 */
function the7_update_10_13_1_responsive_scroll_top_offsets() {
	if ( ! the7_elementor_is_active() ) {
		return;
	}
	$kit = \Elementor\Plugin::$instance->kits_manager->get_active_kit();
	if ( ! $kit ) {
		return;
	}
	$the7_scroll_to_top_button_position = $kit->get_settings( 'the7_scroll_to_top_button_position' );
	if ( empty( $the7_scroll_to_top_button_position ) ) {
		$the7_scroll_to_top_button_position = 'left';
	}
	$the7_scroll_to_top_offset = $kit->get_settings( "the7_scroll_to_top_button_h_offset_{$the7_scroll_to_top_button_position}" );
	if ( $the7_scroll_to_top_offset !== null ) {
		$kit->update_settings( [ 'the7_scroll_to_top_button_h_offset' => $the7_scroll_to_top_offset ] );
	}
}

/**
 * Will prepare the7 dashboard settings.
 *
 * @return void
 */
function the7_update_11_0_0_prepare_dashboard_settings() {
	$settings = [
		'settings-preset'                 => 'custom',
		'the7-icons-for-elementor'        => true,
		'legacy-elementor-theme-features' => true,
	];
	foreach ( $settings as $setting => $value ) {
		if ( ! The7_Admin_Dashboard_Settings::setting_exists( $setting ) ) {
			The7_Admin_Dashboard_Settings::set( $setting, $value );
		}
	}
}

/**
 *
 * @return void
 */
function the7_update_11_0_0_dismiss_splash_screen_notice() {
	the7_admin_notices()->dismiss_notice( 'the7_show_registration_splash_screen' );
}

/**
 * @return void
 */
function the7_update_11_0_2_return_dashboard_setting_defaults() {
	// Only for custom preset.
	if ( The7_Admin_Dashboard_Settings::get( 'settings-preset' ) !== 'custom' ) {
		return;
	}

	if ( ! The7_Admin_Dashboard_Settings::setting_exists( 'elementor-theme-style' ) ) {
		The7_Admin_Dashboard_Settings::set( 'elementor-theme-style', false );
	}

	$post_types_settings = [
		'testimonials' => 'dt_testimonials',
		'team'         => 'dt_team',
		'albums'       => 'dt_gallery',
		'slideshow'    => 'dt_slideshow',
	];
	foreach ( $post_types_settings as $setting => $post_type ) {
		if ( The7_Admin_Dashboard_Settings::setting_exists( $setting ) ) {
			continue;
		}

		$posts = get_posts(
			[
				'post_type'      => $post_type,
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			]
		);
		if ( count( $posts ) > 0 ) {
			The7_Admin_Dashboard_Settings::set( $setting, true );
		}
	}
}

/**
 * @return void
 */
function the7_update_11_11_1_set_elementor_the7_typography_fix_to_false() {
	if ( ! The7_Admin_Dashboard_Settings::setting_exists( 'elementor-the7-typography-fix' ) ) {
		The7_Admin_Dashboard_Settings::set( 'elementor-the7-typography-fix', false );
	}
}
