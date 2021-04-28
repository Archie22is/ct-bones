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
