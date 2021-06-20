<article class="w100 post-row">
  <div class="f post-row__grid">
    <div class="f ais post-row__col post-row__col--image">
      <a class="w100 f fdc post-row__image-wrapper" href="<?php the_permalink(); ?>">
        <?php
        if (has_post_thumbnail()) :
          the_block('image', array(
            'image' => get_post_thumbnail_id(),
            'class' => 'image--cover post-row__image'
          ));
        else :
          the_block('image-placeholder');
        endif;
        ?>
      </a>
    </div>
    <div class="f fdc pl-1 post-row__col post-row__col--content">
      <h3 class="w100 f post-row__title">
        <a class="large-text bold-text post-row__title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </h3>
      <?php if (get_the_excerpt() !== '') : ?>
        <div class="mt-1 w100 post-row__description"><?php the_excerpt(); ?></div>
      <?php endif; ?>
      <div class="mt-1 w100 post-row__footer">
        <?php the_block('button', array(
          'button' => esc_html__('Read more', 'ct-bones'),
          'url' => get_the_permalink(),
          'size' => 'small',
          'type' => 'dark',
          'class' => 'post-row__button'
        )); ?>
      </div>
    </div>
  </div>
</article>
