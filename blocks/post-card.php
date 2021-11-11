<?php
$categories = get_the_category();
$category   = ! empty( $categories ) ? $categories[0] : '';

$_format_date = ! empty( $format_date ) ? $format_date : get_option( 'format_date' );
$post_date    = get_the_date( $_format_date );
$card_style   = codetot_get_theme_mod( 'post_card_style' ) ?? 'style-default';

// Fallback default to style-1
if ( ! empty( $card_style ) && $card_style === 'style-default' ) {
	$card_style = 'style-1';
}

// Visible condition
$_display_category    = ! empty( $card_style ) && ( in_array( $card_style, array( 'style-3' ) ) );
$_display_author      = ! empty( $card_style ) && ( in_array( $card_style, array( 'style-4' ) ) );
$_display_date        = ! empty( $card_style ) && ( in_array( $card_style, array( 'style-1', 'style-3' ) ) );
$_display_date_badge  = ! empty( $card_style ) && $card_style == 'style-2';
$_display_footer      = ! empty( $card_style ) && $card_style == 'style-3';
$_display_description = ! empty( $card_style ) && ( in_array( $card_style, array( 'style-2', 'style-3', 'style-4', 'style-5' ) ) );

$word_count = ! empty( $post_description_length ) ? (int) $post_description_length : (int) apply_filters( 'codetot_post_card_excerpt_number', 20 );

the_block(
	'post-card-' . esc_attr( $card_style ),
	array(
		'class'         => ! empty( $class ) ? $class : '',
		'post_date'     => $post_date,
		'category_name' => ! empty( $category ) ? $category->name : '',
		'category_link' => get_category_link( $category ),
	)
);
