<?php
$hide_icon = get_global_option('codetot_header_hide_account_icon') ?? false;
if (!$hide_icon && class_exists('WooCommerce')) :
  if ( is_user_logged_in() ) : ?>
   <a class="header__menu-icons__item header__menu-icons__link header__menu-icons__item--account" href="<?php echo wc_get_account_endpoint_url('dashboard'); ?>">
      <span class="header__menu-icons__icon">
        <?php codetot_svg('user', true); ?>
      </span>
      <span class="screen-reader-text"><?php _e('Click to manage an account', 'ct-theme'); ?></span>
      </a>
  <?php  else : ?>
    <button class="header__menu-icons__item header__menu-icons__link header__menu-icons__item--account" data-open-modal="modal-login">
      <span class="header__menu-icons__icon">
        <?php codetot_svg('user', true); ?>
      </span>
    </button>
  <?php endif; ?>
<?php endif; ?>
