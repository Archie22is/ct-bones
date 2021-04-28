<?php
$hide_icon = get_global_option('codetot_header_hide_cart_icon') ?? false;
if (!$hide_icon && class_exists('WooCommerce')) : ?>
  <?php $cart_url = wc_get_cart_url(); ?>
  <a class="header__menu-icons__item header__menu-icons__item--cart header__menu-icons__link js-minicart-trigger" href="<?php echo $cart_url; ?>">
    <span class="header__menu-icons__icon">
      <?php codetot_svg('cart', true); ?>
    </span>
    <?php if (is_object(WC()->cart) && !empty(WC()->cart)) : ?>
        <span class="header__menu-icons__count">
          <?php echo sprintf ( _n( '%d', '%d', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?>
        </span>
    <?php endif; ?>
    <span class="screen-reader-text"><?php _e('Cart', 'ct-theme'); ?></span>
  </a>
<?php endif; ?>
