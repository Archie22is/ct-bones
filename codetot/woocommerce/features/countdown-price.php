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
    $this->start_date_format = 'Y-m-d 00:00:00';
    $this->end_date_format = 'Y-m-d 23:59:59';

    if ($enable_countdown_price) {
      add_action('wp', function() {
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
        add_action('woocommerce_single_product_summary', array($this, 'render_countdown_block'), 12);
      }, 20);

      add_action('wp_enqueue_scripts', array($this, 'load_js_labels'));
    }
  }

  public function load_js_labels() {
    if (!is_singular('product')) {
      return;
    }

    ob_start();
    printf('var CODETOT_COUNTDOWN_LABELS = \'%s\'', json_encode($this->get_labels()));
    $labels = ob_get_clean();

    wp_register_script('codetot-countdown-labels', false);
    wp_enqueue_script('codetot-countdown-labels', false);
    wp_add_inline_script('codetot-countdown-labels', $labels);
  }

  public function format_date_with_time($date, $type = 'end') {
    if ($type === 'end') {
      return date($this->end_date_format, $date);
    } else {
      return date($this->start_date_format, $date);
    }
  }

  public function get_time_range($product)
  {
    $output = [];

    if ($product->is_type('simple')) {

      $start_date_timestamp = get_post_meta($product->get_id(), '_sale_price_dates_from', true);
      $end_time_timestamp = get_post_meta($product->get_id(), '_sale_price_dates_to', true);

      $output = array(
        'from' => $this->format_date_with_time($start_date_timestamp),
        'to' => $this->format_date_with_time($end_time_timestamp)
      );

    } elseif ($product->is_type('variable')) {

      $variations = $product->get_available_variations();

      if (empty($variations)) {
        return $output;
      }

      $start_dates = [];
      $end_dates = [];

      // Loop to find all available date time range
      foreach ($variations as $variation) {
        $variation_object = new WC_Product_Variation($variation['variation_id']);
        $variation_data = $variation_object->get_data();

        $sale_price_from = !empty($variation_data['date_on_sale_from']) ? $variation_data['date_on_sale_from'] : null;
        $sale_price_to = !empty($variation_data['date_on_sale_to']) ? $variation_data['date_on_sale_to'] : null;

        if (!empty($sale_price_from)) {
          $start_dates[] = $sale_price_from->date($this->start_date_format);
        }

        if (!empty($sale_price_to)) {
          $end_dates[] = $sale_price_to->date($this->end_date_format);
        }
      }

      if (!empty($start_dates)) {
        $output['from'] = min($start_dates);
      }

      if (!empty($end_dates)) {
        $output['to'] = max($end_dates);
      }
    }

    return $output;
  }

  public function get_labels() {
    return array(
      'days' => array(
        'singular' => esc_html__('Day', 'ct-bones'),
        'plural'   => esc_html__('Days', 'ct-bones')
      ),
      'hours' => array(
        'singular' => esc_html__('Hour', 'ct-bones'),
        'plural'   => esc_html__('Hours', 'ct-bones')
      ),
      'minutes' => array(
        'singular' => esc_html__('Minute', 'ct-bones'),
        'plural'   => esc_html__('Minutes', 'ct-bones')
      ),
      'seconds' => array(
        'singular' => esc_html__('Second', 'ct-bones'),
        'plural'   => esc_html__('Seconds', 'ct-bones')
      ),
      'message' => array(
        'not_start' => esc_html__('Sale begins after', 'ct-bones'),
        'ongoing'   => esc_html__('Sale ended after', 'ct-bones'),
        'expired'   => esc_html__('The sale has ended.', 'ct-bones'),
        'less_day'  => esc_html__('The sale will end after less than a day.', 'ct-bones'),
        'less_hour' => esc_html__('Hurry up! The sale will end after less than a hour.', 'ct-bones')
      )
    );
  }

  public function is_scheduled($date) {
    if (empty($date)) {
      return false;
    }

    return strtotime($date) > current_time('timestamp');
  }

  public function is_date_running($date) {
    if (empty($date)) {
      return false;
    }

    return strtotime($date) >= current_time('timestamp');
  }

  public function render_countdown_block()
  {
    global $product;
    $time_range = $this->get_time_range($product);
    $price = $product->get_price_html();

    $sales_price_from = !empty($time_range['from']) ? $time_range['from'] : null;
    $sales_price_to   = !empty($time_range['to']) ? $time_range['to'] : null;

    $is_scheduled = !empty($sales_price_from) && $this->is_scheduled($sales_price_from);
    $is_running = !empty($sales_price_to) && $this->is_date_running($sales_price_to);

    if (
      (
        $is_scheduled ||
        $is_running
      )
    ) {
      $attributes = 'data-woocommerce-block="countdown-price"';

      if (!empty($sales_price_from)) :
        $attributes .= sprintf(' data-start-date="%s"', $sales_price_from); // Ex: 2021-10-01 00:00:00
      endif;

      if (!empty($sales_price_to)) :
        $attributes .= sprintf(' data-end-date="%s"', $sales_price_to); // Ex: 2021-10-01 23:59:59
      endif;

      $price_output_html = sprintf('<span class="product-price product-price--countdown" %s>', $attributes);
      $price_output_html .= apply_filters('woocommerce_get_price', $price);
      $price_output_html .= '</span>'; // Close .single-product__price--has-discount

      echo $price_output_html;
    } else {
      echo '<span class="product-price">';
      woocommerce_template_single_price();
      echo '</span>';
    }
  }
}

Codetot_WooCommerce_Countdown_Price::instance();
