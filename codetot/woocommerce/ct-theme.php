<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_CT_Theme_WooCommerce_Settings
{
  /**
   * Singleton instance
   *
   * @var Codetot_CT_Theme_WooCommerce_Settings
   */
  private static $instance;
  /**
   * @var string
   */
  private $prefix;
  /**
   * @var string
   */
  private $filter_prefix;
  /**
   * @var string
   */
  private $setting_id;
  /**
   * Get singleton instance.
   *
   * @return Codetot_CT_Theme_WooCommerce_Settings
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
    $this->prefix = 'codetot_';
    $this->filter_prefix = 'codetot_settings_';
    $this->setting_id = 'ct-bones';

    add_filter('codetot_settings_tabs', array($this, 'woocommerce_settings_tab'));
    add_filter('rwmb_meta_boxes', array($this, 'register_fields'));
    add_filter('codetot_layout_settings', array($this, 'woocommerce_sidebar_settings'));
    add_filter('codetot_layout_settings_column', function () {
      return 4;
    });
  }

  public function woocommerce_settings_tab($tabs)
  {
    $tabs['woocommerce'] = __('WooCommerce', 'ct-bones');

    return $tabs;
  }

  public function register_fields($meta_boxes)
  {
    $default_fields = array(
      array(
        'type' => 'switch',
        'name' => __('Quick View', 'ct-bones'),
        'id'   => $this->prefix . 'woocommerce_enable_quick_view',
        'std'  => 1,
        'desc' => __('Display a quick view popup when click on product in listing page.', 'ct-bones')
      ),
      array(
        'type' => 'switch',
        'name' => __('Minicart', 'ct-bones'),
        'id'   => $this->prefix . 'woocommerce_enable_minicart',
        'std'  => 1,
        'desc' => __('When adding product in listing page, display a minicart.', 'ct-bones')
      ),
      array(
        'type' => 'switch',
        'name' => __('Login Popup', 'ct-bones'),
        'id'   => $this->prefix . 'woocommerce_login_popup',
        'std'  => 1,
        'desc' => __('Display a login popup instead of login page.', 'ct-bones')
      ),
      array(
        'type' => 'select',
        'name' => __('Product Card Style', 'ct-bones'),
        'id'   => $this->prefix . 'woocommerce_product_card_style',
        'std'  => 0,
        'options' => [
          0 => __('Default', 'ct-bones'),
          1 => 1,
          2 => 2,
          3 => 3,
          4 => 4,
          5 => 5,
        ]
      ),
      array(
        'type' => 'select',
        'name' => __('Product Image Visible', 'ct-bones'),
        'id'   => $this->prefix . 'woocommerce_product_image_visible',
        'std'  => 'cover',
        'options' => [
          'cover' => __('Cover', 'ct-bones'),
          'contain' => __('Contain', 'ct-bones'),
        ]
      ),
      array(
        'type' => 'switch',
        'name' => __('Floating Product Bar', 'ct-bones'),
        'desc' => __('Display a floating product bar when visiting a product page with price and add to cart button.', 'ct-bones'),
        'id'   => $this->prefix . 'woocommerce_enable_floating_product_bar',
        'std'  => 1
      ),
      array(
        'type' => 'switch',
        'name' => __('Admin: Hide sticky bar when editing products', 'ct-bones'),
        'desc' => __('Display a floating product bar when visiting a product page with price and add to cart button.', 'ct-bones'),
        'id'   => $this->prefix . 'woocommerce_hide_sticky_bar_editing_products',
        'std'  => 1
      ),
      array(
        'type' => 'switch',
        'name' => __('Facebook Comment', 'ct-bones'),
        'desc' => __('Display a Facebook comment in each product page', 'ct-bones'),
        'id'   => $this->prefix . 'woocommerce_enable_facebook_comment',
        'std'  => 0
      ),
      array(
        'type' => 'switch',
        'name' => __('Product Star Rating in List', 'ct-bones'),
        'desc' => __('Display product star rating in all list.', 'ct-bones'),
        'id'   => $this->prefix . 'woocommerce_enable_product_star_rating_in_list',
        'std'  => 0
      ),
      array(
        'type' => 'switch',
        'name' => __('Countdown Product Price', 'ct-bones'),
        'desc' => __('Display countdown in single product page if price has been scheduled.', 'ct-bones'),
        'id'   => $this->prefix . 'woocommerce_enable_countdown_price',
        'std'  => 0
      )
    );

    $meta_boxes[] = [
      'title'          => esc_html__('WooCommerce', 'woocommerce'),
      'id'             => 'ct-theme-woocommerce-settings',
      'settings_pages' => [$this->setting_id],
      'tab'            => 'woocommerce',
      'fields'         => apply_filters(
        $this->filter_prefix . 'woocommerce_fields',
        $default_fields
      ),
    ];

    return $meta_boxes;
  }

  public function woocommerce_sidebar_settings($layouts)
  {
    return array_merge($layouts, array(
      'Product',
      'Shop',
      'Product Category'
    ));
  }
}

Codetot_CT_Theme_WooCommerce_Settings::instance();
