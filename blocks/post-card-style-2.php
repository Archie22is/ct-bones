<article class="fa1 f fdc post-card post-card post-card--style-2">
  <a class="f rel post-card__image-wrapper" href="<?php the_permalink(); ?>">
    <?php
    if (has_post_thumbnail()) :
      the_block('image', array(
        'image' => get_post_thumbnail_id(),
        'class' => 'w100 image--hd image--cover post-card__image'
      ));
    else :
      the_block('image-placeholder', array(
        'class' => 'w100',
        'image_class' => 'image--hd'
      ));
    endif;
    ?>
    <span class="f c-white abs z-2 post-card__badge">
      <span class="post-card__badge-date"><?php echo $post_date; ?></span>
    </span>
  </a>
  <div class="fa1 post-card__main">
    <?php if (!empty($category_name) && !empty($category_link)) : ?>
      <a class="label-text mt-05 c-secondary bold-text d-inline-flex rel post-card__meta-category" href="<?php echo esc_url($category_link); ?>"><?php echo $category_name; ?></a>
    <?php endif; ?>
    <h3 class="post-card__title">
      <a class="d-block post-card__link" href="<?php the_permalink(); ?>" title="<?php printf(__('View post %s', 'ct-bones'), get_the_title()); ?>"><?php the_title(); ?></a>
    </h3>
    <div class="mt-05 post-card__description"><?php echo codetot_excerpt(27); ?></div>
  </div>
</article>
