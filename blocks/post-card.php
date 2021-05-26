<?php
$categories = get_the_category();
$category = !empty($categories) ? $categories[0] : '';
$title_num_words = 5;

$_class = 'fa1 f fdc post-card';
$_class .= !empty($card_style) ? ' post-card--' . esc_attr($card_style) : ' post-card--style-1';
$_class .= !empty($class) ? ' ' . esc_attr($class) : '';

// Visible condition
$_display_category = !empty($card_style) && (in_array($card_style, array('style-3')));
$_display_author = !empty($card_style) && (in_array($card_style, array('style-4')));
$_display_date = !empty($card_style) && $card_style == 'style-3';
$_display_date_bage = !empty($card_style) && $card_style == 'style-2';
$_display_footer = !empty($card_style) && $card_style == 'style-3';
$_display_description = !empty($card_style) && (in_array($card_style, array('style-2', 'style-3', 'style-4', 'style-5')));

$_format_date = !empty($format_date) ? $format_date : get_option('format_date');
$word_count = (int) apply_filters('codetot_post_card_excerpt_number', 20);

?>
<article class="<?php echo $_class; ?>">
  <div class="post-card__wrapper">
    <a class="post-card__image-wrapper" href="<?php the_permalink(); ?>">
      <?php
      if (has_post_thumbnail()) :
        the_block('image', array(
          'image' => get_post_thumbnail_id(),
          'class' => 'image--cover post-card__image'
        ));
      else :
        the_block('image-placeholder', array(
          'class' => 'post-card__image'
        ));
      endif;
      ?>

      <?php if ($_display_date_bage) : ?>
        <div class="post-card__bage">
          <span class="post-card__bage-date"><?php echo get_the_date(!empty($format_date) ? $format_date : ''); ?></span>
          <span class="post-card__bage-icon"><?php codetot_svg('right-arrow',true); ?></span>
        </div>
        <?php endif; ?>
      </a>
  </div>
  <div class="f fdc fa1 post-card__main">
    <?php if ($_display_category || $_display_date) : ?>
      <p class="mb-05 post-card__meta">
        <?php if ($_display_date) : ?>
          <span class="post-card__meta-date"><?php echo get_the_date($format_date); ?></span>
        <?php endif; ?>
        <?php if ($_display_date && $_display_category) : ?>
          <span class="post-card__meta-separator">|</span>
        <?php endif; ?>
        <?php if ($_display_category) : ?>
          <span class="post-card__meta-category"><?php echo $category->name; ?></span>
        <?php endif; ?>
      </p>
    <?php endif; ?>
    <h3 class="post-card__title">
      <a class="post-card__link" href="<?php the_permalink(); ?>"><?php echo wp_trim_words(get_the_title(), $title_num_words, '...'); ?></a>
    </h3>
    <?php if ($_display_author) : ?>
      <p class="post-card__author">
        <span class="post-card__author-icon"><?php codetot_svg('user', true); ?></span>
        <span class="post-card__author-label"><?php _e('Post by:', 'ct-bones') ?></span>
        <span class="post-card__author-name"><?php the_author(); ?></span>
      </p>
    <?php endif; ?>
    <?php if ($_display_description) :?>
      <div class="post-card__description"><?php echo codetot_excerpt($word_count); ?></div>
    <?php endif; ?>
  </div>
  <?php if ($_display_footer) : ?>
    <div class="post-card__cta">
      <?php the_block('button', array(
        'class' => 'rel post-card__button',
        'url' => get_permalink(),
        'type' => 'link',
        'button' => __('Read more', 'ct-bones')
      )); ?>
    </div>
  <?php endif; ?>
</article>
