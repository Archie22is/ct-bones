<?php

class Codetot_WooCommerce_Init {
  /**
   * @var Codetot_WooCommerce_Init
   */
  private static $instance;
  /**
   * @var string
   */
  private $theme_version;
  /**
   * @var string
   */
  private $theme_environment;

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

    add_action('after_setup_theme', array($this, 'woocommerce_support'));
    add_action('widgets_init', array($this, 'register_woocommerce_sidebars'));

    add_filter('woocommerce_product_add_to_cart_text', array($this, 'update_add_to_cart_button_text'));

    add_filter('woocommerce_breadcrumb_defaults', array($this, 'breadcrumbs_container'));
    add_filter('woocommerce_get_breadcrumb', array($this, 'woocommerce_breadcrumb'), 10, 2);
    add_action('pre_get_posts', array($this, 'search_product_only'));

    add_filter('woocommerce_reviews_title', array($this, 'change_review_title'));
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');

    add_action('wp_enqueue_scripts', array($this, 'load_woocommerce_css'), 90);
    add_action('wp_enqueue_scripts', array($this, 'load_woocommerce_js'), 91);
    add_action('wp_enqueue_scripts', array($this, 'plugin_assets'));

    add_action('wp_footer', array($this, 'fix_load_country_edit_address'), 90);
    add_filter('body_class', array($this, 'body_class'));

    add_filter('woocommerce_cart_item_remove_link', array($this, 'replace_cart_remove_icon'), 10, 2 );
  }

  public function woocommerce_support() {
    add_theme_support(
      'woocommerce',
      array(
        'thumbnail_image_width' => 150,
        'single_image_width'    => 300,
        'product_grid'          => array(
          'default_rows'    => 3,
          'min_rows'        => 1,
          'default_columns' => 3,
          'min_columns'     => 3,
          'max_columns'     => 5,
        ),
      )
    );
  }

  public function register_woocommerce_sidebars() {
    register_sidebar(
      array(
        'id' => 'shop-sidebar',
        'name' => __('Shop Sidebar', 'ct-bones'),
        'before_widget' => '<div id="%1$s" class="widget widget--shop %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<p class="widget__title">',
        'after_title' => '</p>'
      )
    );

    register_sidebar(array(
      'name' => __('Product Sidebar', 'ct-bones'),
      'id' => 'product-sidebar',
      'before_widget' => '<aside id="%1$s" class="widget widget--product %2$s"><div class="widget__inner">',
      'after_widget' => '</div></aside>',
      'before_title' => '<p class="widget__title">',
      'after_title' => '</p>'
    ));

    register_sidebar(array(
      'name' => __('Top Product Sidebar', 'ct-bones'),
      'id' => 'top-product-sidebar',
      'before_widget' => '<aside id="%1$s" class="widget widget--top-product %2$s"><div class="widget__inner">',
      'after_widget' => '</div></aside>',
      'before_title' => '<p class="widget__title">',
      'after_title' => '</p>'
    ));

    register_sidebar(array(
      'name' => __('Bottom Product Sidebar', 'ct-bones'),
      'id' => 'bottom-product-sidebar',
      'before_widget' => '<aside id="%1$s" class="widget widget--bottom-product %2$s"><div class="widget__inner">',
      'after_widget' => '</div></aside>',
      'before_title' => '<p class="widget__title">',
      'after_title' => '</p>'
    ));

    register_sidebar(array(
      'name' => __('Product Category Sidebar', 'ct-bones'),
      'id' => 'product-category-sidebar',
      'before_widget' => '<aside id="%1$s" class="widget widget--product-category %2$s"><div class="widget__inner">',
      'after_widget' => '</div></aside>',
      'before_title' => '<p class="widget__title">',
      'after_title' => '</p>'
    ));
  }

  public function update_add_to_cart_button_text($text) {
    global $product;

    if ($product->is_type( 'variable' )) {
      $variations = $product->get_available_variations();

      if (!empty($variations) && count($variations) > 1) {
        return esc_html__('View product', 'ct-bones');
      } else {
        return esc_html__('Add to cart', 'woocommerce');
      }
    }

    return $text;
  }

  public function fix_load_country_edit_address() {
    if (is_account_page()) :
    ?>
    <script>
    (function($){
      var $country = $('select[name="billing_country"]')

      if ($country.length) {
        $country.select2()
      }
    })(jQuery);
    </script>
    <?php endif;
  }

  public function load_woocommerce_css() {
    wp_enqueue_style('codetot-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce-style' . $this->theme_environment . '.css', array(), CODETOT_VERSION);
  }

  public function load_woocommerce_js() {
    wp_enqueue_script('wc-add-to-cart-variation');
    wp_enqueue_script(
      'codetot-woocommerce',
      get_template_directory_uri() . '/assets/js/woocommerce-script' . $this->theme_environment . '.js',
      array(),
      CODETOT_VERSION,
      true
    );
  }

  public function breadcrumbs_container($args) {
    $args['wrap_before'] = '<div class="breadcrumbs breadcrumbs--woocommerce"><div class="container breadcrumbs__container"><div class="breadcrumbs__list">';
    $args['wrap_after'] = '</div></div></div>';

    return $args;
  }

  public function search_product_only($query)
  {
    if (apply_filters('codetot_is_search_product_only', true) === true &&  !is_admin() && $query->is_main_query() && $query->is_search) {
      $query->set('post_type', 'product');
    }
  }

  public function woocommerce_breadcrumb($crumbs, $Breadcrumb){
    $shop_page_id = wc_get_page_id('shop');
    if($shop_page_id > 0 && !is_shop()) {
        $new_breadcrumb = [
            _x( 'Shop', 'breadcrumb', 'woocommerce' ), //Title
            get_permalink(wc_get_page_id('shop')) // URL
        ];
        array_splice($crumbs, 1, 0, [$new_breadcrumb]);
    }
    return $crumbs;
  }

  public function change_review_title() {
    return esc_html__( 'Reviews', 'woocommerce' );
  }

  public function body_class($classes) {
    $product_card_style = get_global_option('codetot_woocommerce_product_card_style') ?? 1;

    $classes[] = 'has-product-card-style-' . esc_attr($product_card_style);

    return $classes;
  }

  public function plugin_assets() {
    if (is_plugin_active('woocommerce-products-filter/index.php')) {
      wp_enqueue_style('codetot-woof', get_template_directory_uri() . '/dynamic-assets/plugins/woof.css', array(), '1.0.0');
    }
  }

  public function replace_cart_remove_icon($html, $cart_item_key) {
    return str_replace('&times;', esc_html__('Remove', 'woocommerce'), $html);
  }

  public function is_localhost()
  {
    return !empty($_SERVER['HTTP_X_CODETOT_HEADER']) && $_SERVER['HTTP_X_CODETOT_HEADER'] === 'development';
  }
}

Codetot_WooCommerce_Init::instance();

