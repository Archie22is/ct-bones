<?php

// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_Theme_Layout
{
  /**
   * Singleton instance
   *
   * @var Codetot_Theme_Layout
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Theme_Layout
   */
  final public static function instance()
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
    add_action( 'codetot_sidebar', 'codetot_get_sidebar', 10 );
    add_action('codetot_after_content_post', array($this, 'codetot_share_button'), 5);

    $is_not_flexible_page = get_page_template_slug( get_the_ID()) !== 'flexible';
    $is_not_woocommerce_pages = class_exists('WooCommerce') ? (!is_account_page() && !is_cart() && !is_checkout()) : !class_exists('WooCommerce');

    if (
      is_page() &&
      $is_not_flexible_page &&
      $is_not_woocommerce_pages
    ) {
      $this->generate_page_layout();
    }

    if (is_singular('post')) {
      $this->generate_post_layout();
    }

    $this->generate_default_index_layout();
  }

  public function load_page_header() {
    $sidebar_layout = get_global_option('codetot_page_layout') ?? 'left-sidebar';
    $header_class = $sidebar_layout !== 'no-sidebar' ? 'page-header--no-container' : ' mt-1';
    $header_class .= ' mb-1';

    the_block('page-header', array(
      'class' => $header_class,
      'title' => get_the_title()
    ));
  }

  public function load_breadcrumbs() {
    the_block('breadcrumbs');
  }

  public function generate_page_layout() {
    $sidebar_layout = get_global_option('codetot_page_layout') ?? 'no-sidebar';

    if ( !is_front_page() ) {
      add_action('codetot_after_header', array($this, 'load_breadcrumbs'), 9);
    }
    add_action('codetot_after_header', function() use($sidebar_layout) {
      do_action('codetot_before_page_block');

      if ($sidebar_layout !== 'no-sidebar') {
        echo $this->page_block_open('page-block--page ' . $sidebar_layout, false);
      }
    }, 10);

    add_action('codetot_page', array($this, 'load_page_header'), 20);
    add_action('codetot_page', function() use($sidebar_layout) {
      ob_start();

      echo '<div class="wysiwyg">';
      the_content();
      echo '</div>';
      wp_link_pages(
        array(
          'before'      => '<div class="page-links">' . __( 'Pages:', 'ct-bones' ),
          'after'       => '</div>',
          'link_before' => '<span>',
          'link_after'  => '</span>',
        )
      );

      $content = ob_get_clean();

      if ($sidebar_layout !== 'no-sidebar') {
        echo $content;
      } else {
        the_block('default-section', array(
          'class' => 'section page-content page-content--no-sidebar',
          'content' => $content
        ));
      }

    }, 30);
    add_action('codetot_page', function() use($sidebar_layout) {
      $content = $this->generate_comments();

      if ($sidebar_layout !== 'no-sidebar') {
        echo '<div class="page-comments">';
        comments_template();
        echo '</div>';
      } else {
        the_block('default-section', array(
          'class' => 'section page-comments',
          'content' => $content
        ));
      }
    }, 40);

    add_action('codetot_before_sidebar', function() {
      echo $this->page_block_between();
    }, 10);

    add_action('codetot_footer', function() {
      echo $this->page_block_close();
    }, 10);
  }

  public function generate_post_layout() {
    $sidebar_layout = get_global_option('codetot_post_layout');
    add_action('codetot_after_header', array($this, 'load_breadcrumbs'), 9);
    add_action('codetot_after_header', function() use ($sidebar_layout) {
        echo $this->page_block_open('page-block--page ' . $sidebar_layout, false);
    }, 10);
    add_action('codetot_before_sidebar', function() {
      echo $this->page_block_between();
    }, 10);

    add_action('codetot_footer', function() {
      echo $this->page_block_close();
    }, 10);
  }

  public function codetot_share_button() {
    $hide_social_share = get_global_option('codetot_settings_hide_social_share') ?? false;
    if (!$hide_social_share) :
      global $post;

      the_block('social-links', array(
        'class' => 'social-links--share',
        'label' => __('Share', 'ct-theme'),
        'items' => codetot_get_share_post_links($post)
      ));
    endif;
  }

  public function generate_comments() {
    ob_start();
      if ( comments_open() || get_comments_number() ) :
        comments_template();
      endif;

   return ob_get_clean();
  }

  public function generate_default_index_layout() {
    $sidebar_layout = get_global_option('codetot_category_layout') ?? 'sidebar-right';

    add_action('codetot_before_index_main', function() {
      if (is_category()) {
        $sidebar_layout = get_global_option('codetot_category_layout') ?? 'sidebar-right';
      } else {
        $sidebar_layout = get_global_option('codetot_post_layout') ?? 'sidebar-right';
      }

      echo $this->page_block_open('page-block--archive ' . esc_attr($sidebar_layout), false);
    }, 10);

    add_action('codetot_before_index_sidebar', function() {
      echo $this->page_block_between();
    }, 1);

    add_action('codetot_index_main_layout', 'codetot_layout_post_list_html', 5);
    add_action('codetot_index_main_layout', 'codetot_layout_post_list_pagination', 10);

    add_action('codetot_after_index_main', function() {
      echo $this->page_block_close();
    }, 10);
  }

  public function page_block_open($available_class = '')
  {
    ob_start();
    printf('<div class="page-block %s">', $available_class);
    echo '<div class="container page-block__container">';
    echo '<div class="grid page-block__grid">';
    echo '<div class="grid__col page-block__col page-block__col--main">';

    return ob_get_clean();
  }

  public function page_block_between()
  {
    ob_start();
    echo '</div>'; // Close .page-block__col--main
    echo '<div class="grid__col page-block__col--sidebar">';
    return ob_get_clean();
  }

  public function page_block_close() {
    ob_start();
    echo '</div>'; // Close .page-block__col--sidebar
    echo '</div>'; // Close .page-block__grid
    echo '</div>'; // Close .page-block__container
    echo '</div>'; // Close .page-block
    return ob_get_clean();
  }
}

function codetot_layout_post_list_html() {
  global $wp_query;

  $post_list_layout = get_global_option('codetot_post_list_layout') ?? 'row';
  $columns = get_global_option('codetot_category_column_number') ?? 3;

  if ($post_list_layout === 'row') {
    the_block('post-list', array(
      'class' => 'section default-section--no-container',
      'query' => $wp_query
    ));
  } else {
    the_block('post-grid', array(
      'class' => 'section default-section--no-container',
      'columns' => $columns,
      'query' => $wp_query
    ));
  }
}

function codetot_layout_post_list_pagination() {
  the_block('pagination');
}

Codetot_Theme_Layout::instance();
