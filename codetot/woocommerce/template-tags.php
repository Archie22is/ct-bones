<?php

function codetot_archive_product_price_html() {
  global $product;
  $price_html = $product->get_price_html();
  ?>

  <?php if ( !empty($price_html) ) : ?>
    <span class="product__price price"><?php echo $price_html; ?></span>
  <?php endif;
}

/**
 * Get product discount badge html
 *
 * @return void
 */
function codetot_archive_product_sale_flash_html($product)
{
  $final_price = codetot_get_price_discount_percentage($product, 'percentage');
  $classes = ['product__tag', 'product__tag--onsale'];

  if (!empty($final_price) ) :
    ob_start();
    ?>
    <span class="<?php echo esc_attr(implode(' ', array_filter($classes))); ?>">
      <?php echo esc_html($final_price); ?>
    </span>
    <?php
    return ob_get_clean();
  else :
    return '';
  endif;
}

function codetot_archive_product_product_rating_html()
{
  global $product;

  $average = $product->get_average_rating();
  $enable_star_rating = codetot_get_theme_mod('archive_product_star_rating', 'woocommerce') ?? false;

  if (!empty($average) || $enable_star_rating) :
    if ($enable_star_rating && $average == 0) {
      $average = 5;
    }
    ?>
    <div class="product__rating">
      <?php echo '<div class="product__rating-stars"><span style="width:'.( ( $average / 5 ) * 100 ) . '%"></span></div>'; ?>
    </div>
    <?php
  endif;
}
