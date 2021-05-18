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
  }
}

Codetot_Api::instance();
