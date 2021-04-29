<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

class Codetot_Woocommerce_Modal_Login {
  private static $instance;
  public final static function instance()
  {
      if (is_null(self::$instance)) {
          self::$instance = new self();
      }
      return self::$instance;
  }

  public function __construct()
  {
    add_action('wp_footer', array($this, 'modal_login_block'));
  }

  public function modal_login_block()
  {
    the_block('modal-login');
  }
}

Codetot_Woocommerce_Modal_Login::instance();
