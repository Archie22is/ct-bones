<?php

add_action('wp_ajax_codetot_woocommerce_ajax_add_to_cart', 'codetot_woocommerce_ajax_add_to_cart_callback');
add_action('wp_ajax_nopriv_codetot_woocommerce_ajax_add_to_cart', 'codetot_woocommerce_ajax_add_to_cart_callback');

function codetot_woocommerce_ajax_add_to_cart_callback()
{
  var_dump('loaded');
  wp_die();
  $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
  $quantity = absint($_POST['quantity']);
  $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
  $product_status = get_post_status($product_id);

  if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity) && 'publish' === $product_status) {

    do_action('woocommerce_ajax_added_to_cart', $product_id);

    if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
      wc_add_to_cart_message(array($product_id => $quantity), true);
    }

    WC_AJAX::get_refreshed_fragments();
  } else {

    $data = array(
      'error' => true,
      'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
    );

    echo wp_send_json($data);
  }

  wp_die();
}
