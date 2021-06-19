<?php
/**
 * Display shop steps when visiting cart and checkout
 */

$page_keys = array(
  'woocommerce_cart_page_id',
  'woocommerce_checkout_page_id'
);

$steps = array();

// Add cart page
$cart_page_id = get_option('woocommerce_cart_page_id');
if (!empty($cart_page_id)) {
  $steps[] = array(
    'is_active' => is_page($cart_page_id),
    'title' => get_the_title($cart_page_id),
    'url' => wc_get_cart_url()
  );
}

// Add checkout page
$checkout_page_id = get_option('woocommerce_checkout_page_id');
if (!empty($checkout_page_id)) {
  $steps[] = array(
    'is_active' => is_checkout() && !is_order_received_page(),
    'title' => get_the_title($checkout_page_id),
    'url' => get_permalink($checkout_page_id)
  );
}

// Add thank you page
$steps[] = array(
  'is_active' => is_checkout() && is_order_received_page(),
  'title' => __('Complete order', 'ct-bones')
);

?>
<div class="section shop-steps">
  <div class="f aie shop-steps__list">
    <?php foreach($steps as $index => $step) :
      ob_start(); ?>
      <span class="shop-steps__number"><?php echo $index + 1; ?></span>
      <span class="shop-steps__title"><?php echo $step['title']; ?></span>
      <?php
      $html = ob_get_clean();

      if (!empty($step['url'])) :
        printf('<a class="%1$s" href="%2$s">%3$s</a>',
          'shop-steps__item' . (!empty($step['is_active']) ? ' shop-steps__item--active' : ''),
          esc_url($step['url']),
          $html
        );
      else :
        printf('<span class="%1$s">%2$s</span>',
          'shop-steps__item' . (!empty($step['is_active']) ? ' shop-steps__item--active' : ''),
          $html
        );
      endif;
      endforeach; ?>
  </div>
</div>
