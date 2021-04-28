<?php

$sidebar = '';

if (is_page()) {
  $sidebar = 'page-sidebar';
}

if (is_single()) {
  $sidebar = 'post-sidebar';
}

if (function_exists('is_shop') && is_shop()) {
  $sidebar = 'shop-sidebar';
}

if (function_exists('is_product_category') && is_product_category()) {
  $sidebar = 'product-category-sidebar';
}

/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CT_Bones
 */

if ( ! is_active_sidebar( $sidebar ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( $sidebar ); ?>
</aside><!-- #secondary -->
