<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

/**
 * @link       https://codetot.com
 * @since      1.0.0
 * @package    Codetot_Woocommerce
 * @subpackage Codetot_Woocommerce/includes/layout
 * @author     CODE TOT JSC <khoi@codetot.com>
 */
class Codetot_Woocommerce_Layout_Checkout extends Codetot_Woocommerce_Layout
{
  /**
   * Singleton instance
   *
   * @var Codetot_Woocommerce_Layout_Checkout
   */
  private static $instance;

    /**
   * @var string
   */
  private $theme_environment;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Woocommerce_Layout_Checkout
   */
  public final static function instance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  private function __construct()
  {
    $this->theme_environment = $this->is_localhost() ? '' : '.min';

    // Layout
    remove_action('woocommerce_before_checkout_form_cart_notices', 'woocommerce_output_all_notices', 10);
    add_action('woocommerce_checkout_before_customer_details',  array($this, 'page_block_open'), 10);
    add_action('woocommerce_checkout_after_customer_details', array($this, 'page_block_between'), 40);
    add_action('woocommerce_after_checkout_form', array($this, 'page_block_close'), 90);

    add_filter('woocommerce_default_address_fields', array($this, 'update_fields_order'));
    add_filter('woocommerce_checkout_fields', array($this, 'update_placeholder_fields'));

    if (is_checkout()) {
      add_action('codetot_page', array($this, 'container_open'), 1);
      add_action('codetot_page', array($this, 'checkout_content'), 10);
      add_action('codetot_page', array($this, 'container_close'), 10);
    }

    // Sticky mobile checkout
    add_filter('woocommerce_add_to_cart_fragments', array($this, 'update_fragments'));
    add_filter('woocommerce_after_checkout_form', array($this, 'sticky_mobile_checkout_block'), 100);
  }

  public function checkout_content() {
    the_content();
  }

  public function container_open() {
    echo '<div class="page-block page-block--checkout">';
    echo '<div class="container page-block__container">';
  }

  public function container_close() {
    echo '</div>';
    echo '</div>';
  }

  public function page_block_open() {
    echo '<div class="grid page-block__grid">';
    echo '<div class="grid__col page-block__col page-block__col--main">';
  }

  public function page_block_between() {
    echo '</div>';
    echo '<div class="grid__col page-block__col page-block__col--sidebar">';
  }

  public function page_block_close() {
    echo '</div>';
    echo '</div>';
  }

  public function sticky_mobile_checkout_block() {
    the_block('sticky-mobile-checkout');
  }

  public function update_fields_order($fields) {
    unset($fields['company']);
    unset($fields['address_2']);
    unset($fields['postcode']);

    return $fields;
  }

  public function update_fragments($fragments) {
    ob_start(); ?>
    <span class="sticky-mobile-checkout__value">
      <?php wc_cart_totals_order_total_html(); ?>
    </span>
    <?php
    $sticky_checkout_price = ob_get_clean();

    $fragments['span.sticky-mobile-checkout__value'] = $sticky_checkout_price;

    return $fragments;
  }

  public function update_placeholder_fields( $fields ) {
    $fields['billing']['billing_first_name']['placeholder'] = esc_html__('First name', 'woocommerce');
    $fields['billing']['billing_last_name']['placeholder'] = esc_html__('Last name', 'woocommerce');
    $fields['billing']['billing_phone']['placeholder'] = esc_html__('Phone', 'woocommerce');
    $fields['billing']['billing_city']['placeholder'] = esc_html__('City', 'woocommerce');
    $fields['billing']['billing_email']['placeholder'] = esc_html__('Email', 'woocommerce');

    $fields['shipping']['shipping_first_name']['placeholder'] = esc_html__('First name', 'woocommerce');
    $fields['shipping']['shipping_last_name']['placeholder'] = esc_html__('Last name', 'woocommerce');
    $fields['shipping']['shipping_phone']['placeholder'] = esc_html__('Phone', 'woocommerce');
    $fields['shipping']['shipping_city']['placeholder'] = esc_html__('City', 'woocommerce');

    return $fields;
  }

    /**
   * @return bool
   */
  public function is_localhost()
  {
    return !empty($_SERVER['HTTP_X_CODETOT_CHILD_HEADER']) && $_SERVER['HTTP_X_CODETOT_CHILD_HEADER'] === 'development';
  }
}

Codetot_Woocommerce_Layout_Checkout::instance();
