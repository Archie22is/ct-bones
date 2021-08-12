<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Codetot_WooCommerce_Viewed_Products {
  /**
   * Singleton instance
   *
   * @var Codetot_WooCommerce_Viewed_Products
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_WooCommerce_Viewed_Products
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
    $this->prefix = 'codetot_';

    add_action('wp', function() {
      $this->enable = !empty(get_global_option('codetot_woocommerce_enable_viewed_products_section'));

      if ($this->enable) {
        add_action('woocommerce_after_single_product_summary', 'codetot_render_viewed_products_section', 75);
      }
    });

    add_filter('codetot_settings_woocommerce_fields', array($this, 'register_fields'));
  }

  function register_fields($fields) {
    $fields = array_merge($fields, array(
      array(
        'type' => 'switch',
        'name' => __('Enable Viewed Products section', 'ct-bones'),
        'id'   => $this->prefix . 'woocommerce_enable_viewed_products_section',
        'std'  => 1,
        'desc' => __('Display viewed products section on single product page.', 'ct-bones')
      ),
      array(
        'type' => 'select',
        'name' => __('Viewed Products Columns', 'ct-bones'),
        'id'   => $this->prefix . 'woocommerce_viewed_products_colums',
        'std'  => 4,
        'options' => [
          3 => 3,
          4 => 4,
          5 => 5,
          6 => 6
        ]
      ),
    ));

    return $fields;
  }
}

function codetot_render_viewed_products_section() {
  $viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) : array(); // @codingStandardsIgnoreLine
  $viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

  if ( empty( $viewed_products ) ) {
    return;
  }

  $columns = get_global_option('codetot_woocommerce_viewed_products_colums') ?? 4;

  $post_args = array(
    'posts_per_page' => apply_filters('codetot_viewed_products_number', $columns),
    'no_found_rows'  => 1,
    'post_status'    => 'publish',
    'post_type'      => 'product',
    'post__in'       => $viewed_products,
    'orderby'        => 'post__in',
  );

  if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
    $post_args['tax_query'] = array(
      array(
        'taxonomy' => 'product_visibility',
        'field'    => 'name',
        'terms'    => 'outofstock',
        'operator' => 'NOT IN',
      ),
    );
  }

  $_post_args = apply_filters( 'woocommerce_recently_viewed_products_widget_query_args', $post_args);

  $post_query = new WP_Query($_post_args);

  $_class = 'section default-section--no-container product-grid--viewed-products';

  if ($post_query->have_posts()) :
    the_block('product-grid', array(
      'class' => $_class,
      'title' => apply_filters( 'codetot_product_viewed_products_heading', __( 'Recently Viewed Products', 'woocommerce' )),
      'query' => $post_query,
      'columns' => $columns
    ));
  endif;
}
add_action( 'template_redirect', 'codetot_track_product_view', 20 );

function codetot_track_product_view() {
	if ( ! is_singular( 'product' ) ) {
		return;
	}

	global $post;

	if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) )
		$viewed_products = array();
	else
		$viewed_products = (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] );

	if ( ! in_array( $post->ID, $viewed_products ) ) {
		$viewed_products[] = $post->ID;
	}

	if ( sizeof( $viewed_products ) > 15 ) {
		array_shift( $viewed_products );
	}

	wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
}

Codetot_WooCommerce_Viewed_Products::instance();
