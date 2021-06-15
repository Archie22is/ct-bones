<?php

/**
 * CT Bones functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package CT_Bones
 */

if (!defined('CODETOT_VERSION')) {
  $theme = wp_get_theme();
  $is_child = !empty($theme->parent());

  if ($is_child) {
    $theme_version = $theme->parent()->Get('Version');
  } else {
    $theme_version = $theme->Get('Version');
  }

	// Replace the version number of the theme on each release.
	define('CODETOT_VERSION', $theme_version);
}

if ( !function_exists('the_block') ) {
  function the_block() {
    $error = new WP_Error(
      'plugin_not_activate',
      sprintf(__('Plugin %s must be activate to work with this theme.', 'ct-bones'), 'CT Bones')
    );

    echo $error->get_error_message();
  }
}

if ( !function_exists('the_block_part') ) {
  function the_block_part() {
    $error = new WP_Error(
      'plugin_not_activate',
      sprintf(__('Plugin %s must be activate to work with this theme.', 'ct-bones'), 'CT Bones')
    );

    echo $error->get_error_message();
  }
}

if ( !function_exists('get_field') ) {
  function codetot_admin_notice() {
    $class = 'notice notice-error';
    $message = sprintf(__('Plugin %s must be activate to work with this theme.', 'ct-bones'), 'Advanced Custom Fields PRO');

    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
  }
  add_action( 'admin_notices', 'codetot_admin_notice' );
}

define('CODETOT_DIR', get_template_directory() . '/codetot');
define('CODETOT_ADMIN_DIR', get_template_directory() . '/codetot/admin');
define('CODETOT_ADMIN_PATH', get_template_directory_uri() . '/codetot/admin');
define('CODETOT_ADMIN_ASSETS_URI', get_template_directory_uri() . '/codetot/admin/assets');
define('CODETOT_ASSETS_URI ', get_template_directory_uri(). '/assets');

include_once CODETOT_DIR . '/helpers/acf.php';
include_once CODETOT_DIR . '/helpers/metabox.php';
include_once CODETOT_DIR . '/helpers/generator.php';
include_once CODETOT_DIR . '/helpers/deprecated.php';

include_once get_template_directory() . '/inc/template-tags.php';
include_once get_template_directory() . '/inc/template-functions.php';

require_once CODETOT_DIR . '/theme-init.php';
require_once CODETOT_DIR . '/assets.php';
require_once CODETOT_DIR . '/api.php';

// Admin
require_once CODETOT_ADMIN_DIR . '/init.php';
require_once CODETOT_ADMIN_DIR . '/acf.php';
require_once CODETOT_ADMIN_DIR . '/ct-settings.php';
require_once CODETOT_ADMIN_DIR . '/ct-theme.php';
require_once CODETOT_ADMIN_DIR . '/ct-data.php';
require_once CODETOT_ADMIN_DIR . '/theme-sync.php';
require_once CODETOT_ADMIN_DIR . '/page-settings.php';

require_once CODETOT_DIR . '/shortcodes.php';

/** Custom widgets */
require_once CODETOT_DIR . '/widgets/ct-icon-box.php';
require_once CODETOT_DIR . '/widgets/ct-company-info.php';
require_once CODETOT_DIR . '/widgets/ct-recent-posts.php';

require_once CODETOT_DIR . '/features/store.php';
require_once CODETOT_DIR . '/features/google-maps.php';

add_action('wp', function() {
  // Layout hooks
  require_once CODETOT_DIR . '/layout.php';
  require_once CODETOT_DIR . '/features/multiple-headers.php';
  require_once CODETOT_DIR . '/features/breadcrumbs.php';
  require_once CODETOT_DIR . '/features/related-posts.php';
  require_once CODETOT_DIR . '/features/facebook-comments.php';
}, 10);

if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}

if (class_exists('WooCommerce')) {
	require get_template_directory() . '/inc/woocommerce.php';

  include_once CODETOT_DIR . '/helpers/woocommerce.php';

  require_once CODETOT_DIR . '/woocommerce/init.php';
  require_once CODETOT_DIR . '/woocommerce/ct-settings.php';
  require_once CODETOT_DIR . '/woocommerce/ct-theme.php';

  // Custom Layout
  require_once CODETOT_DIR . '/woocommerce/layouts/abstract.php';

  require_once CODETOT_DIR . '/woocommerce/features/mini-cart.php';
  require_once CODETOT_DIR . '/woocommerce/features/modal-login.php';
  require_once CODETOT_DIR . '/woocommerce/features/quick-view.php';
  require_once CODETOT_DIR . '/woocommerce/features/countdown-price.php';
  require_once CODETOT_DIR . '/woocommerce/features/global-guarantee-list.php';

  require_once CODETOT_DIR . '/woocommerce/layouts/archive.php';

  add_action('wp', function() {
    require_once CODETOT_DIR . '/woocommerce/layouts/product.php';
    require_once CODETOT_DIR . '/woocommerce/layouts/account.php';
    require_once CODETOT_DIR . '/woocommerce/layouts/cart.php';
    require_once CODETOT_DIR . '/woocommerce/layouts/checkout.php';
  });
}

require_once CODETOT_DIR . '/optimize.php';
