<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

class Codetot_Page_Settings {
    /**
     * Singleton instance
     *
     * @var Codetot_Page_Settings
     */
    private static $instance;

    /**
     * Get singleton instance.
     *
     * @return Codetot_Page_Settings
     */
    public final static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
      $this->prefix = 'codetot_';
      add_filter( 'rwmb_meta_boxes', array($this, 'page_settings'));
    }

    public function page_settings( $meta_boxes ) {
      $meta_boxes[] = [
          'title'      => __( 'Page Settings', 'ct-theme' ),
          'id'         => 'page-settings',
          'post_types' => ['page'],
          'style'      => 'seamless',
          'fields'     => [
              [
                  'name' => __( 'Enable Header Transparent', 'ct-theme' ),
                  'id'   =>  $this->prefix . 'enable_header_transparent',
                  'type' => 'switch',
              ],
              [
                  'name' => __( 'Page Class', 'ct-theme' ),
                  'id'   => $this->prefix  . 'page_class',
                  'type' => 'text',
              ],
              [
                'name' => __( 'Page Stylesheet (CSS)', 'ct-theme' ),
                'id'   => $this->prefix  . 'page_stylesheet_css',
                'type' => 'textarea'
            ],
          ],
      ];

      return $meta_boxes;
  }
}

Codetot_Page_Settings::instance();
