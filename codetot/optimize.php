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
    add_action('wp_enqueue_scripts', array($this, 'register_first_screen_style'), 1);
    add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));

    add_action('wp_print_scripts', array($this, 'remove_scripts'));
    add_action('wp_print_styles', array($this, 'remove_styles'));
    add_action('wp_head', array($this, 'dns_prefetch'), 0);

    add_action('wp_head', array($this, 'lazy_scripts'), 1);
    add_action('wp_head', array($this, 'lazy_styles'), 1);

    add_action('wp_head',  array($this, 'load_first_screen_hide_selectors'));
  }

  public function remove_scripts()
  {
    wp_dequeue_script('toc-front');
  }

  public function remove_styles()
  {
    wp_dequeue_style('dashicons');
    /** Table of Content Plus plugin  **/
    wp_dequeue_style('toc-screen');
  }

  public function enqueue_frontend_styles()
  {
    if (is_singular('post')) {
      wp_enqueue_style('toc-screen');
    }
  }

  public function register_first_screen_style()
  {
    $first_screen_css_path = get_template_directory() . '/assets/css/first-screen-style' . $this->theme_environment . '.css';
    $first_screen_css_inline = file_exists($first_screen_css_path) ? file_get_contents($first_screen_css_path) : '';

    if (!$this->is_localhost() && !empty($first_screen_css_inline)) {
      // Inline - production
      $this->register_inline_style('codetot-first-screen', $first_screen_css_inline);
    } else {
      $first_screen_url = get_template_directory_uri() . '/assets/css/first-screen-style' . $this->theme_environment . '.css';

      // Load file
      wp_enqueue_style('codetot-first-screen', $first_screen_url, array(), CODETOT_VERSION);
    }
  }

  public function enqueue_frontend_scripts()
  {
    if (!wp_script_is('lazysizes', 'enqueued')) {
      wp_register_script('lazysizes', get_template_directory_uri() . '/assets/js/vendors/lazysizes.min.js', array(), '5.2.2');
      wp_enqueue_script('lazysizes');
    }

    wp_enqueue_script('codetot-lazy', get_template_directory_uri() . '/assets/js/codetot-lazy'. $this->theme_environment .'.js', array(), CODETOT_VERSION, true);
  }

  public function dns_prefetch()
  {
    ?>
    <meta http-equiv="x-dns-prefetch-control" content="on">
    <link rel="dns-prefetch" href="//fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <?php
  }

  public function load_first_screen_hide_selectors() {
    $selectors = apply_filters('codetot_first_screen_hide_style', array(
      '.slideout-menu',
      '.modal-search-form',
      '.modal'
    ));

    echo '<style id="codetot-first-screen-hide-css">';
    echo implode(', ', $selectors) . '{';
    echo 'opacity: 0; visibility: hidden;';
    echo '}';
    echo '</style>';
  }

  public function lazy_styles()
  {
    $styles = apply_filters('codetot_lazy_styles', array(
      'codetot-global-style' => get_template_directory_uri() . '/assets/css/global-style' . $this->theme_environment . '.css?ver=' . CODETOT_VERSION,
    ));
    if (!empty($styles)) {
    ?>
    <script id="codetot-lazy-styles">
      var LAZY_STYLES = '<?php echo json_encode($styles); ?>';
    </script>
    <?php
    } else {
      echo '<!--- CODETOT: No lazy styles -->'; ?>
      <script id="codetot-lazy-styles">
        var LAZY_STYLES = '';
      </script>
      <?php
    }
  }

  public function lazy_scripts()
  {
    $scripts = apply_filters('codetot_lazy_scripts', array());
    if (!empty($scripts)) {
      ?>
      <script id="codetot-lazy-scripts">
        var LAZY_SCRIPTS = '<?php echo json_encode($scripts); ?>';
      </script>
      <?php
    } else {
      echo '<!--- CODETOT: No lazy scripts -->'; ?>
      <script id="codetot-lazy-scripts">
        var LAZY_SCRIPTS = '';
      </script>
      <?php
    }
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
   * @return bool
   */
  public function is_localhost()
  {
    return !empty($_SERVER['HTTP_X_CODETOT_HEADER']) && $_SERVER['HTTP_X_CODETOT_HEADER'] === 'development';
  }
}

CodeTot_Optimize::instance();
