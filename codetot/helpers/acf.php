<?php

/**
 * Get contact section - primary layout settings
 *
 * @return array
 */
function codetot_primary_contact_layouts() {
	return array(
		'default' => __( 'Left Map - Right Content', 'ct-bones' ),
		'switch'  => __( 'Right Map - Left Content', 'ct-bones' ),
		'top'     => __( 'Top Map - Bottom Content', 'ct-bones' ),
		'bottom'  => __( 'Top Content - Bottom Map', 'ct-bones' ),
	);
}

/**
 * Get contact section - secondary layout settings
 */
function codetot_secondary_contact_layouts() {
	return array(
		'default' => __( 'Top Content - Bottom Form', 'ct-bones' ),
		'switch'  => __( 'Top Form - Bottom Content', 'ct-bones' ),
		'left'    => __( 'Left Content - Right Form', 'ct-bones' ),
		'right'   => __( 'Left Form - Right Content', 'ct-bones' ),
	);
}

/**
 * Get image type
 *
 * @return array
 */
function codetot_image_types() {
	return apply_filters(
		'codetot_image_types',
		array(
			'default' => __( 'Default Image Size', 'ct-bones' ),
			'cover'   => __( 'Cover Image', 'ct-bones' ),
			'contain' => __( 'Contain Image', 'ct-bones' ),
		)
	);
}

/**
 * Get section vertical spaces
 *
 * @return array
 */
function codetot_block_vertical_spaces() {
	return apply_filters(
		'codetot_block_vertical_spaces',
		array(
			'default'    => __( 'Default', 'ct-bones' ),
			's'          => __( 'Small', 'ct-bones' ),
			'm'          => __( 'Medium', 'ct-bones' ),
			'l'          => __( 'Large', 'ct-bones' ),
			'fullscreen' => __( 'Fullscreen', 'ct-bones' ),
		)
	);
}

/**
 * Get background types
 *
 * @return array
 */
function codetot_background_types() {
	return apply_filters(
		'codetot_background_types',
		array(
			'white'     => __( 'White', 'ct-bones' ),
			'light'     => __( 'Light', 'ct-bones' ),
			'gray'      => __( 'Gray', 'ct-bones' ),
			'dark'      => __( 'Dark', 'ct-bones' ),
			'black'     => __( 'Black', 'ct-bones' ),
			'primary'   => __( 'Primary', 'ct-bones' ),
			'secondary' => __( 'Secondary', 'ct-bones' ),
		)
	);
}

/**
 * Get background contracts
 *
 * @return array
 */
function codetot_background_contracts() {
	return apply_filters(
		'codetot_background_contracts',
		array(
			'light' => __( 'Light Background - Dark Text', 'ct-bones' ),
			'dark'  => __( 'Dark Background - White Text', 'ct-bones' ),
		)
	);
}

/**
 * Get text alignments
 *
 * @return array
 */
function codetot_text_alignments() {
	return apply_filters(
		'codetot_text_alignments',
		array(
			'left'   => __( 'Left', 'ct-bones' ),
			'center' => __( 'Center', 'ct-bones' ),
			'right'  => __( 'Right', 'ct-bones' ),
		)
	);
}

/**
 * Get button sizes
 *
 * @return array
 */
function codetot_button_sizes() {
	return apply_filters(
		'codetot_button_sizes',
		array(
			'normal' => __( 'Normal', 'ct-bones' ),
			'small'  => __( 'Small', 'ct-bones' ),
			'large'  => __( 'Large', 'ct-bones' ),
		)
	);
}

/**
 * Get button targets
 *
 * @return array
 */
function codetot_button_targets() {
	return apply_filters(
		'codetot_button_targets',
		array(
			'_self'  => __( 'Same Window/Tab', 'ct-bones' ),
			'_blank' => __( 'New Window/Tab', 'ct-bones' ),
		)
	);
}

/**
 * Get button styles
 *
 * @return void
 */
function codetot_button_styles() {
	return apply_filters(
		'codetot_button_styles',
		array(
			'primary'       => __( 'Primary', 'ct-bones' ),
			'secondary'     => __( 'Secondary', 'ct-bones' ),
			'dark'          => __( 'Dark', 'ct-bones' ),
			'outline'       => __( 'Outline', 'ct-bones' ),
			'outline-white' => __( 'Outline (Dark Background)', 'ct-bones' ),
			'link'          => __( 'Link', 'ct-bones' ),
			'link-white'    => __( 'Link (Dark Background)', 'ct-bones' ),
		)
	);
}

/**
 * Get premium fonts
 *
 * @return array
 */
function codetot_premium_fonts() {
	return apply_filters(
		'codetot_premium_fonts',
		array(
			'Averta'                => 'Averta',
			'Gilroy'                => 'Gilroy',
			'SF Pro Display'        => 'SF Pro Display',
			'Avenir Next'           => 'Avenir Next',
			'Futura'                => 'Futura',
			'Myriad Pro'            => 'Myriad Pro',
			'Segoe UI'              => 'Segoe UI',
			'Sanomat Sans'          => 'Sanomat Sans',
			'San Francisco Display' => 'San Francisco Display',
			'Poppins' => 'Poppins'
		)
	);
}

/**
 * Get Google fonts
 *
 * @return array
 */
function codetot_google_fonts() {
	return apply_filters(
		'codetot_google_fonts',
		array(
			'Encode Sans'     => sprintf( 'Google: %s', 'Encode Sans' ),
			'Open Sans'       => sprintf( 'Google: %s', 'Open Sans' ),
			'Roboto'          => sprintf( 'Google: %s', 'Roboto' ),
			'Montserrat'      => sprintf( 'Google: %s', 'Montserrat' ),
			'Source Sans Pro' => sprintf( 'Google: %s', 'Source Sans Pro' ),
			'Oswald'          => sprintf( 'Google: %s', 'Oswald' ),
			'Raleway'         => sprintf( 'Google: %s', 'Raleway' ),
			'Nunito'          => sprintf( 'Google: %s', 'Nunito' ),
		)
	);
}

/**
 * Get Footer background colors
 *
 * @return array
 */
function codetot_footer_background_colors() {
	return array(
		'primary'   => __( 'Brand', 'ct-bones' ),
		'secondary' => __( 'Secondary', 'ct-bones' ),
		'white'     => __( 'White', 'ct-bones' ),
		'dark'      => __( 'Dark', 'ct-bones' ),
	);
}

/**
 * Get Header background colors
 *
 * @return array
 */
function codetot_header_background_colors() {
	return array(
		'primary'   => __( 'Brand', 'ct-bones' ),
		'secondary' => __( 'Secondary', 'ct-bones' ),
		'white'     => __( 'White', 'ct-bones' ),
		'dark'      => __( 'Dark', 'ct-bones' ),
	);
}

/**
 * Undocumented function
 *
 * @return void
 * @deprecated 5.7.0
 */
function codetot_footer_widget_columns() {
	return array(
		'2-columns' => CODETOT_ADMIN_ASSETS_URI . '/2-columns.svg',
		'3-columns' => CODETOT_ADMIN_ASSETS_URI . '/3-columns.svg',
		'4-columns' => CODETOT_ADMIN_ASSETS_URI . '/4-columns.svg',
	);
}

/**
 * Undocumented function
 *
 * @return void
 * @deprecated 5.7.0
 */
function codetot_sidebar_layouts() {
	return array(
		'sidebar-left'  => CODETOT_ADMIN_ASSETS_URI . '/layout-sidebar.svg',
		'sidebar-right' => CODETOT_ADMIN_ASSETS_URI . '/layout-sidebar-right.svg',
		'no-sidebar'    => CODETOT_ADMIN_ASSETS_URI . '/layout-no-sidebar.svg',
	);
}

/**
 * Undocumented function
 *
 * @return void
 * @deprecated 5.7.0
 */
function codetot_container_layout_options() {
	return array(
		'fullwidth' => __( 'Fullwidth', 'ct-bones' ),
		'boxed'     => __( 'Boxed', 'ct-bones' ),
	);
}

/**
 * Undocumented function
 *
 * @return void
 * @deprecated 5.7.0
 */
function codetot_container_layouts() {
	return array(
		'boxed'     => CODETOT_ADMIN_ASSETS_URI . '/container-boxed.svg',
		'fullwidth' => CODETOT_ADMIN_ASSETS_URI . '/container-fullwidth.svg',
	);
}

/**
 * Undocumented function
 *
 * @return array
 * @deprecated 5.7.0
 */
function codetot_font_size_scales() {
	return array( '1067', '1125', '1200', '1250', '1333', '1414' );
}
