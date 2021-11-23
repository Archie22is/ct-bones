<?php
$_class    = 'section wp-block-group page-header';
$_class   .= ! empty( $alignment ) ? ' ' . esc_attr( $alignment ) : '';
$_class   .= ! empty( $class ) ? ' ' . esc_attr( $class ) : '';

do_action( 'codetot_page_header_before' ); ?>

<div class="<?php echo $_class; ?>">
  <div class="wp-block-group__inner-container">
	<h1 class="m0 has-heading-2-font-size page-header__title"><?php echo $title; ?></h1>
	<?php if ( ! empty( $description ) ) : ?>
	  <div class="page-header__description"><?php echo $description; ?></div>
	<?php endif; ?>
  </div>
</div>

<?php do_action( 'codetot_page_header_after' ); ?>
