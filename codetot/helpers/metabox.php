<?php

function codetot_get_color_options()
{
    $prefix = 'codetot_';

    return array(
        [
            'name'    => sprintf(__('%s Color', 'ct-theme'), __('Brand', 'ct-theme')),
            'id'      => $prefix . 'primary_color',
            'type'    => 'color',
            'std'     => '#1e73be',
            'columns' => 3,
        ],
        [
            'name'    => sprintf(__('%s Color', 'ct-theme'), __('Secondary', 'ct-theme')),
            'id'      => $prefix . 'secondary_color',
            'type'    => 'color',
            'std'     => '#d43d3d',
            'columns' => 9,
        ],
        [
            'name'    => sprintf(__('%s Color', 'ct-theme'), __('Dark', 'ct-theme')),
            'id'      => $prefix . 'dark_color',
            'type'    => 'color',
            'std'     => '#3a3a3a',
            'columns' => 3,
        ],
        [
            'name'    => sprintf(__('%s Color', 'ct-theme'), __('Body Text', 'ct-theme')),
            'id'      => $prefix . 'base_color',
            'type'    => 'color',
            'std'     => '#595959',
            'columns' => 3,
        ],
        [
            'name'    => sprintf(__('%s Color', 'ct-theme'), __('Gray', 'ct-theme')),
            'id'      => $prefix . 'gray_color',
            'type'    => 'color',
            'std'     => '#b2b2b2',
            'columns' => 3,
        ],
        [
            'name'    => sprintf(__('%s Color', 'ct-theme'), __('Light', 'ct-theme')),
            'id'      => $prefix . 'light_color',
            'type'    => 'color',
            'std'     => '#f2f2f2',
            'columns' => 3,
        ]
    );
}

/**
 * @return array
 */
function codetot_get_font_size_scale_options()
{
    $types = codetot_font_size_scales();
    $items = [];
    foreach ($types as $type) {
        $items[$type] = CODETOT_ADMIN_ASSETS_URI . '/font-' . esc_attr($type) . '.png';
    }

    return $items;
}

function codetot_get_font_family_options()
{
    return array_merge(
        codetot_premium_fonts(),
        codetot_google_fonts()
    );
}

function codetot_get_header_options()
{
    $header_options = array();
    $header_presets = 7;
    for ($i = 0; $i < $header_presets; $i++) {
        $index = 'header-' . ($i + 1);
        $header_options[$index] = CODETOT_ADMIN_ASSETS_URI . '/header-style-' . ($i + 1) . '.jpg';
    }

    return $header_options;
}
