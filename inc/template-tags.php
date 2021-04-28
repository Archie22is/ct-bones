<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package CT_Bones
 */

if ( ! function_exists( 'ct_bones_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function ct_bones_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

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
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
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

if ( ! function_exists( 'ct_bones_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function ct_bones_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'ct-bones' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'ct-bones' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'ct-bones' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'ct-bones' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

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

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
							'alt' => the_title_attribute(
								array(
									'echo' => false,
								)
							),
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
 * @param null $id
 * @param string $alt
 * @param bool $placeholder
 * @return mixed|string
 */
function codetot_image_alt( $id = null, $alt = '', $placeholder = false ) {
  if ( ! $id ) {
    if ( $placeholder ) {
      return esc_attr__( 'Placeholder image', 'ct-theme' );
    }
    return esc_attr__( 'Error image', 'ct-theme' );
  }

  $data    = get_post_meta( $id, '_wp_attachment_image_alt', true );
  $img_alt = ! empty( $data ) ? $data : $alt;

  return $img_alt;
}

/**
 * codetot_excerpt
 */
function codetot_excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }
  $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
  return $excerpt;
}

if ( ! function_exists( 'codetot_header_class' ) ) {
  /**
   * Header class
   */
  function codetot_header_class() {
    $class[] = 'header';

    $header_layout = !empty(get_global_option('codetot_header_layout')) ? str_replace('header-', '', get_global_option('codetot_header_layout')) : '1';
    $header_style_number = !empty($header_layout) ? str_replace('style-', '', esc_attr($header_layout)) : '1';
    $class[] = apply_filters( 'codetot_header_layout_classes', 'header--layout-' . $header_style_number );

    $enable_header_transparent = is_page() && function_exists('rwmb_meta') ? rwmb_meta('codetot_enable_header_transparent') : false;
    if ($enable_header_transparent) {
      $class[] = 'header--transparent';
    }

    $header_background_color = get_global_option('codetot_header_background_color') ?? 'white';
    $class[] = !empty($header_background_color) && $header_background_color !== 'bg-white' ? 'header--has-bg bg-' . esc_attr($header_background_color) : 'bg-white';

    $text_contract_color = get_global_option('codetot_header_color_contract') ?? 'light';
    $class[] = !empty($text_contract_color) ? 'header--' . esc_attr($text_contract_color) . '-contract' : 'header--dark-contract';

    $class = implode( ' ', array_filter( $class ) );

    return esc_attr( $class );
  }
}

if ( !function_exists('codetot_logo_or_site_title') ) {
  /**
   * @param bool $echo
   * @return string
   */
  function codetot_logo_or_site_title($echo = false) {
    if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
      // Image logo.
      $logo = get_custom_logo();
      $html = is_home() ? '<h1 class="logo">' . $logo . '</h1>' : $logo;
    } else {
      $tag = is_home() ? 'h1' : 'div';

      $html  = '<' . esc_attr( $tag ) . ' class="site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a></' . esc_attr( $tag ) . '>';
    }

    if ( ! $echo ) {
      return $html;
    }

    echo $html; // phpcs:ignore
  }
}

if (! function_exists('codetot_page_breadcrumbs') ) {
  function codetot_page_breadcrumbs() {
    the_block('breadcrumbs');
  }
}

if (! function_exists('codetot_page_header')) {
    /**
     * Display the post title
     */
    function codetot_page_header()
    {
      the_block('page-header', array(
        'title' => get_the_title()
      ));
    }
}

if ( ! function_exists( 'codetot_page_content' ) ) {
	/**
	 * Display the post content
	 */
	function codetot_page_content() {
    ob_start();
		the_content();

		wp_link_pages(
			array(
				'before'      => '<div class="page-links">' . __( 'Pages:', 'ct-theme' ),
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			)
		);

    $content = ob_get_clean();

    the_block('default-section', array(
      'class' => 'section page-content',
      'content' => $content
    ));
	}
}

if ( ! function_exists( 'codetot_display_comments' ) ) {
	/**
	 * Display comments
	 */
	function codetot_display_comments() {
		if ( is_single() || is_page() ) {
      ob_start();
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

      $content = ob_get_clean();

      the_block('default-section', array(
        'class' => 'section page-comments',
        'content' => $content
      ));
		}
	}
}
