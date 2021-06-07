<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package CT_Bones
 */

get_header();
?>

<main id="primary" class="site-main">

  <?php
  the_block('breadcrumbs');
  ?>

  <?php if (have_posts()) :
    global $wp_query;
  ?>

    <?php the_block('page-header', array(
      'class' => 'page-header--search',
      /* translators: %s: search query. */
      'title' => sprintf(esc_html__('Search Results for: %s', 'ct-bones'), '<span>' . get_search_query() . '</span>')
    )); ?>

  <?php
    if (!class_exists('WooCommerce')) :

      the_block('post-grid', array(
        'class' => 'post-grid--search',
        'query' => $wp_query
      ));

    else :

      the_block('product-grid', array(
        'class' => 'section product-grid--search',
        'query' => $wp_query
      ));

    endif;

    the_block('pagination');

  else :

    ob_start();

    the_block('page-header', array(
      'class' => 'page-header--search page-header--search-not-found',
      'title' => apply_filters('codetot_404_title', sprintf(__('No Result for keyword %s', 'ct-bones'), '<span>' . get_search_query() . '</span>'))
    ));

    the_block('message-block', array(
      'class' => 'message-block--search',
      'content' => apply_filters('codetot_404_content', sprintf(__('It seems we can\'t find any %s matching your search keyword.', 'ct-bones'), 'post'))
    ));

  endif;
  ?>

</main><!-- #main -->

<?php do_action('codetot_sidebar'); ?>

<?php
get_footer();
