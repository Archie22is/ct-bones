<article class="post-card post-card--style-3">
  <a class="post-card__image-wrapper" href="<?php the_permalink(); ?>">
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
  <div class="post-card__main">
    <p class="mb-05 c-dark post-card__meta">
      <?php if(!empty($post_date)) : ?>
        <span class="post-card__meta-date"><?php echo $post_date; ?></span>
      <?php endif; ?>
      <?php if (!empty($category_link) && !empty($category_name)) : ?>
        <span class="post-card__meta-separator">|</span>
        <a class="post-card__meta-category" href="<?php echo $category_link; ?>"><?php echo $category_name; ?></a>
      <?php endif; ?>
    </p>
    <h3 class="post-card__title">
      <a class="post-card__link" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
    </h3>
    <div class="mt-05 post-card__description"><?php echo codetot_excerpt(27); ?></div>
    <div class="pt-05 post-card__footer">
      <?php the_block('button', array(
        'class' => 'rel post-card__button',
        'url' => get_permalink(),
        'type' => 'link',
        'button' => __('Read more', 'ct-bones')
      )); ?>
    </div>
  </div>
</article>
