<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_Back_To_Top
{
  /**
   * Singleton instance
   *
   * @var Codetot_Back_To_Top
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Back_To_Top
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
    $enable = codetot_get_theme_mod('enable_back_to_top', 'pro') ?? true;

    if ($enable) {
      add_action('codetot_footer', 'codetot_render_back_to_top_section', 30);
      add_action('wp_enqueue_scripts', 'codetot_enqueue_back_to_top_assets');
    }
  }
}

function codetot_enqueue_back_to_top_assets() {
  if (!WP_DEBUG) {
    $theme_env = '.min';
  } else {
    $theme_env = '';
  }

  wp_enqueue_style('codetot-back-to-top', get_template_directory_uri() . '/dynamic-assets/blocks/back-to-top' . $theme_env . '.css', array(), '1.0.0');
  wp_enqueue_script('codetot-back-to-top', get_template_directory_uri() . '/dynamic-assets/blocks/back-to-top' . $theme_env . '.js', array(), '1.0.0', true);
}

function codetot_render_back_to_top_section() {
  get_template_part('dynamic-assets/blocks/back-to-top');
}

Codetot_Back_To_Top::instance();
