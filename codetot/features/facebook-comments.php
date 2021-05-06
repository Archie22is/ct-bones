<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_Facebook_Comments
{
  /**
   * Singleton instance
   *
   * @var Codetot_Facebook_Comments
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Facebook_Comments
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
    $enable_post_comments = get_global_option('codetot_enable_post_facebook_comments') ?? false;
    $enable_product_comments = (class_exists('WooCommerce') && get_global_option('codetot_woocommerce_enable_facebook_comment')) ?? false;

    if ($enable_post_comments) {
      add_action('codetot_after_post', array($this, 'load_facebook_comments'), 20);
    }

    if ($enable_product_comments) {
      // Add new tab
      add_filter( 'woocommerce_product_tabs', array($this, 'product_tabs_facebook_reviews'), 98 );
    }
  }

  public function product_tabs_facebook_reviews($tabs) {
    if (
      get_option( 'woocommerce_enable_reviews') === 'yes' &&
      !empty($tabs['reviews'])
    ) {
      unset($tabs['reviews']);
    }

    $tabs['reviews'] = array(
      'title' => apply_filters('woocommerce_reviews_title', esc_html__('Reviews', 'woocommerce')),
      'priority' => 90,
      'callback' => array($this, 'load_facebook_comments')
    );

    return $tabs;
  }

  /**
   * Load Javascript SDK
   *
   * Taken from https://developers.facebook.com/docs/plugins/comments
   * @return void
   */
  public function load_script() {
    ?>
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v10.0" nonce="7Q8qKRn1"></script>
    <?php
  }

  public function load_embed($url) {
    ?>
    <div class="fb-comments" data-href="<?php echo esc_url($url); ?>" data-width="" data-numposts="5"></div>
    <?php
  }

  public function load_facebook_comments() {
    if (!is_singular()) {
      return;
    }

    if ($this->is_localhost()) {
      echo '<--- WARNING: Facebook comments can\'t load in localhost environment. You must test on live or staging environment. -->';
    } else {
      global $post;

      $url = get_permalink($post);

      $this->load_script();
      $this->load_embed($url);
    }
  }

  public function is_localhost()
  {
    return !empty($_SERVER['HTTP_X_CODETOT_HEADER']) && $_SERVER['HTTP_X_CODETOT_HEADER'] === 'development';
  }
}

Codetot_Facebook_Comments::instance();
