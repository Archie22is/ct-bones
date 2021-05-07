<?php

// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_WooCommerce_Countdown_Price
{
  /**
   * Singleton instance
   *
   * @var Codetot_WooCommerce_Countdown_Price
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_WooCommerce_Countdown_Price
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
    $enable_countdown_price = get_global_option('codetot_woocommerce_enable_countdown_price') ?? true;

    if ($enable_countdown_price) {
      add_filter('woocommerce_get_price_html', array($this, 'custom_price_html'), 100, 2);
    }
  }

  public function custom_price_html($price, $product)
  {
    $sales_price_from = get_post_meta($product->get_id(), '_sale_price_dates_from', true);
    $sales_price_to   = get_post_meta($product->get_id(), '_sale_price_dates_to', true);

    if (is_singular('product') && !empty($sales_price_to)) {
      $sales_price_date_from = !empty($sales_price_from) ? date("j M y", $sales_price_from) : '';
      $sales_price_date_to   = date("j M y", $sales_price_to);

      $labels = array(
        'days' => array(
          'singular' => esc_html__('Day', 'ct-bones'),
          'plural' => esc_html__('Days', 'ct-bones')
        ),
        'hours' => array(
          'singular' => esc_html__('Hour', 'ct-bones'),
          'plural' => esc_html__('Hours', 'ct-bones')
        ),
        'minutes' => array(
          'singular' => esc_html__('Minute', 'ct-bones'),
          'plural' => esc_html__('Minutes', 'ct-bones')
        ),
        'seconds' => array(
          'singular' => esc_html__('Second', 'ct-bones'),
          'plural' => esc_html__('Seconds', 'ct-bones')
        ),
        'message' => array(
          'ongoing' => esc_html__('Sale ended after', 'ct-bones'),
          'expired' => esc_html__('The sale has ended.', 'ct-bones'),
          'less_day' => esc_html__('The sale will end after less than a day.', 'ct-bones'),
          'less_hour' => esc_html__('Hurry up! The sale will end after less than a hour.', 'ct-bones')
        )
      );

      $attributes = 'data-woocommerce-block="countdown-price"';
      if (!empty($sales_price_date_from)) :
        $attributes .= sprintf(' data-start-date="%s"', $sales_price_date_from);
      endif;
      $attributes .= sprintf(' data-end-date="%s"', $sales_price_date_to);
      $attributes .= sprintf(' data-labels=\'%s\'', json_encode($labels));

      $price_output_html = sprintf('<span class="product-price product-price--countdown" %s>', $attributes);
      $price_output_html .= apply_filters('woocommerce_get_price', $price);
      $price_output_html .= '</span>'; // Close .single-product__price--has-discount

      return $price_output_html;
    }

    return '<span class="product-price">' . apply_filters('woocommerce_get_price', $price) . '</span>';
  }
}

Codetot_WooCommerce_Countdown_Price::instance();
