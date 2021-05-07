<?php
$layout = get_global_option('codetot_category_layout') ?? 'sidebar-left';
$post_grid_columns = codetot_get_category_column_number();
$post_card_style = codetot_get_category_post_card_style();
$container = codetot_site_container();
$class = 'category-post page-block page-block--category';
$class .= !empty($layout) ? ' ' . esc_attr($layout) : '';
?>
<div class="<?php echo $class; ?>">
  <div class="<?php echo $container; ?> page-block__container">
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
              'columns' => !empty($post_grid_columns) ? $post_grid_columns : '3',
              'card_style' => !empty($post_card_style) ? $post_card_style : 'style-1'
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
