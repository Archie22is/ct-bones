<?php

function codetot_is_product_out_of_stock($product) {
  if ( ! $product || ! is_object( $product ) ) {
    return false;
  }

  $in_stock     = $product->is_in_stock();
  $manage_stock = $product->managing_stock();
  $quantity     = $product->get_stock_quantity();

  if (
    ( $product->is_type( 'simple' ) && ( ! $in_stock || ( $manage_stock && 0 === $quantity ) ) ) ||
    ( $product->is_type( 'variable' ) && $manage_stock && 0 === $quantity )
  ) {
    return true;
  }

  return false;
}

function codetot_get_upsell_products( $limit = '-1', $columns = 4, $orderby = 'rand', $order = 'desc' ) {
  global $product;

  if ( ! $product ) {
    return;
  }

  // Handle the legacy filter which controlled posts per page etc.
  $args = apply_filters(
    'woocommerce_upsell_display_args',
    array(
      'posts_per_page' => $limit,
      'orderby'        => $orderby,
      'order'          => $order,
      'columns'        => $columns,
    )
  );
  wc_set_loop_prop( 'name', 'up-sells' );
  wc_set_loop_prop( 'columns', apply_filters( 'woocommerce_upsells_columns', isset( $args['columns'] ) ? $args['columns'] : $columns ) );

  $orderby = apply_filters( 'woocommerce_upsells_orderby', isset( $args['orderby'] ) ? $args['orderby'] : $orderby );
  $order   = apply_filters( 'woocommerce_upsells_order', isset( $args['order'] ) ? $args['order'] : $order );
  $limit   = apply_filters( 'woocommerce_upsells_total', isset( $args['posts_per_page'] ) ? $args['posts_per_page'] : $limit );

  // Get visible upsells then sort them at random, then limit result set.
  $upsells = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', $product->get_upsell_ids() ), 'wc_products_array_filter_visible' ), $orderby, $order );
  $upsells = $limit > 0 ? array_slice( $upsells, 0, $limit ) : $upsells;

  return $upsells;
}

function codetot_get_product_query_by_type($attr) {
  if (empty($attr)) {
    return new WP_Error(400, __FUNCTION__ . ': ' . __('No attribute was defined for query.', 'ct-bones'));
  }

  switch($attr):

    case 'normal':

      return array(
        'post_type' => 'product',
        'orderby'   => 'DESC',
      );

      break;

    case 'featured':

      return array(
        'post_type'   => 'product',
        'tax_query'   => array(
          'relation'  => 'AND',
          array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => 'featured',
          ),
        )
      );

      break;

    case 'on_sale':

      return array(
        'post_type'   => 'product',
        'meta_query'  => array(
          'relation'  => 'OR',
          array(
            'key'     => '_sale_price',
            'value'   => 0,
            'compare' => '>',
            'type'    => 'numeric'
          ),
          array(
            'key'     => '_min_variation_sale_price',
            'value'   => 0,
            'compare' => '>',
            'type'    => 'numeric'
          )
        )
      );

      break;

    case 'random':

      return array(
        'post_type' => 'product',
        'orderby'   => 'rand',
      );

      break;

    case 'top_rated':
      return array(
        'post_status'    => 'publish',
        'post_type'      => 'product',
        'meta_key'       => '_wc_average_rating',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
      );

      break;

    case 'total_sales':

      return array(
        'post_type'      => 'product',
        'meta_key'       => 'total_sales',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
        'meta_query'     => WC()->query->get_meta_query(),
      );

      break;

    default :

      return array(
        'post_type' => 'product'
      );

  endswitch;
}
