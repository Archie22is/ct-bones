<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package CT_Bones
 */

get_header();
?>

	<main id="primary" class="site-main">
	<?php
	the_block(
		'page-header',
		array(
			'class' => 'page-header--404',
			'title' => esc_html__( 'Oops! That page can&rsquo;t be found.', 'ct-bones' ),
		)
	);

	ob_start();
	echo '<p>';
	esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'ct-bones' );
	echo '</p>';
	echo get_search_form(
		array(
			'id' => '404',
		)
	);

	the_block(
		'button',
		array(
			'button' => __( 'Back to Homepage', 'ct-bones' ),
			'type'   => 'dark',
			'class'  => 'message-block__404-button',
			'url'    => esc_url( home_url( '/' ) ),
		)
	);

	$content = ob_get_clean();

	the_block(
		'message-block',
		array(
			'class'   => 'message-block--404',
			'content' => $content,
		)
	);

	?>
	</main><!-- #main -->
<?php
get_footer();
