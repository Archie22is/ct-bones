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

/**
 * wrapper class
 */
$_class  = ['wp-block-button'];

/**
 * element class
 */
$_link_class = ['wp-block-button__link'];


$_attr  = ! empty( $attr ) ? $attr : '';
$_attr .= ! empty( $target ) ? ' target="' . esc_html( $target ) . '"' : '';
$_attr .= ! empty( $rel ) ? ' rel="' . esc_html( $rel ) . '"' : '';
$_url    = ! empty( $url ) ? $url : '';

$content = ! empty( $button ) ? sanitize_text_field( $button ) : '';

if ( empty( $content ) ) {
	echo '<!-- Missing button text -->';
}

if ( ! empty( $icon ) ) {
	/** Alternative: button-icon block */
	error_log('The button is not support $icon anymore.');
	echo '<!-- The button is not support $icon anymore. -->';
}

if ( ! empty( $icon_html ) ) {
	error_log('The button is not support $icon_html anymore.');
	echo '<!-- The button is not support $icon_html anymore. -->';
}

if ( ! empty( $size ) ) {
	error_log('The button is not support $size anymore.');
	echo '<!-- The button is not support $size anymore. -->';
}

if ( ! empty( $type ) && in_array( $type, $button_types ) ) {
	$_raw_button_types = explode('-', $type);
	$_formatted_button_type = $_raw_button_types[0];

	$_class = array_merge( $_class, ['is-style-' . esc_attr($_formatted_button_type)] );

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
			$_link_class = array_merge($_link_class, ['has-primary-color', 'has-text-color']);
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

