<?php
$hide_icon = codetot_get_theme_mod('header_hide_cart_icon') ?? false;

if (!$hide_icon && class_exists('WooCommerce')) :
  echo apply_filters('codetot_header_cart_icon', do_shortcode('[cart-icon
    class="cart-shortcode header__menu-icons__item header__menu-icons__item--cart header__menu-icons__link js-minicart-trigger"
    svg_icon="cart"
  ]'));
endif;
