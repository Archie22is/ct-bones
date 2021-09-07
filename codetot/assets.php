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

    add_action('wp_head', array($this, 'register_pwa_meta'));
    add_action('wp_enqueue_scripts', array($this, 'load_frontend_js'), 20);
    add_action('wp_head', array($this, 'output_inline_styles'), 100);

    // Frontend inline css
    add_action('codetot_custom_style_css_before', array($this, 'default_variables_css_inline'));
    add_action('codetot_custom_style_css_variables', array($this, 'custom_color_options_css_inline'));
  }

  public function register_pwa_meta()
  {
    $primary_color = codetot_get_theme_mod('primary_color') ?? '#000';

    echo '<meta name="theme-color" content="' . esc_attr($primary_color). '">';
  }

  public function load_frontend_js()
  {
    if (is_singular() && comments_open() && get_option('thread_comments')) {
      wp_enqueue_script('comment-reply');
    }

    if (!wp_script_is('lazysizes', 'enqueued')) {
      wp_register_script('lazysizes', get_template_directory_uri() . '/assets/vendors/lazysizes.min.js', array(), '5.2.2', false);
      wp_enqueue_script('lazysizes');
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

    wp_register_script(
      'codetot-global-script',
      get_template_directory_uri() . '/assets/js/frontend' . $this->theme_environment . '.js',
      ['jquery'],
      $this->theme_version,
      true
    );

    wp_localize_script('codetot-global-script', 'codetotConfig', $locale_settings);
    wp_enqueue_script('codetot-global-script');
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
      echo ct_bones_filter_css_variables($file_content);
    } else {
      echo '/** No variables.css found **/';
    }
  }

  public function custom_color_options_css_inline()
  {
    $variables_rows = $this->load_custom_color_options();
    echo implode('', $variables_rows);
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

	public function is_localhost()
  {
    return !empty($_SERVER['HTTP_X_CODETOT_PARENT_THEME_HEADER']) && $_SERVER['HTTP_X_CODETOT_PARENT_THEME_HEADER'] === 'development';
  }
}

Codetot_Assets::instance();
