<?php

// Prevent direct access.
if (!defined('ABSPATH')) exit;

class CodeTot_Optimize
{
  /**
   * Singleton instance
   *
   * @var CodeTot_Optimize
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return CodeTot_Optimize
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
    add_action('wp_head', array($this, 'dns_prefetch'), 0);

    add_filter('style_loader_tag', array($this, 'remove_type_attr'));
    add_filter('script_loader_tag', array($this, 'remove_type_attr'), 10, 2);
    add_filter('gform_get_form_filter', array($this, 'filter_tag_html5_gravity_forms'));
  }

	/**
	 * Prefetch DNS
	 *
	 * @return void
	 */
  public function dns_prefetch()
  {
    ?>
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <link rel="dns-prefetch" href="//fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <?php
  }

  /**
   * Validate HTML5 by remove type="script" in tag scripts
   *
   * @param string $tag
   * @param string $handle
   * @return void
   */
  public function remove_type_attr($tag) {
    return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
  }

	/**
	 * Validate HTML5 by remove type="script" in tag scripts
	 *
	 * @param string $form_string
	 * @return void|string
	 */
  public function filter_tag_html5_gravity_forms($form_string) {
    return preg_replace( "/[ ]type=[\'\"]text\/(javascript|css)[\'\"]/", '', $form_string );
  }
}

CodeTot_Optimize::instance();
