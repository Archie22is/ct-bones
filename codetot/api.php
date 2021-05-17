<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Codetot_Api {
  /**
   * Singleton instance
   *
   * @var Codetot_Api
   */
  private static $instance;

  /**
   * @var string
   */
  private $route_name;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Api
   */
  public final static function instance() {
    if ( is_null( self::$instance ) ) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Class constructor
   */
  private function __construct()
  {
    $this->route_name = 'codetot/v1';

    add_action('rest_api_init', array($this, 'register_custom_routes'));
  }

  public function register_custom_routes() {
    register_rest_route($this->route_name, 'get_menu_html', array(
      'methods' => 'GET',
      'callback' => array($this, 'get_menu_html_callback'),
      'permission_callback' => '__return_true'
    ));
  }

  /**
   * @param WP_REST_Request $request
   * @return WP_REST_Response
   */
  public function get_menu_html_callback($request) {
    $html = '';

    return new WP_REST_Response(
      array(
        'html' => $html
      ),
      200
    );
  }
}

Codetot_Api::instance();
