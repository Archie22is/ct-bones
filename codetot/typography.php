<?php

class Codetot_Theme_Typography {
  /**
   * @var Codetot_Assets
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Assets
   */
  public final static function instance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function __construct()
  {
    $this->theme_version = $this->is_localhost() ? substr(sha1(rand()), 0, 6) : CODETOT_VERSION;
    $this->premium_fonts = array_keys(codetot_premium_fonts());

		add_action('wp_enqueue_scripts', array($this, 'load_fonts_assets'), 1);

		// Load CSS inline
		add_action('codetot_custom_style_css', array($this, 'custom_font_options_css_inline'));
  }

  public function get_body_font() {
    return codetot_get_theme_mod('body_font') ?? 'Averta';
  }

  public function get_heading_font() {
    return codetot_get_theme_mod('heading_font') ?? 'Averta';
  }

  public function is_premium_font($font)
  {
    return in_array($font, $this->premium_fonts);
  }

  public function load_font_local_or_google_fonts($font, $type)
  {
    if ($this->is_premium_font($font)) {
      $local_font_css_file = ct_bones_get_local_font_url($font);
      $local_font_css_inline = file_exists($local_font_css_file) ? file_get_contents($local_font_css_file) : '';

      if (!empty($local_font_css_inline)) {
        ct_bones_register_inline_style('ct-bones-fonts-' . esc_attr($type), ct_bones_format_font_assets_path($local_font_css_inline, $font));
      }
    } else {
      $google_fonts_css_inline = ct_bones_get_google_fonts_css_inline($font);

      ct_bones_register_inline_style('ct-bones-fonts', $google_fonts_css_inline);
    }
  }

  public function load_fonts_assets()
  {
    $body_font = $this->get_body_font();
    $heading_font = $this->get_heading_font();

    if (empty($body_font) && empty($heading_font)) {
      return;
    }

    if ($body_font == $heading_font) {
      $this->load_font_local_or_google_fonts($body_font, 'body');
    } else {
      $this->load_font_local_or_google_fonts($body_font, 'body');
      $this->load_font_local_or_google_fonts($heading_font, 'heading');
    }
  }

  public function custom_font_options_css_inline()
  {
    $body_font = $this->get_body_font();
    $heading_font = $this->get_heading_font();

    if (!empty($body_font)) {
      echo 'body {font-family: "' . esc_attr($body_font) . '", Arial, Helvetica, sans-serif;}';
    }
    if (!empty($heading_font)) {
      echo 'h1, h2, h3, h4, h5, h6{font-family: ' . esc_attr($heading_font) . ', sans-serif;}';
    }
  }

  public function is_localhost()
  {
    return !empty($_SERVER['HTTP_X_CODETOT_PARENT_THEME_HEADER']) && $_SERVER['HTTP_X_CODETOT_PARENT_THEME_HEADER'] === 'development';
  }
}

Codetot_Theme_Typography::instance();
