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
    }
}

Codetot_Theme_Layout::instance();
