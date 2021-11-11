<article class="post-card post-card--style-4">
  <div class="post-card__main">
	<h3 class="post-card__title">
	  <a class="post-card__link" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
	</h3>
	<div class="mt-05 post-card__description"><?php echo codetot_excerpt( 15 ); ?></div>
	<?php if ( ! empty( $post_date ) ) : ?>
	  <p class="mt-05 post-card__meta">
		<span class="post-card__meta-date"><?php echo $post_date; ?></span>
	  </p>
	<?php endif; ?>
  </div>
  <div class="post-card__footer">
	<div class="f fw jcb post-card__footer-grid">
	  <?php if ( ! empty( $category_link ) && ! empty( $category_name ) ) : ?>
		<div class="w50 post-card__footer-col post-card__footer-col--left">
		  <a class="label-text mb-05 bg-primary d-inline-flex c-white post-card__meta-category" href="<?php echo $category_link; ?>"><?php echo $category_name; ?></a>
		</div>
	  <?php endif; ?>
	  <div class="w50 fa1 align-r post-card__footer-col post-card__footer-col--right">
		<?php 
		the_block(
			'button',
			array(
				'class'  => 'rel post-card__button',
				'url'    => get_permalink(),
				'type'   => 'link',
				'button' => __( 'Read more', 'ct-bones' ),
			)
		); 
		?>
	  </div>
	</div>
  </div>
</article>
