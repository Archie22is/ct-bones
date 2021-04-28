<?php
$display = get_global_option('codetot_header_display_phone') ?? false;
$phone_number = get_codetot_data('codetot_company_hotline') ?? null;
$phone_link = preg_replace('/\D/', '', $phone_number);
$phone_link = 'tel:' . esc_attr($phone_link);

if ($display && !empty($phone_number)) :
?>
  <a class="header__menu-icons__item header__menu-icons__link header__menu-icons__item--phone" href="<?php echo $phone_link; ?>">
    <span class="header__menu-icons__icon"><?php codetot_svg('phone', true); ?></span>
    <span class="header__menu-icons__content">
      <span class="header__menu-icons__label"><?php _e('Call us', 'ct-theme'); ?></span>
      <span class="header__menu-icons__text"><?php echo $phone_number; ?></span>
    </span>
  </a>
<?php endif; ?>
