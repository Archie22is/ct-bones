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
?>

<main id="primary" class="site-main">

  <?php
  if (have_posts()) :

    global $wp_query;

    /* Start the Loop */
    the_block('post-grid', array(
      'class' => 'mt-2 mb-2 post-grid--default',
      'columns' => 3,
      'query' => $wp_query
    ));

    the_block('pagination');

  else :

    the_block('message-block', array(
      'content' => esc_html__('There is no posts to display.', 'ct-bones')
    ));

  endif;
  ?>

</main><!-- #main -->

<?php do_action('codetot_sidebar'); ?>

<?php
get_footer();
