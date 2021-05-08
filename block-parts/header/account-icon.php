<?php
  ob_start(); ?>
  <a class="header__menu-icons__item header__menu-icons__link header__menu-icons__item--account" href="<?php echo wc_get_account_endpoint_url('dashboard'); ?>">
     <span class="header__menu-icons__icon">
       <?php codetot_svg('user', true); ?>
     </span>
     <span class="screen-reader-text"><?php _e('Click to manage an account', 'ct-bones'); ?></span>
   </a>
 <?php $html = ob_get_clean();

$hide_icon = get_global_option('codetot_header_hide_account_icon') ?? false;
if (!$hide_icon && class_exists('WooCommerce')) :
  echo apply_filters('codetot_header_account_icon', $html);
endif; ?>
