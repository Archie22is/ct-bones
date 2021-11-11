<?php
// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CT_Gutenberg_Init {
	/**
	 * Singleton instance
	 *
	 * @var CT_Gutenberg_Init
	 */
	private static $instance;

	/**
	 * @var array|false|string
	 */
	private $theme_version;

	/**
	 * Get singleton instance.
	 *
	 * @return CT_Gutenberg_Init
	 */
	final public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Class constructor
	 */
	public function __construct() {
		 $this->theme_version = $this->is_localhost() ? substr( sha1( rand() ), 0, 6 ) : CODETOT_VERSION;

		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
	}

	function theme_supports() {
		add_theme_support( 'editor-color-palette', $this->load_color_palette() );
		add_theme_support( 'editor-font-sizes', $this->load_font_sizes() );
		add_theme_support( 'block-templates' );

		// Custom CSS and JS
		add_theme_support( 'editor-styles' );
		add_action( 'enqueue_block_editor_assets', array( $this, 'load_editor_assets' ) );
	}

	function load_color_palette() {
		return wp_parse_args( ct_bones_get_color_schemas(), ct_bones_get_default_colors() );
	}

	function load_font_sizes() {
		$config        = ct_bones_get_font_scales();
		$output_scales = array();

		foreach ( $config as $slug => $value ) {
			$output_scales[] = array(
				'name' => esc_attr( $slug ),
				'slug' => str_replace( 'h', 'heading', esc_attr( $slug ) ),
				'size' => 16 * number_format( $value, 3, '.', '' ),
			);
		}

		return $output_scales;
	}

	function load_editor_assets() {
		$env = ! $this->is_localhost() ? '.min' : '';

		wp_enqueue_script( 'ct-bones-editor-js', CODETOT_ASSETS_URI . '/js/editor' . $env . '.js', array( 'wp-blocks', 'wp-i18n', 'wp-dom-ready' ), $this->theme_version, true );

		if ( ! $this->is_localhost() ) {
			wp_enqueue_style( 'ct-bones-editor-css', CODETOT_ASSETS_URI . '/css/editor.min.css', array(), $this->theme_version );
		}
	}

	public function is_localhost() {
		return ! empty( $_SERVER['HTTP_X_CODETOT_PARENT_THEME_HEADER'] ) && $_SERVER['HTTP_X_CODETOT_PARENT_THEME_HEADER'] === 'development';
	}
}

CT_Gutenberg_Init::instance();
