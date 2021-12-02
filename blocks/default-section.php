<?php
$_attrs  = '';
$_attrs .= ! empty( $id ) ? sprintf( ' id="%s"', esc_attr( $id ) ) : '';
$_attrs .= ! empty( $attributes ) ? ' ' . $attributes : '';
$_class  = 'default-section';
$_class .= ! empty( $background_image ) ? ' has-bg-image' : '';
$_class .= ! empty( $class ) ? ' ' . $class : '';
$_tag    = ! empty( $tag ) ? $tag : 'section';

// Support lazyload main block
$_enable_lazyload       = isset( $lazyload ) && $lazyload === true;
$_lazyload_loader_class = ! empty( $lazyload_loader_class ) ? $lazyload_loader_class : 'loader--dark';
if ( $_enable_lazyload ) {
	$_attrs .= empty( $attributes ) ? ' data-block="default-section"' : '';
	$_class .= ' is-loading has-lazyload';
}

ob_start();
if ( ! empty( $content ) ) {
	if ( ! is_array( $content ) ) {
		echo $content;
	} else {
		echo codetot_build_grid_columns( $content, 'default-section' );
	}
}
$_content = ob_get_clean();

if ( ! empty( $content ) ) : ?>
  <<?php echo $_tag; ?> class="<?php echo $_class; ?>"
			  <?php
				if ( ! empty( $_attrs ) ) :
					echo ' ' . $_attrs;
endif;
				?>
	>
	<?php
	if ( ! empty( $background_image ) ) :
		the_block(
			'image',
			array(
				'image'    => $background_image,
				'class'    => 'default-section__background-image',
				'lazyload' => $_enable_lazyload,
				'size'     => 'medium',
			)
		);
	endif;
	?>
	<?php
	if ( ! empty( $before_header ) ) :
		echo $before_header;
endif;
	?>
	<?php if ( ! empty( $header ) ) : ?>
	  <div class="wp-block-group default-section__header">
		<div class="wp-block-group__inner-container container default-section__container default-section__container--header">
		  <div class="wp-block-group default-section__inner default-section__inner--header">
			<div class="wp-block-group__inner-content">
				<?php echo $header; ?>
			</div>
		  </div>
		</div>
	  </div>
	<?php endif; ?>
	<?php
	if ( ! empty( $before_main ) ) :
		echo $before_main;
endif;
	?>
	<div class="wp-block-group default-section__main">
	  <div class="wp-block-group__inner-container container default-section__container default-section__container--main">
		<div class="wp-block-group default-section__inner default-section__inner--main">
			<div class="wp-block-group__inner-content<?php if ( $_enable_lazyload ) : ?> is-not-loaded js-main-content<?php endif; ?>">
				<?php
					if ( $_enable_lazyload ) :
						printf( '<noscript>%s</noscript>', $_content );
				else :
					echo $_content;
				endif;
				?>
			</div>
		</div>
		<?php
		if ( $_enable_lazyload ) :
			the_block(
				'loader',
				array(
					'class' => $_lazyload_loader_class,
				)
			);
		endif;
		?>
	  </div>
	</div>
	<?php
	if ( ! empty( $after_main ) ) :
		echo $after_main;
	endif;
	?>
	<?php if ( ! empty( $footer ) ) : ?>
	  <div class="wp-block-group default-section__footer">
		<div class="wp-block-group__inner-container container default-section__container default-section__container--footer">
		  <div class="wp-block-group default-section__inner default-section__inner--footer">
			<div class="wp-block-group__inner-container">
				<?php echo $footer; ?>
			</div>
		</div>
		</div>
	  </div>
	<?php endif; ?>
	<?php
	if ( ! empty( $after_footer ) ) :
		echo $after_footer;
endif;
	?>
  </<?php echo $_tag; ?>>
<?php endif; ?>
