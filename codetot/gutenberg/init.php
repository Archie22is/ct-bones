<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

class CT_Gutenberg_Init {
	/**
	 * Singleton instance
	 *
	 * @var CT_Gutenberg_Init
	 */
	private static $instance;

	/**
	 * Get singleton instance.
	 *
	 * @return CT_Gutenberg_Init
	 */
	public final static function instance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		add_action('after_setup_theme', array($this, 'theme_supports'));
	}

	function theme_supports() {
		add_theme_support( 'editor-color-palette', $this->load_color_palette());
		add_theme_support( 'editor-font-sizes', $this->load_font_sizes() );
		add_theme_support( 'block-templates' );
	}

	function load_color_palette() {
		return wp_parse_args(ct_bones_get_color_schemas(), ct_bones_get_default_colors());
	}

	function load_font_sizes() {
		$config = ct_bones_get_font_scales();
		$output_scales = [];

		foreach ($config as $slug => $value) {
			$output_scales[] = array(
				'name' => esc_attr($slug),
				'slug' => str_replace('h', 'heading', esc_attr($slug)),
				'size' => 16 * number_format($value, 3, '.', '')
			);
		}

		return $output_scales;
	}
}

CT_Gutenberg_Init::instance();
