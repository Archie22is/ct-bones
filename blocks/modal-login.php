<?php
ob_start();
woocommerce_login_form();
$content = ob_get_clean();

$enable_popup = get_global_option('codetot_woocommerce_login_popup') ?? false;

if (!empty($content) && $enable_popup) :
  the_block('modal', array(
    'class' => 'modal--login',
    'attributes' => ' data-woocommerce-block="modal-login"',
    'id' => 'modal-login',
    'header' => sprintf('<p class="modal__title">%s</p>', esc_html__('Sign in', 'ct-blocks')),
    'content' => $content,
    'close_button_class' => 'js-close-account-modal'
  ));
endif;
?>
