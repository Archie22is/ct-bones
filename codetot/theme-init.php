<?php

if ( ! function_exists( 'ct_bones_setup' ) ) :
	function ct_bones_setup() {
		load_theme_textdomain( 'ct-bones', get_template_directory() . '/languages' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );

    // Thumbnail and image sizes
    add_theme_support('post-thumbnails');
    add_image_size('codetot-mobile', 360, 9999, false);
    add_image_size('codetot-tablet', 600, 9999, false);
    add_image_size('codetot-large-tablet', 1024, 9999, false);
    add_image_size('codetot-desktop', 1440, 9999, false);

		// This theme uses wp_nav_menu() in one location.
    register_nav_menus(array(
      'primary' => __('Primary Menu', 'ct-theme')
    ));

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'ct_bones_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		add_theme_support( 'customize-selective-refresh-widgets' );

		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'ct_bones_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function ct_bones_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'ct_bones_content_width', 640 );
}
add_action( 'after_setup_theme', 'ct_bones_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function ct_bones_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'ct-bones' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'ct-bones' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'ct_bones_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function ct_bones_scripts() {
	wp_enqueue_style( 'ct-bones-default-style', get_stylesheet_uri(), array(), CODETOT_VERSION );
	wp_enqueue_script( 'ct-bones-navigation', get_template_directory_uri() . '/js/navigation.js', array(), CODETOT_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ct_bones_scripts' );

function ct_bones_register_sidebars() {
  register_sidebar(
    array(
      'id' => 'post-sidebar',
      'name' => __('Post Sidebar', 'ct-theme'),
      'before_widget' => '<div id="%1$s" class="widget widget--post %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<p class="widget__title">',
      'after_title' => '</p>'
    )
  );

  register_sidebar(
    array(
      'id' => 'page-sidebar',
      'name' => __('Page Sidebar', 'ct-theme'),
      'before_widget' => '<div id="%1$s" class="widget widget--page %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<p class="widget__title">',
      'after_title' => '</p>'
    )
  );

  register_sidebar(
    array(
      'id' => 'category-sidebar',
      'name' => __('Category Sidebar', 'ct-theme'),
      'before_widget' => '<div id="%1$s" class="widget widget--category %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<p class="widget__title">',
      'after_title' => '</p>'
    )
  );
  $footer_column = get_global_option('codetot_footer_columns') ? str_replace('-columns', '', get_global_option('codetot_footer_columns')) : 3;
  for ($i = 1; $i <= $footer_column; $i++) {
    register_sidebar(
      array(
        'name' => sprintf(__('Footer Column #%s', 'ct-theme'), $i),
        'description' => __('Add widgets to display in footer column.', 'ct-theme'),
        'id' => 'footer-column-' . $i,
        'before_widget' => '<div id="%1$s" class="widget widget--footer %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<p class="widget__title">',
        'after_title' => '</p>',
      ));
  }
}
add_action('widgets_init', 'ct_bones_register_sidebars');

function codetot_add_arrow_to_menu( $output, $item, $depth, $args ){
  if('primary' == $args->theme_location && $depth === 0 ){
      if (in_array("menu-item-has-children", $item->classes)) {
          $output .='<span class="icon-toggle js-toggle-sub-menu"></span>';
      }
  }
    return $output;
}
add_filter( 'walker_nav_menu_start_el', 'codetot_add_arrow_to_menu', 10, 4);

add_action('wp_footer', 'codetot_bottom_blocks');
function codetot_bottom_blocks() {
  the_block_part('footer');

  // Sticky blocks
  the_block('slideout-menu');
  the_block_part('modal-search-form');
}


function codetot_page_body_class($classes) {
  if (!function_exists('rwmb_meta')) {
    return $classes;
  }

  if (is_page() && !empty(rwmb_meta('codetot_page_class'))) {
    $classes[] = esc_attr(rwmb_meta('codetot_page_class'));
  }

  return $classes;
}
add_filter('body_class', 'codetot_page_body_class');
