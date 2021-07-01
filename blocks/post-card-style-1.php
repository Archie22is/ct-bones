<?php

?>

<article class="bg-white fa1 f fdc post-card post-card post-card--style-1">
  <a class="f post-card__image-wrapper" href="<?php the_permalink(); ?>">
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
  </a>
  <div class="fa1 post-card__main">
    <?php if (!empty($category_name) && !empty($category_link)) : ?>
      <a class="label-text mb-05 bg-primary d-inline-flex c-white post-card__meta-category" href="<?php echo esc_url($category_link); ?>"><?php echo $category_name; ?></a>
    <?php endif; ?>
    <h3 class="post-card__title">
      <a class="post-card__link" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
    </h3>
    <p class="mt-05 mb-05 post-card__meta">
      <span class="post-card__meta-date"><?php echo $post_date; ?></span>
    </p>
  </div>
  <div class="pt-05 post-card__footer">
    <?php the_block('button', array(
      'class' => 'rel post-card__button',
      'url' => get_permalink(),
      'type' => 'link',
      'button' => __('Read more', 'ct-bones')
    )); ?>
  </div>
</article>
