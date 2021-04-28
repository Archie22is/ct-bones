<?php

add_action('widgets_init', 'codetot_register_woocommerce_sidebars');
function codetot_register_woocommerce_sidebars() {
  register_sidebar(
    array(
      'id' => 'shop-sidebar',
      'name' => __('Shop Sidebar', 'ct-theme'),
      'before_widget' => '<div id="%1$s" class="widget widget--shop %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<p class="widget__title">',
      'after_title' => '</p>'
    )
  );

  register_sidebar(array(
    'name' => __('Product Sidebar', 'ct-theme'),
    'id' => 'product-sidebar',
    'before_widget' => '<aside id="%1$s" class="widget widget--product %2$s"><div class="widget__inner">',
    'after_widget' => '</div></aside>',
    'before_title' => '<p class="widget__title">',
    'after_title' => '</p>'
  ));

  register_sidebar(array(
    'name' => __('Product Category Sidebar', 'ct-theme'),
    'id' => 'product-category-sidebar',
    'before_widget' => '<aside id="%1$s" class="widget widget--product-category %2$s"><div class="widget__inner">',
    'after_widget' => '</div></aside>',
    'before_title' => '<p class="widget__title">',
    'after_title' => '</p>'
  ));
}
