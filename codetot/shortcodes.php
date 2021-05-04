<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

class CodeTot_Shortcode
{
  /**
   * Singleton instance
   *
   * @var CodeTot_Shortcode
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return CodeTot_Shortcode
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
    add_action('init', array($this, 'register_shortcodes'));
  }

  public function register_shortcodes()
  {
    add_shortcode('social-link', function () {
      ob_start();
      the_block('social-links', array(
        'class' => 'social-links--dark-contract social-links--footer-bottom'
      ));
      $social_link = ob_get_contents();
      ob_end_clean();
      return $social_link;
    });

    add_shortcode('contact', function () {
      ob_start();
     the_block('contact-shortcode');
     $contact = ob_get_contents();
     ob_end_clean();
     return $contact;
    });

    add_shortcode('search-form', function () {
      ob_start();
     the_block('search-product-form');
     $search_form = ob_get_contents();
     ob_end_clean();
     return $search_form;
    });

    add_shortcode('cart-icon', function () {
      ob_start();
      the_block_part('header/cart-icon');
      $cart_icon = ob_get_contents();
      ob_end_clean();
      return $cart_icon;
    });
  }
}

CodeTot_Shortcode::instance();
