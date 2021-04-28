<?php

class Codetot_WooCommerce_Init {
  /**
   * @var Codetot_WooCommerce_Init
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
   * @return Codetot_WooCommerce_Init
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
    $this->theme_version = $this->is_localhost() ? substr(sha1(rand()), 0, 6) : wp_get_theme()->get('Version');
    $this->theme_environment = $this->is_localhost() ? '' : '.min';

    add_action('widgets_init', array($this, 'register_woocommerce_sidebars'));
    add_action('wp_enqueue_scripts', array($this, 'load_woocommerce_css'), 90);
  }

  public function register_woocommerce_sidebars() {
    register_sidebar(
      array(
        'id' => 'shop-sidebar',
        'name' => __('Shop Sidebar', 'ct-theme'),
        'before_widget' => '<div id="%1$s" class="widget widget--shop %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<p class="widget__title">',
        'after_title' => '</p>'
      )
    );

    register_sidebar(array(
      'name' => __('Product Sidebar', 'ct-theme'),
      'id' => 'product-sidebar',
      'before_widget' => '<aside id="%1$s" class="widget widget--product %2$s"><div class="widget__inner">',
      'after_widget' => '</div></aside>',
      'before_title' => '<p class="widget__title">',
      'after_title' => '</p>'
    ));

    register_sidebar(array(
      'name' => __('Product Category Sidebar', 'ct-theme'),
      'id' => 'product-category-sidebar',
      'before_widget' => '<aside id="%1$s" class="widget widget--product-category %2$s"><div class="widget__inner">',
      'after_widget' => '</div></aside>',
      'before_title' => '<p class="widget__title">',
      'after_title' => '</p>'
    ));
  }

  public function load_woocommerce_css() {
    wp_enqueue_style('codetot-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce-style' . $this->theme_environment . '.css', array(), CODETOT_VERSION);
  }

  public function ct_bones_load_woocommerce_js() {
    wp_enqueue_script('wc-add-to-cart-variation');
    wp_enqueue_script(
      'codetot-woocommerce',
      get_template_directory_uri() . '/assets/js/woocommerce-script' . $this->theme_environment . '.js',
      array(),
      CODETOT_VERSION,
      true
    );
  }

  public function is_localhost()
  {
    return !empty($_SERVER['HTTP_X_CODETOT_HEADER']) && $_SERVER['HTTP_X_CODETOT_HEADER'] === 'development';
  }
}

Codetot_WooCommerce_Init::instance();

