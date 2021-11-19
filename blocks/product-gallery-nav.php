<?php

/**
 * @block product-gallery-nav
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

if ( empty( $product ) ) {
	global $product;
}

$thumbnail_type          = codetot_get_theme_mod( 'single_product_gallery_thumbnail_style', 'woocommerce' ) ?? 'default';
$enable_view_more_button = $thumbnail_type === 'default';
$columns                 = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$attachment_ids          = $product->get_gallery_image_ids();

if ( ! empty( $attachment_ids ) && ! empty( $product->get_image_id() ) ) {
	do_action( 'codetot_before_single_product_image_thumbnails' );

	foreach ( $attachment_ids as $index => $attachment_id ) {
		if (
		( $index <= ( intval( $columns ) - 2 ) && $enable_view_more_button ) ||
		! $enable_view_more_button
		) :
			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $attachment_id ), $attachment_id );
	endif;
	}

	do_action( 'codetot_after_single_product_image_thumbnails' );
}
