<?php
$button_sizes = array( 'small', 'large' );
$button_types = array(
	'white',
	'dark',
	'primary',
	'secondary',
	'outline',
	'outline-white',
	'outline-dark',
	'outline-primary',
	'outline-secondary',
	'link',
	'link-white',
	'link-dark',
	'link-primary',
	'link-secondary',
);

$fill_button_types = [
	'white',
	'dark',
	'primary',
	'secondary'
];

/**
 * wrapper class
 */
$_class  = ['wp-block-button button-icon text-center'];

/**
 * element class
 */
$_link_class = ['wp-block-button__link'];


$_attr  = ! empty( $attr ) ? $attr : '';
$_attr .= ! empty( $target ) ? ' target="' . esc_html( $target ) . '"' : '';
$_attr .= ! empty( $rel ) ? ' rel="' . esc_html( $rel ) . '"' : '';
$_url    = ! empty( $url ) ? $url : '';

$content = ! empty( $button ) ? '<span class="button__text wp-block-button__text">' . sanitize_text_field( $button ) . '</span>' : '';

if ( empty( $content ) ) {
	echo '<!-- Missing button text -->';
}

if ( ! empty( $icon ) ) {
	$content .= '<span class="button__icon wp-block-button__icon">' . codetot_svg( $icon, false ) . '</span>';
}

if ( ! empty( $icon_html ) ) {
	$content .= '<span class="button__icon wp-block-button__icon">' . $icon_html . '</span>';
}

if ( ! empty( $size ) ) {
	if ( in_array( $size, $button_sizes ) )  {
		if ($size === 'small') {
			$_class[] = ' has-heading-5-font-size';
		} elseif ( $size === 'large' ) {
			$_class[] = ' has-heading-6-font-size';
		}
	} elseif ( absint($size) ) {
		$_class[] = ' has-heading-' . absint($size) . '-font-size';
	} else {
		error_log('The button has wrong $size.');
		echo '<!-- The button has wrong $size. -->';
	}

	$_class[] = 'has-custom-font-size';
}

if ( ! empty( $type ) && in_array( $type, $button_types ) ) {
	$_raw_button_types = explode('-', $type);
	$_formatted_button_type = $_raw_button_types[0];

	if ( in_array($type, $fill_button_types) ) {
		$_class = array_merge( $_class, ['is-style-fill'] );
	} else {
		$_class = array_merge( $_class, ['is-style-' . esc_attr($_formatted_button_type)] );
	}

	switch ($type) {
		case 'primary':
			$_link_class = array_merge($_link_class, ['has-primary-background-color', 'has-white-color', 'has-text-color', 'has-background']);
			break;

		case 'secondary':
			$_link_class = array_merge($_link_class, ['has-secondary-background-color', 'has-white-color', 'has-text-color', 'has-background']);
			break;

		case 'white':
			$_link_class = array_merge($_link_class, ['has-white-background-color', 'has-dark-color', 'has-text-color', 'has-background']);
			break;

		case 'outline':
		case 'outline-dark':
		case 'link':
		case 'link-dark':
			$_link_class = array_merge($_link_class, ['has-dark-color', 'has-text-color']);
			break;

		case 'outline-white':
		case 'link-white':
			$_link_class = array_merge($_link_class, ['has-white-color', 'has-text-color']);
			break;

		case 'outline-primary':
		case 'link-primary':
			$_link_class = array_merge($_link_class, ['has-primary-color', 'has-text-color']);
			break;

		case 'outline-secondary':
		case 'link-secondary':
			$_link_class = array_merge($_link_class, ['has-secondary-color', 'has-text-color']);
			break;

		default:
			$_link_class = array_merge($_link_class, ['has-dark-background-color', 'has-white-color', 'has-text-color', 'has-background']);
			break;
	}
}

ob_start();
if ( ! empty( $url ) ) :
	printf( '<a class="%1$s" href="%2$s"%3$s>%4$s</a>', implode( ' ',  $_link_class ), esc_url( $_url ), $_attr, $content );
else :
	printf( '<button class="%1$s"%2$s>%3$s</button>', implode( ' ',  $_link_class ), $_attr, $content );
endif;
$button_html = ob_get_clean();

if ( !empty( $class ) && is_string( $class ) ) {
	$_class[] = esc_attr( $class );
}

printf('<div class="%s">%s</div>', implode( ' ',  $_class ), $button_html);

