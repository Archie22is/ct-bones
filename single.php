<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package CT_Bones
 */

get_header();
?>

<main id="primary" class="site-main">
  <?php

  if (is_singular('post')) :
    do_action('codetot_before_post');
  endif;

  while (have_posts()) :
    the_post();

    get_template_part('template-parts/content', get_post_type());

    if (is_singular('post')) :
      do_action('codetot_after_content_post');
    endif;

    // If comments are open or we have at least one comment, load up the comment template.
    if (comments_open() || get_comments_number()) :
      comments_template();
    endif;

  endwhile; // End of the loop.

  if (is_singular('post')) :
    do_action('codetot_after_post');
  endif;
  ?>

</main><!-- #main -->

<?php do_action('codetot_sidebar'); ?>

<?php
get_footer();
