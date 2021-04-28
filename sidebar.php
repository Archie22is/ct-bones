<?php

do_action('codetot_before_sidebar');

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

?>

<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( $sidebar ); ?>
</aside><!-- #secondary -->

<?php do_action('codetot_after_sidebar'); ?>
