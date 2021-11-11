<?php
$default_attr  = ' data-block="header"';
$header_attr   = apply_filters( 'codetot_header_attributes', $default_attr );
$enable_sticky = codetot_get_theme_mod( 'header_sticky_type' ) ?? 'jump-down';
$header_attr  .= ( $enable_sticky != 'none' ) ? ' data-sticky-header="' . $enable_sticky . '"' : '';
$header_class  = function_exists( 'codetot_header_class' ) ? codetot_header_class() : 'header';
?>

<header id="masthead" class="<?php echo $header_class; ?>" <?php echo $header_attr; ?>>
  <?php 
	if ( $enable_sticky != 'none' ) {
		echo '<div class="header__sticky js-sticky-header">';
	}
	?>
  <?php do_action( 'codetot_header' ); ?>

  <?php 
	if ( $enable_sticky != 'none' ) {
		echo '</div>';
	}
	?>
</header>
