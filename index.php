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
    /**
     * @hook codetot_layout_post_list_html - 5
     * @hook codetot_layout_post_list_pagination - 10
     */
    do_action('codetot_index_main_layout');

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
