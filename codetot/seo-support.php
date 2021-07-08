<?php

add_filter( 'rank_math/snippet/rich_snippet_product_entity', 'codetot_rankmath_rich_snippet_product_entity');

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
