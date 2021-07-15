<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_Insert_Script
{
  /**
   * Singleton instance
   *
   * @var Codetot_Insert_Script
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Insert_Script
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
  public function __construct()
  {
    add_action( 'wp_head', array( &$this, 'ct_scripts_in_header' ), 100 );
		add_action( 'wp_footer', array( &$this, 'ct_scripts_in_footer'), 100 );
    add_action( 'wp_body_open', array( &$this, 'ct_scripts_in_body' ), 1);
  }

	public function ct_scripts_in_header() {
    $script_header = get_field('ct_scripts_in_header', 'options');
    if(!empty($script_header)) {
      echo $script_header;
    }
	}

  public function ct_scripts_in_footer() {
    $script_footer = get_field('ct_scripts_in_footer', 'options');
    if(!empty($script_footer)) {
      echo $script_footer;
    }
  }

  public function ct_scripts_in_body() {
    $script_body = get_field('ct_scripts_in_body', 'options');
    if(!empty($script_body)) {
      echo $script_body;
    }
  }
}

Codetot_Insert_Script::instance();
