<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package CT_Bones
 */

get_header();

$sidebar_layout    = codetot_get_theme_mod( 'post_layout' ) ?? 'right-sidebar';
$enable_hero_image = codetot_get_theme_mod( 'extra_single_post_layout', 'pro' ) ?? 'none';

if ( is_singular( 'post' ) && $enable_hero_image ) :
	$categories    = get_the_category();
	$category_html = '<ul class="list-reset has-white-color has-text-color block-cover__list">';

	if ( ! empty( $categories ) ) {
		foreach ( $categories as $category ) :
			$category_html .= sprintf(
				'<li class="block-cover__item"><a class="has-white-color has-text-color block-cover__list__link" href="%1$s">%2$s</a></li>',
				get_term_link( $category, 'category' ),
				$category->name
			);
		endforeach;
	}

	$category_html .= '</ul>';

	ob_start();
	echo $category_html;
	printf('<h1 class="has-heading-1-font-size has-text-color has-white-color block-cover__title">' . esc_html($post->post_title) . '</h1>');
	$content = ob_get_clean();

	get_template_part('template-parts/block', 'cover', array(
		'lazy' => false,
		'class' => 'has-text-align-center block-cover--single',
		'image' => get_post_thumbnail_id(),
		'content' => $content
	));
endif;
the_block( 'breadcrumbs' );
echo codetot_layout_page_block_open( 'page-block--page ' . $sidebar_layout, false );

?>

<main id="primary" class="site-main">
	<?php
	if ( is_singular( 'post' ) ) :
		do_action( 'codetot_before_post' );
	endif;

	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/content', get_post_type() );

		if ( is_singular( 'post' ) ) :
			do_action( 'codetot_after_content_post' );
	endif;

		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
	endif;

	endwhile; // End of the loop.

	if ( is_singular( 'post' ) ) :
		do_action( 'codetot_after_post' );
	endif;

	codetot_layout_page_block_between_html();
	?>

</main><!-- #main -->

<?php
do_action( 'codetot_sidebar' );

echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';

get_footer();
