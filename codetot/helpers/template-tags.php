<?php

/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Codetot
 * @since 0.0.1
 */

if ( ! function_exists( 'ct_bones_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function ct_bones_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
		/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'ct-bones' ),
			$time_string
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'ct_bones_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function ct_bones_posted_by() {
		$byline = sprintf(
		/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'ct-bones' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

function ct_bones_entry_categories() {
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'ct-bones' ) );
		if ( $categories_list ) {
			/* translators: 1: list of categories. */
			printf( '<span class="cat-links">' . esc_html__( 'Category: %1$s', 'ct-bones' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

function ct_bones_entry_tags() {
	/* translators: used between list items, there is a space after the comma */
	$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'ct-bones' ) );
	if ( $tags_list ) {
		echo '<div class="entry-tags">';
		/* translators: 1: list of tags. */
		printf( '<span class="entry-tags__label">%s</span> ', esc_html__( 'Tags:', 'ct-bones' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( '<span class="entry-tags__list">%s</span>', $tags_list );
		echo '</div>';
	}
}

function ct_bones_entry_comment_links() {
	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link(
			sprintf(
				wp_kses(
				/* translators: %s: post title */
					__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'ct-bones' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);
		 echo '</span>';
	}
}

if ( ! function_exists( 'ct_bones_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function ct_bones_entry_footer() {
		edit_post_link(
			sprintf(
				wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'ct-bones' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'ct_bones_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function ct_bones_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>
			<?php
			the_block(
				'image',
				array(
					'class'    => 'image--default entry-thumbnail__image',
					'lazyload' => false,
					'image'    => get_post_thumbnail_id(),
				)
			);
			?>

		<?php else : ?>

	  <a class="entry-thumbnail__link" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php
			the_block(
				'image',
				array(
					'class' => 'image--default entry-thumbnail__image',
					'image' => get_post_thumbnail_id(),
				)
			);
			?>
	  </a>

			<?php
	  endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;

/**
 * @param null   $id
 * @param string $alt
 * @param bool   $placeholder
 * @return mixed|string
 */
function codetot_image_alt( $id = null, $alt = '', $placeholder = false ) {
	if ( ! $id ) {
		if ( $placeholder ) {
			return esc_attr__( 'Placeholder image', 'ct-bones' );
		}
		return esc_attr__( 'Error image', 'ct-bones' );
	}

	$data    = get_post_meta( $id, '_wp_attachment_image_alt', true );
	$img_alt = ! empty( $data ) ? $data : $alt;

	return $img_alt;
}

/**
 * codetot_excerpt
 */
function codetot_excerpt( $limit ) {
	$excerpt = explode( ' ', get_the_excerpt(), $limit );
	if ( count( $excerpt ) >= $limit ) {
		array_pop( $excerpt );
		$excerpt = implode( ' ', $excerpt ) . '...';
	} else {
		$excerpt = implode( ' ', $excerpt );
	}
	$excerpt = preg_replace( '`[[^]]*]`', '', $excerpt );
	return $excerpt;
}

if ( ! function_exists( 'codetot_header_class' ) ) {
	/**
	 * Header class
	 */
	function codetot_header_class() {
		$class[] = 'header';

		$header_layout        = codetot_get_theme_mod( 'header_layout' ) ?? 'header-1';
		$header_layout_number = str_replace( 'header-', '', $header_layout );
		$class[]              = apply_filters( 'codetot_header_layout_classes', 'header--layout-' . $header_layout_number );

		$header_background_color = codetot_get_theme_mod( 'header_background_color' ) ?? 'white';
		$class[]                 = ! empty( $header_background_color ) && $header_background_color !== 'bg-white' ? 'header--has-bg bg-' . esc_attr( $header_background_color ) : 'bg-white';

		$text_contract_color = codetot_get_theme_mod( 'header_text_contract' ) ?? 'light';
		$class[]             = ! empty( $text_contract_color ) ? 'header--' . sanitize_text_field( $text_contract_color ) . '-contract' : 'header--light-contract';

		$_class = apply_filters( 'codetot_header_classes', $class );

		$_class = implode( ' ', array_filter( $_class ) );

		return esc_attr( $_class );
	}
}

if ( ! function_exists( 'codetot_logo_or_site_title' ) ) {
	/**
	 * @param bool $echo
	 * @return string
	 */
	function codetot_logo_or_site_title( $echo = false ) {
		$homepage_heading    = codetot_get_theme_mod( 'seo_h1_homepage', 'pro' ) ?? false;
		$is_homepage_heading = is_front_page() && $homepage_heading === '1';
		$titleAttribute      = $is_homepage_heading ? 'title="' . get_the_title( get_option( 'page_on_front' ) ) . '"' : '';
		$tag                 = $is_homepage_heading && is_front_page() ? 'h1' : 'div';

		if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			// Image logo.
			$logo = get_custom_logo();
			$html = '<' . esc_attr( $tag ) . ' class="header__logo">' . ' <span class="screen-reader-text">' . get_the_title( get_option( 'page_on_front' ) ) . '</span>' . $logo . '</' . esc_attr( $tag ) . '>';

		} else {
			$html = '<' . esc_attr( $tag ) . ' class="site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home"' . $titleAttribute . '>' . esc_html( get_bloginfo( 'name' ) ) . '</a></' . esc_attr( $tag ) . '>';
		}

		if ( ! $echo ) {
			return $html;
		}

	  echo $html; // phpcs:ignore
	}
}

if ( ! function_exists( 'codetot_sidebar_id' ) ) {
	/**
	 * Get sidebar class
	 *
	 * @return string $sidebar Class name
	 */
	function codetot_sidebar_id() {
		$sidebar = '';

		if ( is_404() || ( class_exists( 'woocommerce' ) && ( is_cart() || is_checkout() || is_account_page() ) ) ) {
			return $sidebar;
		}

		if ( class_exists( 'woocommerce' ) && ( is_shop() || is_product_taxonomy() ) ) {
			if ( is_product_taxonomy() ) {
				$sidebar_layout = codetot_get_theme_mod( 'product_category_layout', 'woocommerce' ) ?? 'no-sidebar';
				return $sidebar_layout !== 'no-sidebar' ? 'product-category-sidebar' : 'no-sidebar';
			} elseif ( is_shop() ) {
				$sidebar_layout = codetot_get_theme_mod( 'shop_layout', 'woocommerce' ) ?? 'no-sidebar';
				return $sidebar_layout !== 'no-sidebar' ? 'shop-sidebar' : 'no-sidebar';
			}
		} elseif ( class_exists( 'woocommerce' ) && is_singular( 'product' ) ) {
			$sidebar_layout = codetot_get_theme_mod( 'product_layout', 'woocommerce' ) ?? 'no-sidebar';

			// Product page.
			return $sidebar_layout !== 'no-sidebar' ? 'product-sidebar' : 'no-sidebar';
		} elseif ( is_page() ) {
			$sidebar_layout = codetot_get_theme_mod( 'page_layout' ) ?? 'no-sidebar';

			return $sidebar_layout !== 'no-sidebar' ? 'page-sidebar' : 'no-sidebar';

		} elseif ( is_category() ) {
			$sidebar_layout = codetot_get_theme_mod( 'category_layout' ) ?? 'sidebar-right';

			return $sidebar_layout !== 'no-sidebar' ? 'category-sidebar' : 'no-sidebar';

		} elseif ( is_singular( 'post' ) || is_front_page() || is_tag() || is_author() || is_date() ) {
			$sidebar_layout = codetot_get_theme_mod( 'post_layout' ) ?? 'no-sidebar';

			// Post page.
			return 'post-sidebar';
		}

		$blog_page_id = get_option( 'page_for_posts' );

		if ( ! empty( $blog_page_id ) && is_home() ) {
			return 'post-sidebar';
		}

		return $sidebar;
	}
}

if ( ! function_exists( 'codetot_get_sidebar' ) ) {
	/**
	 * Display woostify sidebar
	 *
	 * @uses get_sidebar()
	 */
	function codetot_get_sidebar() {
		$sidebar = codetot_sidebar_id();

		if ( false !== strpos( $sidebar, 'no-sidebar' ) || ! $sidebar ) {
			return;
		}

		get_sidebar();
	}
}

/**
 * @return string
 */
function codetot_get_footer_copyright() {
	$data = codetot_get_theme_mod( 'footer_copyright_text' ) ?? null;

	return ! empty( $data )
	? $data
	: '<p>' . sprintf(
		__( 'Copyright &copy; by <strong>%1$s</strong>. Built with <a href="%2$s" target="_blank" rel="sponsored noopener">%3$s</a> (version: %4$s).', 'ct-bones' ) . '</p>',
		get_bloginfo( 'name' ),
		esc_url( 'https://codetot.com' ),
		esc_html__( 'CT Web Builder', 'ct-bones' ),
		CODETOT_VERSION
	);
}
