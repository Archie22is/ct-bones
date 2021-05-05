<?php

function codetot_primary_contact_layouts()
{
    return array(
        'default' => __('Left Map - Right Content', 'ct-theme'),
        'switch' => __('Right Map - Left Content', 'ct-theme'),
        'top' => __('Top Map - Bottom Content', 'ct-theme'),
        'bottom' => __('Top Content - Bottom Map', 'ct-theme')
    );
}

function codetot_secondary_contact_layouts()
{
    return array(
        'default' => __('Top Content - Bottom Form', 'ct-theme'),
        'switch' => __('Top Form - Bottom Content', 'ct-theme'),
        'left' => __('Left Content - Right Form', 'ct-theme'),
        'right' => __('Left Form - Right Content', 'ct-theme')
    );
}

function codetot_image_types()
{
    return apply_filters('codetot_image_types', array(
        'default' => __('Default Image Size', 'ct-theme'),
        'cover'   => __('Cover Image', 'ct-theme'),
        'contain' => __('Contain Image', 'ct-theme')
    ));
}

function codetot_block_vertical_spaces()
{
    return apply_filters('codetot_block_vertical_spaces', array(
        'default' => __('Default', 'ct-theme'),
        's' => __('Small', 'ct-theme'),
        'm' => __('Medium', 'ct-theme'),
        'l' => __('Large', 'ct-theme'),
        'fullscreen' => __('Fullscreen', 'ct-theme')
    ));
}

function codetot_background_types()
{
    return apply_filters('codetot_background_types', array(
        'white' => __('White', 'ct-theme'),
        'light' => __('Light', 'ct-theme'),
        'gray' => __('Gray', 'ct-theme'),
        'dark' => __('Dark', 'ct-theme'),
        'black' => __('Black', 'ct-theme'),
        'primary' => __('Primary', 'ct-theme'),
        'secondary' => __('Secondary', 'ct-theme')
    ));
}

function codetot_background_contracts()
{
    return apply_filters('codetot_background_contracts', array(
        'light' => __('Light Background - Dark Text', 'ct-theme'),
        'dark' => __('Dark Background - White Text', 'ct-theme')
    ));
}

function codetot_text_alignments()
{
    return apply_filters('codetot_text_alignments', array(
        'left' => __('Left', 'ct-theme'),
        'center' => __('Center', 'ct-theme'),
        'right' => __('Right', 'ct-theme')
    ));
}

function codetot_button_sizes()
{
    return apply_filters('codetot_button_sizes', array(
        'normal' => __('Normal', 'ct-theme'),
        'small' => __('Small', 'ct-theme'),
        'large' => __('Large', 'ct-theme')
    ));
}

function codetot_button_targets()
{
    return apply_filters('codetot_button_targets', array(
        '_self' => __('Same Window/Tab', 'ct-theme'),
        '_blank' => __('New Window/Tab', 'ct-theme')
    ));
}

function codetot_button_styles()
{
    return apply_filters('codetot_button_styles', array(
        'primary' => __('Primary', 'ct-theme'),
        'secondary' => __('Secondary', 'ct-theme'),
        'dark' => __('Dark', 'ct-theme'),
        'outline' => __('Outline', 'ct-theme'),
        'outline-white' => __('Outline (Dark Background)', 'ct-theme'),
        'link' => __('Link', 'ct-theme'),
        'link-white' => __('Link (Dark Background)', 'ct-theme')
    ));
}

function codetot_premium_fonts()
{
    return apply_filters('codetot_premium_fonts', array(
        'Averta'          => 'Averta',
        'Avenir Next'     => 'Avenir Next',
        'Futura'          => 'Futura',
        'Myriad Pro'      => 'Myriad Pro',
        'Segoe UI'        => 'Segoe UI',
        'SF Pro Display'  => 'SF Pro Display',
        'Sanomat Sans'    => 'Sanomat Sans'
    ));
}

function codetot_google_fonts()
{
    return apply_filters('codetot_google_fonts', array(
      'Encode Sans'     => sprintf('Google: %s', 'Encode Sans'),
      'Open Sans'       => sprintf('Google: %s', 'Open Sans'),
      'Roboto'          => sprintf('Google: %s', 'Roboto'),
      'Montserrat'      => sprintf('Google: %s', 'Montserrat'),
      'Source Sans Pro' => sprintf('Google: %s', 'Source Sans Pro'),
      'Oswald'          => sprintf('Google: %s', 'Oswald'),
      'Raleway'         => sprintf('Google: %s', 'Raleway'),
      'Nunito'          => sprintf('Google: %s', 'Nunito'),
      'Poppins'         => sprintf('Google: %s', 'Poppins')
    ));
}

function codetot_footer_background_colors() {
    return array(
        'primary' => __('Brand', 'ct-theme'),
        'secondary' => __('Secondary', 'ct-theme'),
        'white'   => __('White', 'ct-theme'),
        'dark'    => __('Dark', 'ct-theme')
      );
}

function codetot_header_background_colors() {
    return array(
        'primary' => __('Brand', 'ct-theme'),
        'secondary' => __('Secondary', 'ct-theme'),
        'white'   => __('White', 'ct-theme'),
        'dark'    => __('Dark', 'ct-theme')
    );
}

function codetot_footer_widget_columns() {
    return array(
        '2-columns' => CODETOT_ADMIN_ASSETS_URI . '/2-columns.svg',
        '3-columns' => CODETOT_ADMIN_ASSETS_URI . '/3-columns.svg',
        '4-columns' => CODETOT_ADMIN_ASSETS_URI . '/4-columns.svg',
      );
}

function codetot_sidebar_layouts()
{
    return array(
        'sidebar-left'  => CODETOT_ADMIN_ASSETS_URI . '/layout-sidebar.svg',
        'sidebar-right' => CODETOT_ADMIN_ASSETS_URI . '/layout-sidebar-right.svg',
        'no-sidebar'    => CODETOT_ADMIN_ASSETS_URI . '/layout-no-sidebar.svg'
    );
}

function codetot_container_layouts() {
    return array(
        'boxed' => CODETOT_ADMIN_ASSETS_URI . '/container-boxed.svg',
        'fullwidth' => CODETOT_ADMIN_ASSETS_URI . '/container-fullwidth.svg'
    );
}

function codetot_font_size_scales()
{
    return array('1067', '1125', '1200', '1250', '1333', '1414');
}
