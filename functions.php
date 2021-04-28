<?php

/**
 * CT Bones functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package CT_Bones
 */

if (!defined('CODETOT_VERSION')) {
	// Replace the version number of the theme on each release.
	define('CODETOT_VERSION', wp_get_theme()->Get('Version'));
}

define('CODETOT_DIR', get_template_directory() . '/codetot');
define('CODETOT_ADMIN_DIR', get_template_directory() . '/codetot/admin');
define('CODETOT_ADMIN_PATH', get_template_directory_uri() . '/codetot/admin');
define('CODETOT_ADMIN_ASSETS_URI', get_template_directory_uri() . '/codetot/admin/assets');
define('CODETOT_ASSETS_URI ', get_template_directory_uri(). '/assets');

include_once CODETOT_DIR . '/helpers/acf.php';
include_once CODETOT_DIR . '/helpers/metabox.php';
include_once CODETOT_DIR . '/helpers/generator.php';

require_once CODETOT_DIR . '/theme-init.php';
require_once CODETOT_DIR . '/assets.php';

// Admin
require_once CODETOT_ADMIN_DIR . '/init.php';

require_once CODETOT_ADMIN_DIR . '/ct-theme.php';
require_once CODETOT_ADMIN_DIR . '/ct-data.php';
require_once CODETOT_ADMIN_DIR . '/theme-sync.php';
require_once CODETOT_ADMIN_DIR . '/page-settings.php';

// Features
require_once CODETOT_DIR . '/features/multiple-headers.php';

require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';

if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}

if (class_exists('WooCommerce')) {
	require get_template_directory() . '/inc/woocommerce.php';

  require_once CODETOT_DIR . '/woocommerce/init.php';
	require_once CODETOT_DIR . '/woocommerce/ct-theme.php';
}

require_once CODETOT_DIR . '/optimize.php';
