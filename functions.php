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

if (!function_exists('get_block')) {
  /**
   * @param string $block_name
   * @param array $args
   * @return false|string
   */
  function get_block($block_name, $args = array())
  {
    ob_start();
    the_block($block_name, $args);
    return ob_get_clean();
  }
}

if ( !function_exists('the_block') ) {
  function the_block($block_name, $args = array())
  {
    extract($args, EXTR_SKIP);

    if (is_child_theme()) {
      $paths[] = get_stylesheet_directory() . '/blocks/' . esc_attr($block_name) . '.php';
    }

    $paths[] = get_template_directory() . '/blocks/' . esc_attr($block_name) . '.php';

    $loaded = false;

    foreach($paths as $path) {
      if (file_exists($path) && empty($loaded)) {
        include($path);

        $loaded = true;
      }
    }

    if (empty($loaded)) {
      $error = new WP_Error(
        'missing_path',
        sprintf(__('Missing block %s', 'ct-bones'), $block_name)
      );

      echo '<pre>';
      echo $error->get_error_message();
      echo '</pre>';
    }
  }
}

if ( !function_exists('the_block_part') ) {
  function the_block_part($block_name) {
    $error = new WP_Error(
      'plugin_not_activate',
      sprintf(__('Plugin %s must be activate to work with this theme.', 'ct-bones'), 'CT Blocks')
    );

    if (is_child_theme()) {
      $paths[] = get_stylesheet_directory() . '/block-parts/' . esc_attr($block_name) . '.php';
    }

    $paths[] = get_template_directory() . '/block-parts/' . esc_attr($block_name) . '.php';

    foreach($paths as $path) {
      if (file_exists($path) && empty($loaded)) {
        include($path);

        $loaded = true;
      }
    }

    if (empty($loaded)) {
      $error = new WP_Error(
        'missing_path',
        sprintf(__('Missing block %s', 'ct-bones'), $block_name)
      );

      echo '<pre>';
      echo $error->get_error_message();
      echo '</pre>';
    }
  }
}

if (!function_exists('codetot_svg')) {
  function codetot_svg($name, $echo = true)
  {

    if (empty($name)) {
      return new WP_Error(
        '404',
        __('Missing svg file name', 'ct-blocks')
      );
    }

    $paths = apply_filters('codetot_svg_paths', []);
    $svg_content = '';

    if (is_child_theme()) {
      $paths[] = get_stylesheet_directory() . '/assets/svg';
    }
    $paths[] = get_template_directory() . '/assets/svg';

    foreach($paths as $path) {
      $file_path = $path . '/' . $name . '.svg';

      if (file_exists($file_path) && empty($svg_content)) {
        $svg_content = file_get_contents($file_path);
      }
    }

    if (empty($svg_content)) {
      $svg_content = '<!-- No svg file for ' . $name . '.svg -->';
    }

    if ($echo) {
      echo $svg_content;

      return true;
    } else {
      return $svg_content;
    }
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
include_once CODETOT_DIR . '/helpers/template-tags.php';
include_once CODETOT_DIR . '/helpers/deprecated.php';

require_once CODETOT_DIR . '/theme-init.php';
require_once CODETOT_DIR . '/assets.php';
require_once CODETOT_DIR . '/api.php';
require_once CODETOT_DIR . '/seo-support.php';

// Admin
require_once CODETOT_ADMIN_DIR . '/init.php';
require_once CODETOT_ADMIN_DIR . '/acf.php';
require_once CODETOT_ADMIN_DIR . '/ct-settings.php';
require_once CODETOT_ADMIN_DIR . '/ct-theme.php';
require_once CODETOT_ADMIN_DIR . '/ct-data.php';
// require_once CODETOT_ADMIN_DIR . '/theme-sync.php';
require_once CODETOT_ADMIN_DIR . '/page-settings.php';

require_once CODETOT_DIR . '/shortcodes.php';

/** Custom widgets */
require_once CODETOT_DIR . '/widgets/ct-icon-box.php';
require_once CODETOT_DIR . '/widgets/ct-company-info.php';
require_once CODETOT_DIR . '/widgets/ct-recent-posts.php';
require_once CODETOT_DIR . '/widgets/ct-related-posts.php';

require_once CODETOT_DIR . '/features/back-to-top.php';
require_once CODETOT_DIR . '/features/store-locator.php';
require_once CODETOT_DIR . '/features/google-maps.php';
require_once CODETOT_DIR . '/features/mega-menu.php';

add_action('wp', function() {
  // Layout hooks
  require_once CODETOT_DIR . '/layout.php';
  require_once CODETOT_DIR . '/features/multiple-headers.php';
  require_once CODETOT_DIR . '/features/breadcrumbs.php';
  require_once CODETOT_DIR . '/features/related-posts.php';
  require_once CODETOT_DIR . '/features/facebook-comments.php';
}, 10);

if (class_exists('WooCommerce')) {
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
  require_once CODETOT_DIR . '/woocommerce/features/product-video.php';

  require_once CODETOT_DIR . '/woocommerce/layouts/archive.php';

  add_action('wp', function() {
    require_once CODETOT_DIR . '/woocommerce/layouts/product.php';
    require_once CODETOT_DIR . '/woocommerce/layouts/account.php';
    require_once CODETOT_DIR . '/woocommerce/layouts/cart.php';
    require_once CODETOT_DIR . '/woocommerce/layouts/checkout.php';
  });
}

require_once CODETOT_DIR . '/optimize.php';
