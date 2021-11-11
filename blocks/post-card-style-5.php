<article class="bg-white fa1 f fdc post-card post-card post-card--style-5">
  <a class="f post-card__image-wrapper" href="<?php the_permalink(); ?>">
	<?php
	if ( has_post_thumbnail() ) :
		the_block(
			'image',
			array(
				'image' => get_post_thumbnail_id(),
				'class' => 'w100 image--hd image--cover post-card__image',
			)
		);
	else :
		the_block(
			'image-placeholder',
			array(
				'class'       => 'w100',
				'image_class' => 'image--hd',
			)
		);
	endif;
	?>
  </a>
  <div class="fa1 post-card__main">
	<?php if ( ! empty( $post_date ) ) : ?>
	<p class="mt-05 mb-05 post-card__meta">
		<span class="post-card__meta-date"><?php echo $post_date; ?></span>
	  </p>
	<?php endif; ?>
	<h3 class="post-card__title">
	  <a class="post-card__link" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
	</h3>
  </div>
</article>
