<?php
/**
 * Backward compatibility with "WPML" WordPress plugin.
 *
 * @see https://wpml.org/
 *
 * @since 4.4 vendors initialization moved to hooks in autoload/vendors.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vc_Vendor_WPML
 *
 * @since 4.9
 */
class Vc_Vendor_WPML {

	/**
	 * Hooks loader.
	 */
	public function load() {
		add_filter( 'vc_object_id', [
			$this,
			'filterMediaId',
		] );

		add_filter( 'vc_basic_grid_filter_query_suppress_filters', '__return_false' );

		add_filter( 'vc_grid_request_url', [
			$this,
			'appendLangToUrlGrid',
		] );

		global $sitepress;
		$action = vc_post_param( 'action' );
		if ( vc_is_page_editable() && 'vc_frontend_load_template' === $action ) {
			// Fix Issue with loading template #135512264670405.
			remove_action( 'wp_loaded', [
				$sitepress,
				'maybe_set_this_lang',
			] );
		}
	}

	/**
	 * Append lang to url for grid.
	 *
	 * @param string $link
	 * @return string
	 */
	public function appendLangToUrlGrid( $link ) {
		global $sitepress;
		if ( is_object( $sitepress ) ) {
			if ( is_string( $link ) && strpos( $link, 'lang' ) === false ) {
				// add langs for vc_inline/vc_editable requests.
				if ( strpos( $link, 'admin-ajax' ) !== false ) {
					return add_query_arg( [ 'lang' => $sitepress->get_current_language() ], $link );
				}
			}
		}

		return $link;
	}

	/**
	 * Filter media id.
	 *
	 * @param int $id
	 * @return mixed|void
	 */
	public function filterMediaId( $id ) {
		return apply_filters( 'wpml_object_id', $id, 'post', true );
	}
}
