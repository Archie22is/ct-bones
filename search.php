<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package CT_Bones
 */

get_header();

global $wp_query;
$post_card_style = codetot_get_category_post_card_style();
$number_columns = get_global_option('codetot_category_column_number') ?? 3;
?>

<main id="primary" class="site-main">

  <?php
  the_block('breadcrumbs');

  if (have_posts()) :
    global $wp_query;

    the_block('page-header', array(
      'class' => 'mt-1 page-header--search',
      /* translators: %s: search query. */
      'title' => sprintf(esc_html__('Search Results for: %s', 'ct-bones'), '<span>' . get_search_query() . '</span>')
    ));

    if (!class_exists('WooCommerce')) :

      $columns = [];
      while( $wp_query->have_posts() ) : $wp_query->the_post();
        $columns[] = get_block('post-card',array(
          'card_style' => !empty($card_style) ? $card_style : 'style-1'
        ));
      endwhile; wp_reset_postdata();

      $content = codetot_build_grid_columns($columns, 'post-grid', array(
        'column_class' => 'f fdc default-section__col'
      ));

      printf('<div class="mt-1 site-main__main-category default-section %s">', 'has-'. esc_attr($number_columns) . '-columns');
      echo '<div class="container">';
      echo $content;
      echo '</div>';
      // the_block('pagination');
      echo '</div>';

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
