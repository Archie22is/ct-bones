<?php
/**
 * Utilitis classes
 *
 * @param string $content
 * @return void
 */
/**
 * Minify inline css
 *
 * @param string $content
 * @return void
 */
function ct_bones_minify_inline_css( $content ) {
	$minified = str_replace( "\n", '', $content );
	$minified = str_replace( '  ', ' ', $minified );
	$minified = str_replace( '  ', ' ', $minified );
	$minified = str_replace( ' {', '{', $minified );
	$minified = str_replace( '{ ', '{', $minified );
	$minified = str_replace( ' }', '}', $minified );
	$minified = str_replace( '} ', '}', $minified );
	$minified = str_replace( ', ', ',', $minified );
	$minified = str_replace( '; ', ';', $minified );

	return str_replace( ': ', ':', $minified );
}

/**
 * Register inline style from given CSS content
 *
 * @param string $id
 * @param string $content
 */
function ct_bones_register_inline_style( $id, $content ) {
	if ( empty( $content ) ) {
		error_log( __FUNCTION__ . ': Missing content for id ' . $id );

		return;
	}

	wp_register_style( $id, false );
	wp_enqueue_style( $id );
	wp_add_inline_style( $id, ct_bones_minify_inline_css( $content ) );
}

function ct_bones_register_inline_script( $id, $content ) {
	if ( empty( $content ) ) {
		error_log( __FUNCTION__ . ': Missing content for id ' . $id );

		return;
	}

	wp_register_script( $id, false );
	wp_enqueue_script( $id, false );
	wp_add_inline_script( $id, $content );
}

/**
 * Filter CSS variables to remove custom media from inline css style
 *
 * @param string $context
 * @return void|string
 */
function ct_bones_filter_css_variables( $context ) {
	$context = preg_replace( '/@custom-media(.*);/', '', $context );
	$context = preg_replace( '/\s+/', '', $context );

	return $context;
}


/**
 * Check if child theme has font assets
 *
 * @param string $font
 * @return void
 */
function ct_bones_is_child_font_css_file( $font ) {
	$font_path = ct_bones_format_local_font_url( $font );

	$child_theme_path = get_stylesheet_directory() . '/dynamic-assets/fonts/' . esc_attr( $font_path ) . '/font.css';

	return file_exists( $child_theme_path );
}

/**
 * Format local fonts, replace text
 *
 * @param string $font_name
 * @return void|string
 */
function ct_bones_format_local_font_url( $font_name ) {
	return strtolower( str_replace( ' ', '-', $font_name ) );
}

/**
 * Format google fonts, replace text
 *
 * @param string $font_name
 * @return void|string
 */
function ct_bones_format_google_font_url( $font_name ) {
	return str_replace( ' ', '+', $font_name );
}

/**
 * Format path to include
 *
 * @param string $content
 * @param string $font
 * @return void|string
 */
function ct_bones_format_font_assets_path( $content, $font ) {
	$font_path = ct_bones_format_local_font_url( $font );
	$is_child_theme = ct_bones_is_child_font_css_file( $font );
	$path = $is_child_theme ? get_stylesheet_directory_uri() : get_template_directory_uri();

	return str_replace( 'url(\'', 'url(\'' . $path . '/dynamic-assets/fonts/' . $font_path . '/', $content );
}

function ct_bones_google_font_weight() {
	return apply_filters('ct_bones_google_font_weights', array(
		'300',
		'400',
		'500',
		'600',
		'700'
	));
}

/**
 * Get formatted inline font css
 *
 * @param string $font
 * @return void|string
 */
function ct_bones_get_google_fonts_css_inline( $font ) {
	$font_path = ct_bones_format_google_font_url( $font );
	$font_weights = ct_bones_google_font_weight();
	$font_weighs_decoded = !empty($font_weights) && is_array($font_weights) ? implode(';', $font_weights) : '';

	return "@import url('https://fonts.googleapis.com/css?family=" . esc_attr( $font_path ) . ":wght@" . $font_weighs_decoded . "&display=swap');";
}

/**
 * Get font CSS path
 *
 * @param string $font
 * @return string
 */
function ct_bones_get_local_font_url( $font ) {
	$font_path = ct_bones_format_local_font_url( $font );

	if ( ct_bones_is_child_font_css_file( $font ) ) {
		return get_stylesheet_directory() . '/dynamic-assets/fonts/' . esc_attr( $font_path ) . '/font.css';
	} else {
		return get_template_directory() . '/dynamic-assets/fonts/' . esc_attr( $font_path ) . '/font.css';
	}
}

function ct_bones_get_font_scales_config() {
	$font_scale_file     = get_template_directory() . '/codetot/metadata/font-scale.json';
	$font_scale_raw_data = file_exists( $font_scale_file ) ? file_get_contents( $font_scale_file ) : array();

	return file_exists( $font_scale_file ) ? json_decode( $font_scale_raw_data, true ) : array();
}

function ct_bones_get_font_scales() {
	$font_scale      = codetot_get_theme_mod( 'font_scale' ) ?? '1200';
	$font_scale_data = ct_bones_get_font_scales_config();

	return ! empty( $font_scale_data[ esc_attr( $font_scale ) ] ) ? $font_scale_data[ esc_attr( $font_scale ) ] : array();
}

function ct_bones_get_default_colors() {
	$default_colors = array(
		'black' => __( 'Black' ),
		'white' => __( 'White' ),
	);
	$output_colors  = array();

	foreach ( $default_colors as $slug => $color ) {
		$output_colors[] = array(
			'name'  => $color,
			'slug'  => $slug,
			'color' => sprintf( 'var(--%s)', $slug ),
		);
	}

	return $output_colors;
}

function ct_bones_get_color_schemas() {
	$output_colors = array();
	$color_schemas = array(
		'primary'   => array(
			'label'         => __( 'Primary', 'ct-bones' ),
			'default_color' => '#1e73be',
		),
		'secondary' => array(
			'label'         => __( 'Secondary', 'ct-bones' ),
			'default_color' => '#d43d3d',
		),
		'dark'      => array(
			'label'         => __( 'Dark' ),
			'default_color' => '#333',
		),
		'gray'      => array(
			'label'         => __( 'Gray' ),
			'default_color' => '#888',
		),
		'light'     => array(
			'label'         => __( 'Light' ),
			'default_color' => '#efefef',
		),
	);

	foreach ( $color_schemas as $slug => $color ) {
		$existing_color_value = codetot_get_theme_mod( $slug . '_color' );

		$output_colors[] = array(
			'name'  => esc_attr( $color['label'] ),
			'slug'  => esc_attr( $slug ),
			'color' => ! empty( $existing_color_value ) ? sprintf( 'var(--%s)', esc_attr( $slug ) ) : esc_attr( $color['default_color'] ),
		);
	}

	return apply_filters('ct_bones_color_schemas', $output_colors);
}
