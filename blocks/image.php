<?php
$_lazyload = ( isset( $lazyload ) && $lazyload ) || ! isset( $lazyload );
$_size     = ! empty( $size ) ? $size : 'full';

if ( ! empty( $image ) && ! empty( $class ) ) :
	$image_id = false;


	if ( ! empty( $image['ID'] ) ) {
		$image_id = $image['ID'];
	} elseif ( is_int( (int) $image ) ) {
		$image_id = $image;
	} else {
		echo '<-- Undefined image -->';

	}
	?>
  <picture class="image <?php echo $class; ?>">
	<?php

	$mobile_image    = wp_get_attachment_image_src( $image_id, 'medium' );
	$tablet_image    = wp_get_attachment_image_src( $image_id, 'large' );
	$full_size_image = wp_get_attachment_image_src( $image_id, 'full' );

	if ( ! $_lazyload ) :

		printf( '<source srcset="%1$s" sizes="(max-width: 480px) %2$s" media="(max-width: 480px)">', $mobile_image[0], $mobile_image[1] . 'w' );
		printf( '<source srcset="%1$s" sizes="(max-width: 1024px) %2$s" media="(max-width: 1024px)">', $tablet_image[0], $tablet_image[1] . 'w' );
		printf(
			'<img class="image__img" src="%1$s" width="%2$s" height="%3$s" alt="%4$s">',
			$full_size_image[0],
			$full_size_image[1],
			$full_size_image[2],
			$full_size_image[3] ?? ''
		);

	else :

		echo wp_get_attachment_image(
			$image_id,
			$_size,
			false,
			array(
				'class' => 'wp-post-image image__img',
			)
		);

	endif;
	?>
  </picture>
<?php endif; ?>
