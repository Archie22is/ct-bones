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
<?php
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

  endif;
	?>
</main><!-- #main -->

<?php do_action( 'codetot_before_index_sidebar' ); ?>
<?php
if ( is_category() ) {
	get_sidebar();
} else {
	get_sidebar( 'post-sidebar' );
}
?>
<?php do_action( 'codetot_after_index_main' ); ?>

<?php
get_footer();
