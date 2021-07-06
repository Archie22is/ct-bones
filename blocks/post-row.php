<?php
$content_num_words = apply_filters('codetot_post_row_excerpt_words', 30);
$_display_date = apply_filters('codetot_post_row_display_date', true);
$_display_category = apply_filters('codetot_post_row_display_category', true);

$_format_date = !empty($format_date) ? $format_date : get_option('format_date');
$post_date = get_the_date($_format_date);

$categories = get_the_category();
$category = !empty($categories) ? $categories[0] : '';
$category_html = sprintf('<a class="post-row__meta-link" href="%1$s">%2$s</a>', get_category_link($category), esc_attr($category->name));
?>

<article class="w100 post-row">
  <div class="f post-row__grid">
    <div class="f ais post-row__col post-row__col--image">
      <a class="w100 f fdc post-row__image-wrapper" href="<?php the_permalink(); ?>">
        <?php
        if (has_post_thumbnail()) :
          the_block('image', array(
            'image' => get_post_thumbnail_id(),
            'class' => 'image--cover image--hd post-row__image'
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
      <p class="mt-05 post-row__meta">
        <?php if ($_display_date) : ?>
          <span class="post-row__meta-date"><?php echo $post_date; ?></span>
        <?php endif; ?>
        <?php if ($_display_date && $_display_category && !empty($category)) : ?>
          <span class="post-row__meta-separator">|</span>
        <?php endif; ?>
        <?php if ($_display_category && !empty($category)) : ?>
          <span class="post-row__meta-category"><?php echo $category_html; ?></span>
        <?php endif; ?>
      </p>
      <?php
      // Short Description
      $excerpt = get_the_excerpt() ? wp_trim_words(get_the_excerpt(), $content_num_words, '...') : '';
      if (!empty($excerpt)) : ?>
        <div class="mt-05 w100 post-row__description"><?php echo $excerpt; ?></div>
      <?php endif; ?>
    </div>
  </div>
</article>
