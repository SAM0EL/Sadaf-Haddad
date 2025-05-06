<?php
/**
 * The7 block theme compatibility class.
 *
 * @since   12.0.0
 * @package The7
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class The7_Block_Theme_Compatibility
 */
class The7_Block_Theme_Compatibility {

	/**
	 * Instance.
	 *
	 * @access public
	 * @static
	 * @var The7_Block_Theme_Compatibility
	 */
	public static $instance = null;

	/**
	 * Instance.
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @return The7_Block_Theme_Compatibility An instance of the class.
	 * @access public
	 * @static
	 */
	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
			self::$instance->bootstrap();
		}

		return self::$instance;
	}

	/**
	 * Bootstrap module.
	 */
	public function bootstrap() {
		if ( the7_is_gutenberg_theme_mode_active() ) {
			add_action( 'init', [ $this, 'add_pattern_categories' ] );
			add_action( 'init', [ $this, 'register_block_styles' ] );
		} else {
			remove_theme_support( 'block-templates' );
		}

		$this->maybe_transform_the7_in_to_block_theme();
	}

	/**
	 * @return void
	 */
	public function register_block_styles() {
		register_block_style(
			'core/button',
			[
				'name'  => 'accent-2',
				'label' => __( 'Fill 2', 'the7mk2' ),
			]
		);
	}

	/**
	 * Register pattern categories
	 *
	 * @return void
	 */
	public function add_pattern_categories() {
		register_block_pattern_category(
			'dt-the7_page',
			[
				'label'       => _x( 'Pages', 'Block pattern category', 'the7mk2' ),
				'description' => _x( 'A collection of full page layouts. ', 'Block pattern category', 'the7mk2' ),
			]
		);
	}

	/**
	 * @return void
	 */
	public function maybe_transform_the7_in_to_block_theme() {
		if ( the7_is_gutenberg_theme_mode_active() && ! wp_is_block_theme() ) {
			$this->copy_block_theme_files();
		} elseif ( ! the7_is_gutenberg_theme_mode_active() && wp_is_block_theme() ) {
			$this->delete_block_theme_files();
		}
	}

	/**
	 * @return bool
	 */
	public function copy_block_theme_files() {
		$filesystem = the7_get_filesystem();
		if ( is_wp_error( $filesystem ) ) {
			return false;
		}

		$dir  = get_template_directory();
		$from = $dir . '/fse';
		copy_dir( $from, $dir );
		wp_get_theme()->cache_delete();

		return true;
	}

	/**
	 * @return bool
	 */
	public function delete_block_theme_files() {
		$filesystem = the7_get_filesystem();
		if ( is_wp_error( $filesystem ) ) {
			return false;
		}

		$dir             = get_template_directory();
		$files_to_remove = [
			'theme.json',
			'templates',
		];
		foreach ( $files_to_remove as $file ) {
			$file_path = $dir . '/' . $file;

			if ( $filesystem->exists( $file_path ) ) {
				$filesystem->delete( $file_path, true );
			}
		}
		wp_get_theme()->cache_delete();

		return true;
	}
}
