<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Codetot_WooCommerce_Product_Card_Style {
  /**
   * Singleton instance
   *
   * @var Codetot_WooCommerce_Product_Card_Style
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_WooCommerce_Product_Card_Style
   */
  public final static function instance() {
    if ( is_null( self::$instance ) ) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Class constructor
   */
  private function __construct()
  {
    $this->settings_id = 'codetot_woocommerce_settings';

    add_action('customize_register', array($this, 'register_product_card_settings'));

    add_filter('body_class', array($this, 'update_body_class_product_badge_style_classes'));
    add_filter('body_class', array($this, 'update_body_class_product_badge_position_classes'));

    add_action('wp', array($this, 'update_product_card_hooks'), 20);
  }

  public function register_product_card_settings($wp_customize) {
    $section_settings_id = 'codetot_woocommerce_product_card_settings';

    codetot_customizer_register_section(array(
      'id' => $section_settings_id,
      'label' => esc_html__('Product Card Style', 'ct-bones'),
      'panel' => 'codetot_woocommerce_options',
      'priority' => 40
    ), $wp_customize);

    codetot_customizer_register_control(array(
      'id' => 'product_card_style',
      'label' => esc_html__('Product Card Style', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'setting_args' => array('default' => 'default'),
      'option_type' => $this->settings_id,
      'control_args' => array(
        'type' => 'select',
        'choices' => apply_filters('product_card_style_options', array(
          'style-default' => esc_html__('Default', 'ct-bones'),
          'style-1' => esc_html__('Style 1', 'ct-bones'),
          'style-2' => esc_html__('Style 2', 'ct-bones'),
          'style-3' => esc_html__('Style 3', 'ct-bones'),
          'style-4' => esc_html__('Style 4', 'ct-bones')
        ))
      )
    ), $wp_customize);

    codetot_customizer_register_control(array(
      'id' => 'product_card_image_type',
      'label' => esc_html__('Product Card Image Style', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'setting_args' => array('default' => 'default'),
      'option_type' => $this->settings_id,
      'control_args' => array(
        'type' => 'select',
        'choices' => apply_filters('product_card_image_type_options', array(
          'default' => esc_html__('Default', 'ct-bones'),
          'cover' => esc_html__('Image Cover', 'ct-bones'),
          'contain' => esc_html__('Image Contain', 'ct-bones')
        ))
      )
    ), $wp_customize);

    codetot_customizer_register_control(array(
      'id' => 'product_card_discount_badge_style',
      'label' => esc_html__('Product Card Discount Badge Style', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'setting_args' => array('default' => 'default'),
      'option_type' => $this->settings_id,
      'control_args' => array(
        'type' => 'select',
        'choices' => apply_filters('product_card_discount_badge_style_options', array(
          'default' => esc_html__('Default (Circle)', 'ct-bones'),
          'style-1' => esc_html__('Style 1 (Rectangle - All radius)', 'ct-bones'),
          'style-2' => esc_html__('Style 2 (Rectangle - Radius left)', 'ct-bones'),
          'style-3' => esc_html__('Style 3 (Rectangle - Radius right)', 'ct-bones')
        ))
      )
    ), $wp_customize);

    codetot_customizer_register_control(array(
      'id' => 'product_card_discount_badge_position',
      'label' => esc_html__('Product Card Discount Badge Position', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'setting_args' => array('default' => 'default'),
      'option_type' => $this->settings_id,
      'control_args' => array(
        'type' => 'select',
        'choices' => apply_filters('product_card_discount_badge_position_options', array(
          'default' => esc_html__('Top Right Image', 'ct-bones'),
          'style-1' => esc_html__('Top Left Image', 'ct-bones'),
          'style-2' => esc_html__('After sale price in same row', 'ct-bones'),
          'style-3' => esc_html__('Replace sale price', 'ct-bones'),
          'style-hidden' => esc_html__('Hide discount badge', 'ct-bones')
        ))
      )
    ), $wp_customize);

    codetot_customizer_register_control(array(
      'id' => 'archive_product_star_rating',
      'label' => esc_html__('Enable Product Star Rating', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'option_type' => $this->settings_id,
      'control_args' => array(
        'type' => 'checkbox',
        'description' => esc_html__('Display product star rating in archive and product category even if no star was given. Default is 5 stars.', 'ct-bones')
      )
    ), $wp_customize);

    return $wp_customize;
  }

  public function update_body_class_product_badge_style_classes($classes) {
    $setting = codetot_get_theme_mod('product_card_discount_badge_style', 'woocommerce') ?? 'style-default';

    $classes[] = 'has-product-card-discount-badge-' . sanitize_key($setting);

    return $classes;
  }

  public function update_body_class_product_badge_position_classes($classes) {
    $setting = codetot_get_theme_mod('product_card_discount_badge_position', 'woocommerce') ?? 'style-default';
    $setting = str_replace('style-', 'position-', $setting);

    $classes[] = 'has-product-card-discount-badge-' . sanitize_key($setting);

    return $classes;
  }

  public function update_product_card_hooks()
  {
    global $product;
    $card_style = codetot_get_theme_mod('product_card_style', 'woocommerce') ?? 'style-default';
    $badge_position = codetot_get_theme_mod('product_card_discount_badge_position', 'woocommerce') ?? 'style-default';
    $display_product_star_rating = codetot_get_theme_mod('archive_product_star_rating', 'woocommerce') ?? false;

    if ($display_product_star_rating) {
      add_action('woocommerce_after_shop_loop_item_title', 'codetot_archive_product_product_rating_html', 2);
    }

    // Display on right price or Replace sale price
    if ($badge_position === 'style-3' || $badge_position === 'style-2') {
      remove_action('woocommerce_before_shop_loop_item_title', 'codetot_archive_product_sale_flash_html', 23);
      add_filter('woocommerce_get_price_html', function($price_html) {
        global $product;
        $badge_html = codetot_archive_product_sale_flash_html($product);

        return $price_html . ' ' . $badge_html;
      }, 100, 1);
    }
  }
}

Codetot_WooCommerce_Product_Card_Style::instance();
