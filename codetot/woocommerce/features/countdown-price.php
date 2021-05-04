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
    add_filter('woocommerce_get_price_html', array($this, 'custom_price_html'), 100, 2);
  }

  public function custom_price_html($price, $product)
  {
    $sales_price_from = get_post_meta($product->id, '_sale_price_dates_from', true);
    $sales_price_to   = get_post_meta($product->id, '_sale_price_dates_to', true);

    if (is_singular('product') && $product->is_on_sale() & !empty($sales_price_to)) {
      $sales_price_date_from = !empty($sales_price_from) ? date("j M y", $sales_price_from) : '';
      $sales_price_date_to   = date("j M y", $sales_price_to);

      $labels = array(
        'days' => array(
          'singular' => esc_html__('Day', 'ct-theme'),
          'plural' => esc_html__('Days', 'ct-theme')
        ),
        'hours' => array(
          'singular' => esc_html__('Hour', 'ct-theme'),
          'plural' => esc_html__('Hours', 'ct-theme')
        ),
        'minutes' => array(
          'singular' => esc_html__('Minute', 'ct-theme'),
          'plural' => esc_html__('Minutes', 'ct-theme')
        ),
        'seconds' => array(
          'singular' => esc_html__('Second', 'ct-theme'),
          'plural' => esc_html__('Seconds', 'ct-theme')
        ),
        'message' => array(
          'expired' => esc_html__('The sale has ended.', 'ct-theme'),
          'less_day' => esc_html__('The sale will end after less than a day.', 'ct-theme'),
          'less_hour' => esc_html__('Hurry up! The sale will end after less than a hour.', 'ct-theme')
        )
      );

      $attributes = 'data-woocommerce-block="countdown-price"';
      if (!empty($sales_price_date_from)) :
        $attributes .= sprintf(' data-start-date="%s"', $sales_price_date_from);
      endif;
      $attributes .= sprintf(' data-end-date="%s"', $sales_price_date_to);
      $attributes .= sprintf(' data-labels=\'%s\'', json_encode($labels));

      $price_output_html = sprintf('<span class="single-product__price single-product__price--has-discount" %s>', $attributes);
      $price_output_html .= apply_filters('woocommerce_get_price', $price);
      $price_output_html .= '<span class="single-product__price__bottom">';
      $price_output_html .= '<span class="single-product__price__label">' . __('Sale ended after', 'ct-theme') . '</span>';
      $price_output_html .= '<span class="single-product__price__countdown js-countdown">';
      $price_output_html .= '<span class="single-product__price__days js-days"></span>';
      $price_output_html .= '<span class="single-product__price__hours js-hours"></span>';
      $price_output_html .= '<span class="single-product__price__minutes js-minutes"></span>';
      $price_output_html .= '<span class="single-product__price__seconds js-seconds"></span>';
      $price_output_html .= '</span>'; // Close .single-product__price__countdown
      $price_output_html .= '<span class="single-product__price__notice js-notice"></span>';
      $price_output_html .= '</span>'; // Close .single-product__price__bottom
      $price_output_html .= '</span>'; // Close .single-product__price--has-discount

      return $price_output_html;
    }

    return apply_filters('woocommerce_get_price', $price);
  }
}

Codetot_WooCommerce_Countdown_Price::instance();
