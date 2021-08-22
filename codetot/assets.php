<?php

class Codetot_Assets
{
  /**
   * @var Codetot_Assets
   */
  private static $instance;
  /**
   * @var array|false|string
   */
  private $theme_version;
  /**
   * @var string
   */
  private $theme_environment;
  /**
   * @var array
   */
  private $premium_fonts;

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
    $this->theme_environment = $this->is_localhost() ? '' : '.min';
    $this->premium_fonts = array_keys(codetot_premium_fonts());

    add_action('wp_head', array($this, 'register_pwa_meta'));
    add_action('wp_enqueue_scripts', array($this, 'load_fonts'), 1);
    add_action('wp_enqueue_scripts', array($this, 'load_font_size_scale'));
    add_action('wp_enqueue_scripts', array($this, 'load_frontend_js'), 20);
    add_action('wp_head', array($this, 'output_inline_styles'), 100);

    // Frontend inline css
    add_action('codetot_custom_style_css_before', array($this, 'default_variables_css_inline'));
    add_action('codetot_custom_style_css_variables', array($this, 'custom_color_options_css_inline'));
    add_action('codetot_custom_style_css', array($this, 'custom_font_options_css_inline'));
  }

  public function register_pwa_meta()
  {
    $primary_color = codetot_get_theme_mod('primary_color') ?? '#000';

    echo '<meta name="theme-color" content="' . esc_attr($primary_color). '">';
  }

  public function get_body_font() {
    return  get_global_option('codetot_font_family') ?? 'Averta';
  }

  public function get_heading_font() {
    return  get_global_option('codetot_font_heading') ?? 'Averta';
  }

  public function load_fonts()
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

  function load_font_local_or_google_fonts($font, $type)
  {
    if ($this->is_premium_font($font)) {
      $local_font_css_file = $this->get_local_font_url($font);
      $local_font_css_inline = file_exists($local_font_css_file) ? file_get_contents($local_font_css_file) : '';

      if (!empty($local_font_css_inline)) {
        $this->register_inline_style('codetot-premium-fonts-' . esc_attr($type), $this->update_font_assets_path($local_font_css_inline, $font));
      }
    } else {
      $google_fonts_css_inline = $this->get_google_fonts_css_inline($font);

      $this->register_inline_style('codetot-google-fonts', $google_fonts_css_inline);
    }
  }

  function is_premium_font($font)
  {
    return in_array($font, $this->premium_fonts);
  }

  function get_local_font_url($font)
  {
    $font_path = $this->update_local_font_url($font);

    return get_template_directory() . '/dynamic-assets/fonts/' . esc_attr($font_path) . '/font.css';
  }

  function update_local_font_url($font_name)
  {
    return strtolower(str_replace(' ', '-', $font_name));
  }

  function get_google_fonts_css_inline($font)
  {
    $font_path = $this->update_google_font_url($font);

    return "@import url('https://fonts.googleapis.com/css?family=" . esc_attr($font_path) . ":wght@300;400;500;600;700&display=swap');";
  }

  function update_google_font_url($font_name)
  {
    return str_replace(' ', '+', $font_name);
  }

  public function load_font_size_scale()
  {
    $font_size_scale = get_global_option('codetot_font_size_scale') ?? '1200';

    wp_enqueue_style(
      'codetot-typography-style',
      get_template_directory_uri() . '/dynamic-assets/typography-style/' . $font_size_scale . '.css',
      [],
      CODETOT_VERSION
    );
  }

  public function load_frontend_js()
  {
    if (is_singular() && comments_open() && get_option('thread_comments')) {
      wp_enqueue_script('comment-reply');
    }

    $locale_settings = array(
      'ajax' => array(
        'restUrl' => get_rest_url(null, 'codetot/v1'),
        'url' => admin_url('admin-ajax.php'),
        "ajax_error" => __('Sorry, something went wrong. Please refresh this page and try again!', 'ct-bones'),
        'nonce' => wp_create_nonce('codetot-config-nonce')
      ),
      'themePath' => get_template_directory_uri()
    );

    wp_enqueue_script(
      'codetot-global-script',
      get_template_directory_uri() . '/assets/js/global' . $this->theme_environment . '.js',
      ['jquery'],
      $this->theme_version,
      true
    );

    wp_localize_script('codetot-global-script', 'codetotConfig', $locale_settings);
  }

  public function is_localhost()
  {
    return !empty($_SERVER['HTTP_X_CODETOT_HEADER']) && $_SERVER['HTTP_X_CODETOT_HEADER'] === 'development';
  }

  public function load_custom_color_options()
  {
    // TODO: Write to new settings.css file to cache.
    $lines = [];
    $keys = apply_filters('codetot_custom_color_options', [
      'primary' => 'primary_color',
      'secondary' => 'secondary_color',
      'body-text-color' => 'base_color',
      'dark' => 'dark_color',
      'gray' => 'gray_color',
      'light' => 'light_color',
    ]);

    foreach ($keys as $key => $field_name) {
      $value = codetot_get_theme_mod($field_name);
      if (!empty($value)) {
        $lines[] = sprintf('--%s: %s;', $key, $value);
      }
    }

    return $lines;
  }

  public function default_variables_css_inline()
  {
    $variables_file = get_stylesheet_directory() . '/variables.css';
    $file_content = file_exists($variables_file) ? file_get_contents($variables_file) : '';

    if (!empty($file_content)) {
      echo $this->filter_css_variables($file_content);
    } else {
      echo '/** No variables.css found **/';
    }
  }

  public function filter_css_variables($context) {
    $context = preg_replace('/@custom-media(.*);/', '', $context);
    $context = preg_replace('/\s+/', '', $context);

    return $context;
  }

  public function custom_color_options_css_inline()
  {
    $variables_rows = $this->load_custom_color_options();
    echo implode('', $variables_rows);
  }

  public function custom_font_options_css_inline()
  {
    $body_font = $this->get_body_font();
    $heading_font = $this->get_heading_font();

    if (!empty($body_font)) {
      echo 'body{font-family: ' . esc_attr($body_font) . ', sans-serif;}';
    }
    if (!empty($heading_font)) {
      echo 'h1,h2,h3,h4,h5,h6{font-family: ' . esc_attr($heading_font) . ', sans-serif;}';
    }
  }

  public function output_inline_styles()
  {
    echo '<style id="codetot-custom-styles">';
    do_action('codetot_custom_style_css_before');
    echo ':root{';
    do_action('codetot_custom_style_css_variables');
    echo '}' . PHP_EOL;
    do_action('codetot_custom_style_css');
    echo '</style>';
  }

  /**
   * @param $id
   * @param $content
   * @return bool
   */
  public function register_inline_style($id, $content)
  {
    if (empty($content)) {
      return;
    }

    wp_register_style($id, false);
    wp_enqueue_style($id);
    return wp_add_inline_style($id, $this->minify_inline_css($content));
  }

  public function update_font_assets_path($content, $font)
  {
    $font_path = $this->update_local_font_url($font);

    return str_replace('url(\'', 'url(\'' . get_template_directory_uri() . '/dynamic-assets/fonts/' . $font_path . '/', $content);
  }

  public function minify_inline_css($content)
  {
    $minified = str_replace("\n", "", $content);
    $minified = str_replace("  ", " ", $minified);
    $minified = str_replace("  ", " ", $minified);
    $minified = str_replace(" {", "{", $minified);
    $minified = str_replace("{ ", "{", $minified);
    $minified = str_replace(" }", "}", $minified);
    $minified = str_replace("} ", "}", $minified);
    $minified = str_replace(", ", ",", $minified);
    $minified = str_replace("; ", ";", $minified);

    return str_replace(": ", ":", $minified);
  }
}

Codetot_Assets::instance();
