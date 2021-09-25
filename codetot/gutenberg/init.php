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
	}

	function load_color_palette() {
		$output_colors = [];
		$colors = codetot_get_color_options();

		foreach ($colors as $color) {
			$output_colors[] = array(
				'name' => esc_html($color['name']),
				'slug' => esc_html($color['id']),
				'color' => esc_html($color['std'])
			);
		}

		return $output_colors;
	}
}

CT_Gutenberg_Init::instance();
