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
function codetot_archive_product_sale_flash_html()
{
  global $product;
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
  $review_count = $product->get_review_count();
  $enable_star_rating = codetot_get_theme_mod('archive_product_star_rating', 'woocommerce') ?? false;
  $svg_icon = '<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" color="#fdd836" height="14" width="14" xmlns="http://www.w3.org/2000/svg" style="color: rgb(253, 216, 54);"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path></svg>';

  if (!empty($average) || $enable_star_rating) :
    if ($enable_star_rating && $average == 0) {
      $average = 5;
      $percentage_width_style = sprintf('%s%s', ($average / 5) * 100, '%');
    }
    ?>
    <div class="product__rating">
      <div class="product__rating-stars" style="width: <?php echo $percentage_width_style; ?>">
        <?php for($i = 0; $i < 5; $i++) : ?>
          <?php echo $svg_icon; ?>
        <?php endfor; ?>
      </div>
      <?php if (!empty($review_count)) : ?>
        <div class="product__rating-count">(<?php echo absint($review_count); ?>)</div>
      <?php endif; ?>
    </div>
    <?php
  endif;
}
