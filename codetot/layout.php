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

    if (is_page() && get_page_template_slug( get_the_ID()) === '') {
      $this->generate_page_layout();
    }

    if (is_singular('post')) {
      $this->generate_post_layout();
    }
  }

  public function generate_page_layout() {
    $sidebar_layout = get_global_option('codetot_page_layout');

    add_action('codetot_after_header', function() use($sidebar_layout) {

      if ( !is_front_page() ) {
        the_block('breadcrumbs');
      }

      if ($sidebar_layout !== 'no-sidebar') {
        echo $this->page_block_open('page-block--page ' . $sidebar_layout, false);
      }
    }, 10);

    add_action('codetot_page', function() use($sidebar_layout) {
      $header_class = $sidebar_layout !== 'no-sidebar' ? 'page-header--no-container page-header--top-section' : '';

      the_block('page-header', array(
        'class' => $header_class,
        'title' => get_the_title()
      ));
    }, 20);
    add_action('codetot_page', function() use($sidebar_layout) {
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

      if ($sidebar_layout !== 'no-sidebar') {
        echo '<div class="wysiwyg page-block__content">';
        the_content();
        echo '</div>';
      } else {
        the_block('default-section', array(
          'class' => 'section page-content',
          'content' => $content
        ));
      }
    }, 30);
    add_action('codetot_page', function() use($sidebar_layout) {
      $content = $this->generate_comments();

      if ($sidebar_layout !== 'no-sidebar') {
        echo '<div class="page-comments">';
        the_content();
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

    add_action('codetot_after_sidebar', function() {
      echo $this->page_block_close();
    }, 10);
  }

  public function generate_post_layout() {
    $sidebar_layout = get_global_option('codetot_post_layout');

    add_action('codetot_after_header', function() use ($sidebar_layout) {
      the_block('breadcrumbs');

      if ($sidebar_layout !== 'no-sidebar') {
        echo $this->page_block_open('page-block--page ' . $sidebar_layout, false);
      }
    }, 10);

    add_action('codetot_before_sidebar', function() {
      echo $this->page_block_between();
    }, 10);

    add_action('codetot_after_sidebar', function() {
      echo $this->page_block_close();
    }, 10);
  }

  public function generate_comments() {
    ob_start();
      if ( comments_open() || get_comments_number() ) :
        comments_template();
      endif;

   return ob_get_clean();
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

Codetot_Theme_Layout::instance();
