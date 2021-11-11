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
	$category_html = '<ul class="hero-image__post-meta">';

	if ( ! empty( $categories ) ) {
		foreach ( $categories as $category ) :
			$category_html .= sprintf(
				'<li class="hero-image__post-meta__item"><a class="hero-image__post-meta__link" href="%1$s">%2$s</a></li>',
				get_term_link( $category, 'category' ),
				$category->name
			);
		endforeach;
	}

	$category_html .= '</ul>';

	the_block(
		'hero-image',
		array(
			'label'               => $category_html,
			'title'               => $post->post_title,
			'class'               => 'hero-image--single-post',
			'image'               => get_post_thumbnail_id(),
			'spacing'             => 'large',
			'background_contract' => 'dark',
			'content_alignment'   => 'center',
			'overlay'             => '0.4',
		)
	);

	?>

	<?php 
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

<?php do_action( 'codetot_sidebar' ); ?>

<?php
echo '</div>'; // Close .page-block__col--sidebar
echo '</div>'; // Close .page-block__grid
echo '</div>'; // Close .page-block__container
echo '</div>'; // Close .page-block

get_footer();
