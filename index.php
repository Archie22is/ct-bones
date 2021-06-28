<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CT_Bones
 */

get_header();

$number_columns = get_global_option('codetot_category_column_number') ?? 3;

if (!is_front_page()) {
  the_block('breadcrumbs');
}

?>
<?php do_action('codetot_before_index_main'); ?>
<main id="primary" class="site-main">
  <?php if (have_posts()) : ?>

    <?php if (!is_front_page()) :

      $description = get_the_archive_description();
      ?>
      <header class="mt-05 mb-1 page-header">
        <?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
        <?php if ( $description ) : ?>
          <div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
        <?php endif; ?>
      </header><!-- .page-header -->
    <?php endif; ?>

    <?php
    global $wp_query;

    $columns = [];

    $post_count = $wp_query->post_count;

    while( $wp_query->have_posts() ) : $wp_query->the_post();
      if (
        ($post_count > 3 && count($columns) < 3) ||
        ($post_count < 3)
      ) :
        $columns[] = get_block('post-card',array(
          'card_style' => !empty($card_style) ? $card_style : 'style-1'
        ));
      endif;
    endwhile; wp_reset_postdata();

    $content = codetot_build_grid_columns($columns, 'post-grid', array(
      'column_class' => 'f fdc default-section__col'
    ));

    the_block('default-section', array(
      'class' => 'section default-section--no-container has-3-columns',
      'content' => $content
    ));

    if ($post_count > 3) {
      the_block('post-list', array(
        'class' => 'section default-section--no-container',
        'query' => $wp_query,
        'offset' => 3
      ));
    }

    the_block('pagination');

  else :

    the_block('message-block', array(
      'content' => esc_html__('There is no posts to display.', 'ct-bones')
    ));

  endif;
  ?>
</main><!-- #main -->

<?php do_action('codetot_before_index_sidebar'); ?>
<?php
if (is_category()) {
  get_sidebar();
} else {
  get_sidebar('post-sidebar');
}
?>
<?php do_action('codetot_after_index_main'); ?>

<?php
get_footer();
