<?php

/**
 * Fix case Either “offers”, “review”, or “aggregateRating” should be specified
 *
 * @param [type] $entity
 * @return void
 */
function codetot_rankmath_rich_snippet_product_entity($entity) {
  if (empty($entity['brand'])) {
    $entity['brand'] = get_bloginfo('name');
  }

  if (empty($entity['sku'])) {
    $entity['sku'] = 'N/A';
  }

  return $entity;
}
add_filter( 'rank_math/snippet/rich_snippet_product_entity', 'codetot_rankmath_rich_snippet_product_entity');

/**
 * Remove the generated product schema markup from Product Category and Shop pages.
 */
function codetot_wc_remove_product_schema_product_archive() {
	remove_action( 'woocommerce_shop_loop', array( WC()->structured_data, 'generate_product_data' ), 10, 0 );
}
add_action( 'woocommerce_init', 'codetot_wc_remove_product_schema_product_archive' );
