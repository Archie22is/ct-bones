<?php

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Codetot_Theme_Init {

	/**
	 * Singleton instance
	 *
	 * @var Codetot_Theme_Init
	 */
	private static $instance;

	/**
	 * Get singleton instance.
	 *
	 * @return Codetot_Theme_Init
	 */
	final public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Class constructor
	 */
	private function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
		add_action( 'wp_footer', array( $this, 'codetot_extra_blocks' ) );

		add_filter( 'walker_nav_menu_start_el', array( $this, 'add_arrow_to_primary_menu' ), 10, 4 );
		add_filter( 'codetot_search_button', array( $this, 'search_button_icon' ) );
		add_filter( 'default_page_template_title', array( $this, 'rename_default_template' ) );
	}

	public function rename_default_template() {
		return esc_html__( 'Basic Page', 'ct-bones' );
	}

	public function theme_supports() {
		load_theme_textdomain( 'ct-bones', get_template_directory() . '/languages' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );

		// Thumbnail and image sizes
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'ct-bones' ),
			)
		);

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

	public function register_sidebars() {
		$post_layout = codetot_get_theme_mod( 'post_layout' ) ?? 'no-sidebar';

		if ( $post_layout !== 'no-sidebar' ) :
			register_sidebar(
				array(
					'id'            => 'post-sidebar',
					'name'          => __( 'Post Sidebar', 'ct-bones' ),
					'before_widget' => '<div id="%1$s" class="widget widget--post %2$s">',
					'after_widget'  => '</div><!-- Close widget -->',
					'before_title'  => '<p class="widget__title">',
					'after_title'   => '</p>',
				)
			);
	  endif;

		$page_layout = codetot_get_theme_mod( 'page_layout' ) ?? 'no-sidebar';

		if ( $page_layout !== 'no-sidebar' ) :
			register_sidebar(
				array(
					'id'            => 'page-sidebar',
					'name'          => __( 'Page Sidebar', 'ct-bones' ),
					'before_widget' => '<div id="%1$s" class="widget widget--page %2$s">',
					'after_widget'  => '</div><!-- Close widget -->',
					'before_title'  => '<p class="widget__title">',
					'after_title'   => '</p>',
				)
			);
	  endif;

		$archive_layout = codetot_get_theme_mod( 'category_layout' ) ?? 'no-sidebar';

		if ( $archive_layout !== 'no-sidebar' ) :
			register_sidebar(
				array(
					'id'            => 'category-sidebar',
					'name'          => __( 'Category Sidebar', 'ct-bones' ),
					'before_widget' => '<div id="%1$s" class="widget widget--category %2$s">',
					'after_widget'  => '</div><!-- Close widget -->',
					'before_title'  => '<p class="widget__title">',
					'after_title'   => '</p>',
				)
			);
	  endif;

		$footer_widget_column = codetot_get_theme_mod( 'footer_widget_column' ) ?? '3-col';
		$footer_widget_column = str_replace( '-col', '', $footer_widget_column );

		if ( $footer_widget_column > 0 ) :
			for ( $i = 1; $i <= $footer_widget_column; $i++ ) {
				register_sidebar(
					array(
						'name'          => sprintf( __( 'Footer Column #%s', 'ct-bones' ), $i ),
						'description'   => __( 'Add widgets to display in footer column.', 'ct-bones' ),
						'id'            => 'footer-column-' . $i,
						'before_widget' => '<div id="%1$s" class="widget widget--footer %2$s">',
						'after_widget'  => '</div><!-- Close widget -->',
						'before_title'  => '<p class="widget__title">',
						'after_title'   => '</p>',
					)
				);
			}
	  endif;

		$enable_topbar_widget = codetot_get_theme_mod( 'enable_topbar_widget' ) ?? false;
		$topbar_widget_column = codetot_get_theme_mod( 'topbar_widget_column' ) ?? '1-col';
		$topbar_widget_column = str_replace( '-col', '', $topbar_widget_column );


		if ( $enable_topbar_widget ) {
			for ( $i = 1; $i <= $topbar_widget_column; $i++ ) {
				register_sidebar(
					array(
						'name'          => sprintf( __( 'Topbar Column #%s', 'ct-bones' ), $i ),
						'description'   => __( 'Add widgets to display in topbar column.', 'ct-bones' ),
						'id'            => 'topbar-column-' . $i,
						'before_widget' => '<div id="%1$s" class="widget widget--topbar %2$s">',
						'after_widget'  => '</div><!-- Close widget -->',
						'before_title'  => '<p class="widget__title">',
						'after_title'   => '</p>',
					)
				);
			}
		}
	}

	public function add_arrow_to_primary_menu( $output, $item, $depth, $args ) {
		if ( 'primary' == $args->theme_location && $depth <= 2 ) {
			if ( in_array( 'menu-item-has-children', $item->classes ) ) {
				$output .= '<span class="icon-toggle js-toggle-sub-menu"></span>';
			}
		}
		return $output;
	}

	public function codetot_extra_blocks() {
		the_block_part( 'footer' );

		// Sticky blocks
		the_block( 'slideout-menu' );
		the_block_part( 'modal-search-form' );
	}

	public function search_button_icon() {
		return sprintf(
			'<button class="search-submit" type="submit" aria-label="%s">%s</button>',
			esc_attr_x( 'Search', 'submit button', 'ct-bones' ),
			codetot_svg( 'search', false )
		);
	}
}


Codetot_Theme_Init::instance();
