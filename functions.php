<?php

/**
 * CT Bones functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package CT_Bones
 */
define('CODETOT_DIR', get_template_directory() . '/codetot');
define('CODETOT_ADMIN_DIR', get_template_directory() . '/codetot/admin');
define('CODETOT_ADMIN_PATH', get_template_directory_uri() . '/codetot/admin');
define('CODETOT_ADMIN_ASSETS_URI', get_template_directory_uri() . '/codetot/admin/assets');
define('CODETOT_ASSETS_URI ', get_template_directory_uri(). '/assets');

include_once CODETOT_DIR . '/fallback.php';
include_once CODETOT_DIR . '/helpers/acf.php';
include_once CODETOT_DIR . '/helpers/metabox.php';
include_once CODETOT_DIR . '/helpers/generator.php';
include_once CODETOT_DIR . '/helpers/template-tags.php';

/**
 * Customizer Support
 */
include_once CODETOT_DIR . '/customizer/helpers.php';
require_once CODETOT_DIR . '/customizer/settings.php';
if (class_exists('WooCommerce')) {
  require_once CODETOT_DIR . '/customizer/woocommerce-settings.php';
}

require_once CODETOT_DIR . '/theme-init.php';
require_once CODETOT_DIR . '/assets.php';
require_once CODETOT_DIR . '/api.php';
require_once CODETOT_DIR . '/seo-support.php';

require_once CODETOT_DIR . '/features/related-posts.php';

// Admin
require_once CODETOT_ADMIN_DIR . '/init.php';
require_once CODETOT_ADMIN_DIR . '/acf.php';
require_once CODETOT_ADMIN_DIR . '/ct-settings.php';
if (defined('CT_THEME')) :
  require_once CODETOT_ADMIN_DIR . '/ct-theme.php';
endif;
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

/**
 * To enable fallback in child theme, please set in functions.php
 * define('CT_THEME', true);
 */

if (defined('CT_THEME')) :
  require_once CODETOT_DIR . '/woocommerce/ct-theme.php';
endif;

add_action('wp', function() {
  // Layout hooks
  require_once CODETOT_DIR . '/layout.php';
  require_once CODETOT_DIR . '/features/multiple-headers.php';
  require_once CODETOT_DIR . '/features/breadcrumbs.php';
  require_once CODETOT_DIR . '/features/facebook-comments.php';
}, 10);

if (class_exists('WooCommerce')) {
  include_once CODETOT_DIR . '/helpers/woocommerce.php';

  include_once CODETOT_DIR . '/woocommerce/template-tags.php';
  require_once CODETOT_DIR . '/woocommerce/init.php';
  require_once CODETOT_DIR . '/woocommerce/ct-settings.php';

  // Custom Layout
  require_once CODETOT_DIR . '/woocommerce/layouts/abstract.php';

  require_once CODETOT_DIR . '/woocommerce/features/mini-cart.php';
  require_once CODETOT_DIR . '/woocommerce/features/modal-login.php';
  require_once CODETOT_DIR . '/woocommerce/features/quick-view.php';
  require_once CODETOT_DIR . '/woocommerce/features/countdown-price.php';
  require_once CODETOT_DIR . '/woocommerce/features/global-guarantee-list.php';
  require_once CODETOT_DIR . '/woocommerce/features/product-video.php';
  require_once CODETOT_DIR . '/woocommerce/features/viewed-products.php';
  require_once CODETOT_DIR . '/woocommerce/features/product-card-style.php';

  add_action('wp', function() {
    require_once CODETOT_DIR . '/woocommerce/layouts/product.php';
    require_once CODETOT_DIR . '/woocommerce/layouts/account.php';
    require_once CODETOT_DIR . '/woocommerce/layouts/cart.php';
    require_once CODETOT_DIR . '/woocommerce/layouts/checkout.php';
    require_once CODETOT_DIR . '/woocommerce/layouts/archive.php';
  });
}

require_once CODETOT_DIR . '/optimize.php';

require_once __DIR__ . '/sync-settings.php';
