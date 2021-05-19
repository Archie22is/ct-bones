<?php

defined('ABSPATH') || exit;

/**
 * @link       https://codetot.com
 * @since      1.0.0
 * @package    Codetot_Woocommerce
 * @subpackage Codetot_Woocommerce/includes/features
 * @author     CODE TOT JSC <khoi@codetot.com>
 */
if (!class_exists('Codetot_Woocommerce_Viewed_Products')) {
  /**
   * The Woostify WooCommerce Integration class
   */
  class Codetot_Woocommerce_Viewed_Products
  {
    /**
     * Instance
     *
     * @var Codetot_Woocommerce_Viewed_Products instance
     */
    public static $instance;

    public static $cookie_name = 'codetot_product_recently_viewed';
    public static $number_of_products = 4;

    /**
     * Initiator
     */
    public static function get_instance()
    {
      if (!isset(self::$instance)) {
        self::$instance = new self();
      }
      return self::$instance;
    }

    /**
     * Setup class.
     */
    public function __construct()
    {
      add_action('template_redirect', array($this, 'add_cookies'), 20);
      add_action('woocommerce_after_single_product_summary', array($this, 'render_section'), 40);
    }

    public function add_cookies()
    {
      if (!is_singular('product')) {
        return;
      }

      global $post;
      $viewed_products = array();
      $cookie = !empty($_COOKIE[$this::$cookie_name]) ? $_COOKIE[$this::$cookie_name] : null;

      if (!empty($cookie)) {
        $viewed_products = (array)explode('|', sanitize_text_field(wp_unslash($cookie)));
      }

      if (!in_array($post->ID, $viewed_products)) {
        $viewed_products[] = $post->ID;
      }

      // Store for session only.
      wc_setcookie($this::$cookie_name, implode('|', array_filter($viewed_products)));
    }

    /**
     * @return array|void|WP_Query
     */
    public function get_query()
    {
      $cookies = isset($_COOKIE[$this::$cookie_name]) ? sanitize_text_field(wp_unslash($_COOKIE[$this::$cookie_name])) : false;

      if (empty($cookies)) {
        return;
      }

      $ids = explode('|', $cookies);

      // Exclude a current post from the list
      if (is_singular('product') && in_array(get_the_ID(), $ids)) {
        global $post;

        $ids = array_diff($ids, array($post->ID));
      }

      $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => $this::$number_of_products,
        'post__in' => $ids,
      );

      $products_query = new WP_Query($args);

      if (!$products_query->have_posts()) {
        return [];
      }

      return $products_query;
    }

    public function render_section()
    {
      $sidebar_layout = get_global_option('codetot_product_layout') ?? 'no-sidebar';
      $columns = get_global_option('codetot_woocommerce_viewed_products_colums') ?? '4';
      $query = $this->get_query();
      $_class = 'section product-grid--viewed-products';

      if ($sidebar_layout !== 'no-sidebar') {
        $_class .= ' product-grid--no-container';
      }

      the_block('product-grid', array(
        'class' => $_class,
        'title' => esc_html__('Recently Viewed Products', 'ct-bones'),
        'query' => $query,
        'columns' => $columns
      ));
    }
  }

  Codetot_Woocommerce_Viewed_Products::get_instance();
}
