<?php
/**
 * The main template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ct-bones
 * @author codetot
 * @since 0.0.1
 */

get_header();

/**
 * @hooks codetot_breadcrumbs_html - 1
 */
do_action( 'codetot_before_index_main' );
?>
<main id="primary" class="site-main">
  	<?php if ( have_posts() ) : ?>
		<?php
		/**
		 * @hook codetot_layout_archive_page_header_html - 1
		 * @hook codetot_layout_post_list_html - 5
		 * @hook codetot_layout_post_list_pagination - 10
		 */
		do_action( 'codetot_index_main_layout' );

  	else :

	  	the_block(
			'message-block',
			array(
				'content' => esc_html__( 'There is no posts to display.', 'ct-bones' ),
			)
		);

  	endif; ?>
</main><!-- #main -->

<?php
do_action( 'codetot_before_index_sidebar' );

if ( is_category() ) :
	get_sidebar();
else :
	get_sidebar( 'post-sidebar' );
endif;

do_action( 'codetot_after_index_main' );

get_footer();
