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
$post_column = codetot_get_theme_mod( 'archive_post_column' ) ?? 3;
?>

<main id="primary" class="mb-2 site-main">

	<?php
  if ( have_posts() ) :
    global $wp_query;

    if ( ! class_exists( 'WooCommerce' ) ) :

			the_block('page-header', array(
				'class' => 'section wp-block-group',
				'title' => sprintf(esc_html__('Search Results for: %s', 'ct-bones'), '<span>' . get_search_query() . '</span>')
			));

			the_block('post-list', array(
				'query' => $wp_query
			));

    else :

      $post_column = apply_filters( 'loop_shop_columns', 4 );

      the_block(
				'product-grid',
				array(
					'class' => 'section product-grid--search',
					'loop_args' => array(
						'name' => 'search_products'
					),
					'columns' => $post_column,
					'query' => $wp_query
				)
			);

    endif;

		if ( $wp_query->max_num_pages > 1) :
			the_block( 'pagination' );
		endif;

  else :

    the_block( 'page-header' ,
			array(
				'class' => 'page-header--search page-header--search-not-found',
				'title' => apply_filters( 'codetot_404_title', sprintf( __( 'No Result for keyword %s', 'ct-bones' ), '<span>' . get_search_query() . '</span>' ) )
			)
		);

    the_block('message-block', array(
      'class' => 'message-block--search',
      'content' => apply_filters( 'codetot_404_content', sprintf( __( 'It seems we can\'t find any %s matching your search keyword.', 'ct-bones' ), 'post') )
    ));

  endif;
?>

</main><!-- #main -->

<?php do_action( 'codetot_sidebar' ); ?>

<?php

get_footer();
