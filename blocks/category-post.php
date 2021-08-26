<?php
$layout          = codetot_get_theme_mod('category_layout') ?? 'sidebar-left';
$post_column     = codetot_get_theme_mod('archive_post_column') ?? 3;
$post_card_style = codetot_get_theme_mod('post_card_style') ?? 'style-default';

$_class = 'category-post page-block page-block--category';
$_class .= !empty($layout) ? ' ' . esc_attr($layout) : '';
$_class .= !empty($class) ? ' ' . esc_html($class) : '';
?>
<div class="<?php echo $_class; ?>">
  <div class="container page-block__container">
    <div class="grid page-block__grid">
      <?php if (!empty($layout) && $layout !== 'no-sidebar' && is_active_sidebar('category-sidebar')) : ?>
        <div class="page-block__col page-block__col--sidebar">
          <div class="page-block__inner">
            <?php dynamic_sidebar('category-sidebar'); ?>
          </div>
        </div>
      <?php endif; ?>
      <div class="page-block__col page-block__col--main">
        <div class="page-block__inner">
          <?php
          if (!isset($hide_title)) :
            the_block('page-header', array(
              'class' => !empty($layout) && $layout === 'no-sidebar' ? 'page-header--no-container' : '',
              'title' => single_cat_title('', false)
            ));
          endif;

          if (!empty($query) && $query->have_posts()) :
            the_block('post-grid', array(
              'query' => $query,
              'columns' => $post_column,
              'card_style' => $post_card_style
            ));

            the_block('pagination');

          else :

            the_block('message-block', array(
              'content' => esc_html__('There is no posts to display.', 'ct-bones')
            ));

          endif;
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
