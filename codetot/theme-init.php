<?php

// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_Theme_Init
{
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
  public final static function instance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Class constructor
   */
  private function __construct()
  {
    add_action('after_setup_theme', array($this, 'theme_supports'));
    add_action('widgets_init', array($this, 'register_sidebars'));
    add_action('wp_footer', array($this, 'codetot_extra_blocks'));
    add_filter('body_class', array($this, 'page_body_class'));

    add_filter('walker_nav_menu_start_el', array($this, 'add_arrow_to_primary_menu'), 10, 4);
    add_filter('codetot_search_button', array($this, 'search_button_icon'));
    add_filter( 'default_page_template_title', array($this, 'rename_default_template' ) );
  }

  public function rename_default_template()
  {
    return __('Basic Page', 'barrel-base');
  }

  public function theme_supports()
  {
    load_theme_textdomain('ct-bones', get_template_directory() . '/languages');
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');

    // Thumbnail and image sizes
    add_theme_support('post-thumbnails');
    add_image_size('codetot-mobile', 360, 9999, false);
    add_image_size('codetot-tablet', 600, 9999, false);
    add_image_size('codetot-large-tablet', 1024, 9999, false);
    add_image_size('codetot-desktop', 1440, 9999, false);

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(array(
      'primary' => __('Primary Menu', 'ct-bones')
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

    add_theme_support('customize-selective-refresh-widgets');

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

  public function register_sidebars()
  {
    register_sidebar(
      array(
        'id' => 'post-sidebar',
        'name' => __('Post Sidebar', 'ct-bones'),
        'before_widget' => '<div id="%1$s" class="widget widget--post %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<p class="widget__title">',
        'after_title' => '</p>'
      )
    );

    register_sidebar(
      array(
        'id' => 'page-sidebar',
        'name' => __('Page Sidebar', 'ct-bones'),
        'before_widget' => '<div id="%1$s" class="widget widget--page %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<p class="widget__title">',
        'after_title' => '</p>'
      )
    );

    register_sidebar(
      array(
        'id' => 'category-sidebar',
        'name' => __('Category Sidebar', 'ct-bones'),
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
          'name' => sprintf(__('Footer Column #%s', 'ct-bones'), $i),
          'description' => __('Add widgets to display in footer column.', 'ct-bones'),
          'id' => 'footer-column-' . $i,
          'before_widget' => '<div id="%1$s" class="widget widget--footer %2$s">',
          'after_widget' => '</div>',
          'before_title' => '<p class="widget__title">',
          'after_title' => '</p>',
        )
      );
    }
  }

  public function add_arrow_to_primary_menu($output, $item, $depth, $args)
  {
    if ('primary' == $args->theme_location && $depth === 0) {
      if (in_array("menu-item-has-children", $item->classes)) {
        $output .= '<span class="icon-toggle js-toggle-sub-menu"></span>';
      }
    }
    return $output;
  }

  public function codetot_extra_blocks()
  {
    the_block_part('footer');

    // Sticky blocks
    the_block('slideout-menu');
    the_block_part('modal-search-form');
  }

  public function page_body_class($classes)
  {
    if (!function_exists('rwmb_meta')) {
      return $classes;
    }

    if (is_page() && !empty(rwmb_meta('codetot_page_class'))) {
      $classes[] = esc_attr(rwmb_meta('codetot_page_class'));
    }

    return $classes;
  }

  public function search_button_icon() {
    return sprintf('<button class="search-submit" type="submit" aria-label="%s">%s</button>',
      esc_attr_x( 'Search', 'submit button', 'ct-bones' ),
      codetot_svg('search', false)
    );
  }
}


Codetot_Theme_Init::instance();
