<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

/**
 * @link       https://codetot.com
 * @since      1.0.0
 * @package    Codetot_Woocommerce
 * @subpackage Codetot_Woocommerce/includes/layout
 * @author     CODE TOT JSC <khoi@codetot.com>
 */
class Codetot_Woocommerce_Layout_Account
{
  /**
   * Singleton instance
   *
   * @var Codetot_Woocommerce_Layout_Archive
   */
  private static $instance;

  /**
   * @var bool
   */
  private $enable_register_form;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Woocommerce_Layout_Archive
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
    $this->lost_password_form_layout();
    $this->reset_password_form_layout();

    $this->enable_register_form = get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes';

    add_filter('codetot_display_page_header', '__return_false');

    if ($this->enable_register_form) {
      add_action('woocommerce_before_customer_login_form', array($this, 'guest_page_open'), 5);
      add_action('woocommerce_before_customer_login_form', array($this, 'login_title'), 10);
      add_action('woocommerce_before_customer_login_form', array($this, 'guest_nav_tabs'), 15);
      add_action('woocommerce_after_customer_login_form', array($this, 'guest_nav_tabs_close'), 10);
      add_action('woocommerce_after_customer_login_form', array($this, 'guest_page_close'), 15);
    } else {
      add_action('woocommerce_before_customer_login_form', array($this, 'guest_page_open'), 10);
      add_action('woocommerce_after_customer_login_form', array($this, 'guest_page_close'), 10);
    }

    if (is_account_page()) {
      add_action('codetot_page', array($this, 'account_content'), 10);
      add_action('woocommerce_before_account_navigation', array($this, 'account_page_open'), 10);
      add_action('woocommerce_after_account_navigation', array($this, 'account_page_between'), 60);
      add_action('woocommerce_after_main_content', array($this, 'account_page_close'), 90);
    }
  }

  public function account_content() {
    the_content();
  }

  public function login_title()
  {
    echo '<h1 class="h2 align-c page-block__title">' . esc_html__('My account', 'woocommerce') . '</h1>';
  }

  public function reset_password_title()
  {
    echo '<h1 class="h2 align-c page-block__title">' . esc_html__('Reset Password') . '</h1>';
  }

  public function lost_password_title()
  {
    echo '<h1 class="h2 align-c page-block__title">' . esc_html__('Lost Password') . '</h1>';
  }

  public function lost_password_form_layout()
  {
    add_action('woocommerce_before_lost_password_form', array($this, 'guest_page_open'), 10);
    add_action('woocommerce_before_lost_password_form', array($this, 'lost_password_title'), 15);
    add_action('woocommerce_after_lost_password_form', array($this, 'link_back_to_login_page'), 5);
    add_action('woocommerce_after_lost_password_form', array($this, 'guest_page_close'), 10);
  }

  public function reset_password_form_layout()
  {
    add_action('woocommerce_before_reset_password_form', array($this, 'guest_page_open'), 10);
    add_action('woocommerce_before_reset_password_form', array($this, 'reset_password_title'), 15);
    add_action('woocommerce_after_reset_password_form', array($this, 'link_back_to_login_page'), 5);
    add_action('woocommerce_after_reset_password_form', array($this, 'guest_page_close'), 10);
  }

  public function guest_page_open() {
    echo '<div class="page-block page-block--guest">';
    echo '<div class="container page-block__container">';
  }

  public function guest_page_close() {
    echo '</div>';
    echo '</div>';
  }

  public function account_page_title() {
    if ( is_wc_endpoint_url( 'orders' ) ) {
      $title = esc_html__( 'Orders', 'woocommerce' );
    } elseif ( is_wc_endpoint_url( 'view-order' ) ) {
      $title = esc_html__( 'Order Detail', 'ct-bones' );
    } elseif ( is_wc_endpoint_url( 'downloads' ) ) {
      $title = esc_html__( 'Downloads', 'woocommerce' );
    } elseif ( is_wc_endpoint_url( 'edit-account' ) ) {
      $title = esc_html__( 'Account details', 'woocommerce' );
    } elseif ( is_wc_endpoint_url( 'edit-address' ) ) {
      $title = esc_html__( 'Addresses', 'woocommerce' );
    } elseif ( is_wc_endpoint_url( 'customer-logout' ) ) {
      $title = esc_html__( 'Logout', 'woocommerce' );
    } elseif ( is_wc_endpoint_url( 'lost-password' ) ) {
      $title = esc_html__( 'Lost password' );
    } else {
      $title = esc_html__('Dashboard', 'woocommerce');
    }

    return apply_filters('codetot_my_account_page_title', $title);
  }

  public function account_page_open() {
    $title = $this->account_page_title();

    echo '<div class="page-block page-block--account" data-block="page-block">';
    echo '<div class="container page-block__container">';
    echo '<div class="page-block__header">';
    echo '<div class="grid page-block__grid page-block__grid page-block__grid--header">';
    echo '<div class="grid__col page-block__col page-block__col--header-left">';
    echo '<h1 class="h2 align-c page-block__title">' . esc_attr($title) . '</h1>';
    echo '</div>'; // Close .page-block__col--header-left
    echo '<div class="grid__col page-block__col page-block__col--header-right">';
    the_block('page-block-mobile-trigger', array(
      'class' => 'has-icon',
      'button_icon' => codetot_svg('menu', false),
      'button_text' => __('Menu', 'ct-bones')
    ));
    echo '</div>'; // Close .page-block__col--header-right
    echo '</div>'; // Close .page-block__grid--header
    echo '</div>'; // Close .page-block__header
    echo '<div class="page-block__main">';
    echo '<div class="grid page-block__grid">';
    echo '<div class="grid__col page-block__col page-block__col--sidebar">';
  }

  public function account_page_between() {
    echo '</div>';
    echo '<div class="grid__col page-block__col page-block__col--main">';
  }

  public function account_page_close() {
    if (is_account_page()) {
      echo '</div>'; // Close .page-block__col--main
      echo '</div>'; // Close .page-block__grid
      echo '</div>'; // Close .page-block__main
      echo '</div>'; // Close .page-block__container
      echo '</div>'; // Close .page-block
    }
  }

  public function guest_nav_tabs() {
    if (is_account_page() && !is_user_logged_in()) {
      $is_register_screen = !empty($_GET['action']) && $_GET['action'] == 'register';
      $tabs = array(
        array(
          'name' => esc_html__('Login', 'woocommerce'),
          'is_active' => empty($_GET)
        ),
        array(
          'name' => esc_html__('Register'),
          'is_register' => true,
          'is_active' => !empty($_GET['action']) && $_GET['action'] == 'register'
        )
      );

      ?>
        <div class="account-tabs<?php if ($is_register_screen) : ?> account-tabs--register-screen<?php endif; ?>" data-ct-block="account-tabs">
          <div class="account-tabs__nav-list">
            <?php foreach($tabs as $tab) : ?>
              <button class="uppercase-text account-tabs__nav-button js-tab-trigger<?php if (isset($tab['is_register'])) : ?> js-tab-trigger-change<?php endif; ?><?php if ($tab['is_active']) : ?> account-tabs__nav-button--active<?php endif; ?>">
                <?php echo $tab['name']; ?>
              </button>
            <?php endforeach; ?>
          </div>
      <?php
    }
  }

  public function guest_nav_tabs_close() {
    echo '</div>';
  }

  /**
   * Add link back to login page
   */
  public function link_back_to_login_page()
  {
    $login_page_url = get_permalink(get_option('woocommerce_myaccount_page_id'));

    echo '<div class="page-block__bottom">';
    echo '<a class="page-block__bottom-link" href="' . esc_url($login_page_url) . '">' . __('Back to Login page', 'ct-bones') . '</a>';
    echo '</div>';
  }

  public function print_errors()
  {
    if (is_account_page()) {
      the_block('message-block', array(
        'content' => wc_print_notices(true)
      ));
    }
  }
}

Codetot_Woocommerce_Layout_Account::instance();
