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
    $this->setting_id = 'ct-theme';

    add_filter('codetot_settings_tabs', array($this, 'woocommerce_settings_tab'));
    add_filter('rwmb_meta_boxes', array($this, 'register_fields'));
    add_filter('codetot_layout_settings', array($this, 'woocommerce_sidebar_settings'));
    add_filter('codetot_layout_settings_column', function () {
      return 4;
    });
  }

  public function woocommerce_settings_tab($tabs)
  {
    $tabs['woocommerce'] = __('WooCommerce', 'ct-theme');

    return $tabs;
  }

  public function register_fields($meta_boxes)
  {
    $default_fields = array(
      array(
        'type' => 'switch',
        'name' => __('Quick View', 'ct-theme'),
        'id'   => $this->prefix . 'woocommerce_enable_quick_view',
        'std'  => 1,
        'desc' => __('Display a quick view popup when click on product in listing page.', 'ct-theme')
      ),
      array(
        'type' => 'switch',
        'name' => __('Minicart', 'ct-theme'),
        'id'   => $this->prefix . 'woocommerce_enable_minicart',
        'std'  => 1,
        'desc' => __('When adding product in listing page, display a minicart.', 'ct-theme')
      ),
      array(
        'type' => 'switch',
        'name' => __('Login Popup', 'ct-theme'),
        'id'   => $this->prefix . 'woocommerce_login_popup',
        'std'  => 1,
        'desc' => __('Display a login popup instead of login page.', 'ct-theme')
      ),
      array(
        'type' => 'select',
        'name' => __('Product Card Style', 'ct-theme'),
        'id'   => $this->prefix . 'woocommerce_product_card_style',
        'std'  => 0,
        'options' => [
          0 => __('Style Default', 'ct-theme'),
          1 => __('1', 'ct-theme'),
          2 => __('2', 'ct-theme'),
          3 => __('3', 'ct-theme'),
          4 => __('4', 'ct-theme'),
          5 => __('5', 'ct-theme'),
        ]
      ),
      array(
        'type' => 'select',
        'name' => __('Product Image Visible', 'ct-theme'),
        'id'   => $this->prefix . 'woocommerce_product_image_visible',
        'std'  => 'cover',
        'options' => [
          'cover' => __('Cover', 'ct-theme'),
          'contain' => __('Contain', 'ct-theme'),
        ]
      ),
      array(
        'type' => 'switch',
        'name' => __('Floating Product Bar', 'ct-theme'),
        'desc' => __('Display a floating product bar when visiting a product page with price and add to cart button.', 'ct-theme'),
        'id'   => $this->prefix . 'woocommerce_enable_floating_product_bar',
        'std'  => 1
      ),
      array(
        'type' => 'switch',
        'name' => __('Admin: Hide sticky bar when editing products', 'ct-theme'),
        'desc' => __('Display a floating product bar when visiting a product page with price and add to cart button.', 'ct-theme'),
        'id'   => $this->prefix . 'woocommerce_hide_sticky_bar_editing_products',
        'std'  => 1
      ),
      array(
        'type' => 'switch',
        'name' => __('Facebook Comment', 'ct-theme'),
        'desc' => __('Display a Facebook comment in each product page', 'ct-theme'),
        'id'   => $this->prefix . 'woocommerce_enable_facebook_comment',
        'std'  => 0
      ),
      array(
        'type' => 'switch',
        'name' => __('Product Star Rating in List', 'ct-theme'),
        'desc' => __('Display product star rating in all list.', 'ct-theme'),
        'id'   => $this->prefix . 'woocommerce_enable_product_star_rating_in_list',
        'std'  => 0
      ),
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
