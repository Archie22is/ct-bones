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
      if (is_page()) :
        if (apply_filters('codetot_display_page_breadcrumbs', true) === true) :
          add_action('codetot_page', 'codetot_page_breadcrumbs', 10);
        endif;

        if (apply_filters('codetot_display_page_header', true) === true) :
          add_action('codetot_page', 'codetot_page_header', 20);
        endif;
        add_action('codetot_page', 'codetot_page_content', 30);
        add_action('codetot_page', 'codetot_display_comments', 40);
      endif;

      if (is_category()) {
        add_action('codetot_after_header', array($this, 'category_block_open'), 10);
        add_action('codetot_before_sidebar', array($this, 'category_block_between'), 10);
      }

      if (is_singular() && !is_product()) {
        add_action('codetot_after_header', array($this, 'single_block_open'), 10);
        add_action('codetot_before_sidebar', array($this, 'single_block_between'), 10);
        add_action('codetot_after_sidebar', array($this, 'single_block_close'), 10);
      }

      if (is_search()) {
        add_action('codetot_after_header', array($this, 'search_block_open'), 10);
        add_action('codetot_before_sidebar', array($this, 'search_block_close'), 10);
      }
    }

    public function category_block_open() {
      the_block('breadcrumbs');
      echo '<div class="category-post">';
      echo '<div class="container category-post__container">';
      echo '<div class="grid category-post__grid">';
      echo '<div class="category-post__col category-post__col--main">';
    }

    public function category_block_between() {
      echo '</div>'; // Close .category-post__col--main
      echo '</div>'; // Close .category-post__grid
      echo '</div>'; // Close .category-post__container
      echo '</div>'; // Close .category-post
    }

    public function single_block_open() {
      the_block('breadcrumbs');
      echo  '<div class="container sidebar-section__container">';
      echo  '<div class="grid sidebar-section__block-grid">';
      echo '<div class="grid__col sidebar-section__block sidebar-section__block--content">';
      echo '<div class="sidebar-section__inner">';
    }

    public function single_block_between() {
      echo '</div>';
      echo '</div>';
      echo  '<div class="grid__col sidebar-section__block sidebar-section__block--sidebar">';
      echo  '<div class="sidebar-section__inner">';
    }

    public function single_block_close() {
      echo '</div>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }

    public function search_block_open() {
      the_block('breadcrumbs');
      echo  '<div class="container sidebar-section__container">';
      echo  '<div class="grid sidebar-section__block-grid">';
      echo '<div class="grid__col sidebar-section__block sidebar-section__block--content">';
      echo '<div class="sidebar-section__inner">';
    }

    public function search_block_close() {
      echo '</div>';
      echo '</div>';
    }
}

Codetot_Theme_Layout::instance();
