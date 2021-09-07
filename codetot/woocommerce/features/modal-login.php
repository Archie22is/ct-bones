<?php
if (! defined('WPINC')) {
    die;
}

class Codetot_Woocommerce_Modal_Login
{
    private static $instance;
    final public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $enable_popup = codetot_get_theme_mod('enable_login_popup', 'woocommerce') ?? false;

        if ($enable_popup) {
            add_action('wp_footer', array($this, 'modal_login_block'));
            add_filter('codetot_header_account_icon', array($this, 'header_account_icon'));
        }
    }

    public function header_account_icon($html)
    {
        if (is_user_logged_in()) :
      return $html; else :
      ob_start(); ?>
      <button class="header__menu-icons__item header__menu-icons__link header__menu-icons__item--account" data-open-modal="modal-login">
        <span class="header__menu-icons__icon">
          <?php codetot_svg('user', true); ?>
        </span>
        <span class="screen-reader-text"><?php _e('Click to open a login popup', 'ct-bones'); ?></span>
      </button>
      <?php return ob_get_clean();
        endif;
    }

    public function modal_login_block()
    {
        the_block('modal-login');
    }
}

Codetot_Woocommerce_Modal_Login::instance();
