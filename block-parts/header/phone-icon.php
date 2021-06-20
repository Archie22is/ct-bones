<?php
$display = get_global_option('codetot_header_display_phone') ?? false;
$phone_number = get_codetot_data('codetot_company_hotline') ?? null;
$phone_link = preg_replace('/\D/', '', $phone_number);
$phone_link = 'tel:' . esc_attr($phone_link);

if ($display && !empty($phone_number)) :
?>
  <a class="header__menu-icons__item header__menu-icons__link header__menu-icons__item--phone" href="<?php echo apply_filters('codetot_header_phone_link', $phone_link); ?>">
    <span class="header__menu-icons__icon"><?php echo apply_filters('codetot_header_phone_icon', codetot_svg('phone', false)); ?></span>
    <span class="header__menu-icons__content">
      <span class="header__menu-icons__label"><?php echo apply_filters('codetot_header_phone_label', __('Call us', 'ct-bones')); ?></span>
      <span class="header__menu-icons__text"><?php echo apply_filters('codetot_header_phone_html', $phone_number); ?></span>
    </span>
  </a>
<?php endif; ?>
