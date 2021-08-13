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
   * @var string
   */
  private $theme_environment;

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
    $this->theme_environment = $this->is_localhost() ? '' : '.min';

    add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_styles'), 10);
    add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
    add_action('wp_head', array($this, 'dns_prefetch'), 0);

    add_filter('style_loader_tag', array($this, 'remove_type_attr'));
    add_filter('script_loader_tag', array($this, 'remove_type_attr'), 10, 2);
    add_filter('gform_get_form_filter', array($this, 'filter_tag_html5_gravity_forms'));
  }

  public function enqueue_frontend_styles()
  {
    $first_screen_url = get_template_directory_uri() . '/assets/css/first-screen-style' . $this->theme_environment . '.css';
    // Load file
    wp_enqueue_style('codetot-first-screen', $first_screen_url, array(), CODETOT_VERSION);
    wp_enqueue_style('codetot-global', get_template_directory_uri() . '/assets/css/global-style' . $this->theme_environment . '.css', array('codetot-first-screen'), CODETOT_VERSION);
  }

  public function enqueue_frontend_scripts()
  {
    if (!wp_script_is('lazysizes', 'enqueued')) {
      wp_register_script('lazysizes', get_template_directory_uri() . '/assets/js/vendors/lazysizes.min.js', array(), '5.2.2', false);
      wp_enqueue_script('lazysizes');
    }
  }

  public function dns_prefetch()
  {
    ?>
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <link rel="dns-prefetch" href="//fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <?php
  }

  /**
   * @param $id
   * @param $content
   * @return bool
   */
  public function register_inline_style($id, $content)
  {
    wp_register_style($id, false);
    wp_enqueue_style($id);
    return wp_add_inline_style($id, $content);
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

  public function filter_tag_html5_gravity_forms($form_string) {
    return preg_replace( "/[ ]type=[\'\"]text\/(javascript|css)[\'\"]/", '', $form_string );
  }

  /**
   * @return bool
   */
  public function is_localhost()
  {
    return !empty($_SERVER['HTTP_X_CODETOT_HEADER']) && $_SERVER['HTTP_X_CODETOT_HEADER'] === 'development';
  }
}

CodeTot_Optimize::instance();
