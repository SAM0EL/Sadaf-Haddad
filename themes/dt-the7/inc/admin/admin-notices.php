<?php
/**
 * Admin notices hooks.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add admin notices.
 *
 * @return void
 */
function the7_add_admin_notices() {
	global $current_screen;

	if (
		function_exists( 'optionsframework_get_options_files' )
		&& optionsframework_get_options_files( $current_screen->parent_base )
		&& ! get_option( 'presscore_less_css_is_writable', 1 )
	) {
		the7_admin_notices()->add( 'unable-to-write-css', 'the7_cannot_write_css_notice', 'updated' );
	}

	if ( ! The7_Admin_Dashboard_Settings::get( 'critical-alerts' ) ) {
		the7_admin_notices()->add(
			'turn-on-critical-alerts',
			'the7_suggest_to_turn_on_critical_alerts_notice',
			'the7-dashboard-notice notice-warning is-dismissible'
		);
	}

	if ( function_exists( 'pro_elements_load_plugin' ) && ! function_exists( 'the7_pro_elements_obsolete' ) && current_user_can( 'update_plugins' ) ) {
		the7_admin_notices()->add(
			'the7_pro_elements_obsolete',
			'the7_notice_about_pro_elements_obsolescence',
			'the7-dashboard-notice notice-warning'
		);
	}

	if (
		the7_elementor_is_active()
		&&
		(
			get_option( 'elementor_experiment-e_optimized_css_loading' ) === 'active'
			||
			get_option( 'elementor_experiment-additional_custom_breakpoints' ) === 'active'
		)
	) {
		the7_admin_notices()->add(
			'the7_elementor_unreliable_experiments',
			function () {
				echo '<p>';
				esc_html_e(
					'Recommendation: deactivate “Additional Custom Breakpoints” and “Improved CSS Loading” Elementor features due to unreliable functioning.',
					'the7mk2'
				);
				echo '&nbsp;';
				printf(
					'<a href="%s" target="_blank">%s</a>',
					esc_url(
						get_admin_url(
							null,
							'admin.php?page=elementor#tab-experiments'
						)
					),
					esc_html( __( 'Elementor settings', 'the7mk2' ) )
				);
				echo '</p>';
			},
			'the7-dashboard-notice notice-warning is-dismissible'
		);
	}

	$screen = get_current_screen();

	if ( $screen && $screen->base === 'toplevel_page_the7-dashboard' && presscore_theme_is_activated() ) {
		the7_admin_notices()->add(
			'the7_show_registration_splash_screen',
			function () {
				require __DIR__ . '/screens/partials/the7-dashboard/registration-splash-screen.php';
			},
			'the7-dashboard-notice updated is-dismissible the7-hide'
		);
	}
}

add_action( 'admin_notices', 'the7_add_admin_notices' );


/**
 * Print admin notice about not writable uploads folder.
 *
 * @return void
 */
function the7_cannot_write_css_notice() {
	echo '<p>';
	echo esc_html_x( 'Failed to create customization .CSS file. To improve your site performance, please check whether ".../wp-content/uploads/" folder is created, and its CHMOD is set to 755.', 'admin', 'the7mk2' );
	echo '</p>';
}

/**
 * Print admin that suggest to turn on critical alerts.
 *
 * @return void
 */
function the7_suggest_to_turn_on_critical_alerts_notice() {
	echo '<p>';
	/* translators: %s: admin page url */
	$msg = _x(
		'Hey, we\'ve noticed that you have "allow to send critical alerts by email" options disabled.<br>
		It is strongly recommended to keep this option enabled (in case of a critical bug, security issue, etc.). <a href="%s">Click here to enable it.</a><br>
		Note that we do not collect your email or other personal data and never spam.<br>
		You can always change this setting under The7 > My The7, in the "Settings" box.',
		'admin',
		'the7mk2'
	);
	$url = wp_nonce_url(
		admin_url( 'admin.php?page=the7-dashboard&the7_dashboard_settings[critical-alerts]=true' ),
		The7_Admin_Dashboard::UPDATE_DASHBOARD_SETTINGS_NONCE_ACTION
	);
	echo wp_kses_post( sprintf( $msg, $url ) );
	echo '</p>';
}

/**
 * Print admin message about pro elements plugin absoletion.
 */
function the7_notice_about_pro_elements_obsolescence() {
	$message = sprintf(
		__(
			'<strong>Important notice</strong>: PRO Elements plugin is obsolete. All its features were transferred into The7 Elements plugin. 
We recommend you <a href="%s">install and activate</a> The7 Elements and remove the PRO Elements plugin.',
			'the7mk2'
		),
		admin_url( 'admin.php?page=the7-plugins' )
	);

	echo '<p>' . wp_kses_post( $message ) . '</p>';
}

/**
 * Enqueue admin notices scripts.
 */
function the7_admin_notices_scripts() {
	the7_register_script( 'the7-admin-notices', PRESSCORE_ADMIN_URI . '/assets/js/admin-notices', array( 'jquery' ), false, true );

	wp_enqueue_script( 'the7-admin-notices' );
	wp_localize_script( 'the7-admin-notices', 'the7Notices', array( '_ajax_nonce' => the7_admin_notices()->get_nonce() ) );
}

/**
 * Main function to handle custom admin notices. Adds action handlers.
 */
function the7_admin_notices_bootstrap() {
	$notices = the7_admin_notices();

	add_action( 'admin_enqueue_scripts', 'the7_admin_notices_scripts', 9999 );
	add_action( 'wp_ajax_the7-dismiss-admin-notice', array( $notices, 'dismiss_notices' ) );
	add_action( 'admin_notices', array( $notices, 'print_admin_notices' ), 40 );
}
add_action( 'admin_init', 'the7_admin_notices_bootstrap' );
