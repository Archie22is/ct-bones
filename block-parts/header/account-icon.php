<?php
$hide_icon = codetot_get_theme_mod( 'header_hide_account_icon' ) ?? false;
if ( class_exists( 'WooCommerce' ) && ! $hide_icon && function_exists( 'wc_get_account_endpoint_url' ) ) :
	ob_start(); ?>
  <a class="header__menu-icons__item header__menu-icons__link header__menu-icons__item--account" href="<?php echo wc_get_account_endpoint_url( 'dashboard' ); ?>">
	<span class="header__menu-icons__icon">
	  <?php codetot_svg( 'user', true ); ?>
	</span>
	<span class="screen-reader-text"><?php _e( 'Click to manage an account', 'ct-bones' ); ?></span>
  </a>
	<?php 
	$html = ob_get_clean();
	echo apply_filters( 'codetot_header_account_icon', $html );
endif;
