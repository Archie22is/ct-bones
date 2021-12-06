<?php
ob_start();
woocommerce_login_form();
$content = ob_get_clean();

if ( ! empty( $content ) ) :

	the_block(
		'modal',
		array(
			'class'              => 'modal--login',
			'attributes'         => ' data-woocommerce-block="modal-login"',
			'id'                 => 'modal-login',
			'header'             => sprintf( '<p class="modal__title">%s</p>', esc_html__( 'Sign in', 'ct-bones' ) ),
			'content'            => $content,
			'close_button_class' => 'js-close-account-modal',
		)
	);

endif;
