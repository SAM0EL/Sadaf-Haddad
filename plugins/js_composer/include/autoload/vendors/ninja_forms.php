<?php
/**
 * Backward compatibility with "Ninja Forms" WordPress plugin.
 *
 * @see https://wordpress.org/plugins/ninja-forms
 *
 * @since 4.4 vendors initialization moved to hooks in autoload/vendors.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

add_action( 'plugins_loaded', 'vc_init_vendor_ninja_forms' );
/**
 * Init Ninja Forms vendor.
 */
function vc_init_vendor_ninja_forms() {
	include_once ABSPATH . 'wp-admin/includes/plugin.php'; // Require class-vc-wxr-parser-plugin.php to use is_plugin_active() below.
	if ( is_plugin_active( 'ninja-forms/ninja-forms.php' ) || defined( 'NINJA_FORMS_DIR' ) || function_exists( 'ninja_forms_get_all_forms' ) ) {
		require_once vc_path_dir( 'VENDORS_DIR', 'plugins/class-vc-vendor-ninja-forms.php' );
		$vendor = new Vc_Vendor_NinjaForms();
		add_action( 'vc_after_set_mode', [
			$vendor,
			'load',
		] );
	}
}
