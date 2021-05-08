<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

if (!function_exists('get_global_option')) {
  function get_global_option($field_name) {
    $options = get_option('ct_theme');

    return !empty($options[$field_name]) ? $options[$field_name] : null;
  }
}

if (!function_exists('get_codetot_data')) {
  function get_codetot_data($field_name) {
    $options = get_option('ct_data');

    return !empty($options[$field_name]) ? $options[$field_name] : null;
  }
}

function codetot_get_color_options()
{
    $prefix = 'codetot_';

    return array(
        [
            'name'    => sprintf(__('%s Color', 'ct-bones'), __('Primary', 'ct-bones')),
            'id'      => $prefix . 'primary_color',
            'type'    => 'color',
            'std'     => '#1e73be',
            'columns' => 3,
        ],
        [
            'name'    => sprintf(__('%s Color', 'ct-bones'), __('Secondary', 'ct-bones')),
            'id'      => $prefix . 'secondary_color',
            'type'    => 'color',
            'std'     => '#d43d3d',
            'columns' => 9,
        ],
        [
            'name'    => sprintf(__('%s Color', 'ct-bones'), __('Dark', 'ct-bones')),
            'id'      => $prefix . 'dark_color',
            'type'    => 'color',
            'std'     => '#3a3a3a',
            'columns' => 3,
        ],
        [
            'name'    => sprintf(__('%s Color', 'ct-bones'), __('Body Text', 'ct-bones')),
            'id'      => $prefix . 'base_color',
            'type'    => 'color',
            'std'     => '#595959',
            'columns' => 3,
        ],
        [
            'name'    => sprintf(__('%s Color', 'ct-bones'), __('Gray', 'ct-bones')),
            'id'      => $prefix . 'gray_color',
            'type'    => 'color',
            'std'     => '#b2b2b2',
            'columns' => 3,
        ],
        [
            'name'    => sprintf(__('%s Color', 'ct-bones'), __('Light', 'ct-bones')),
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

function codetot_get_company_info_inputs()
{
  $prefix = 'codetot_';

  return array(
    [
      'name' => __('Company Name', 'ct-bones'),
      'id'   => $prefix . 'company_name',
      'type' => 'text',
    ],
    [
      'name' => __('Company Address', 'ct-bones'),
      'id'   => $prefix . 'company_address',
      'type' => 'text',
    ],
    [
      'name' => __('Company Hotline', 'ct-bones'),
      'id'   => $prefix . 'company_hotline',
      'type' => 'text',
    ],
    [
      'name' => __('Company Email', 'ct-bones'),
      'id'   => $prefix . 'company_email',
      'type' => 'email',
    ],
    [
      'name' => __('Company Google Maps Link', 'ct-bones'),
      'id'   => $prefix . 'company_google_maps_link',
      'type' => 'text',
      'desc' => __('When visiting a Contact page, the direcction link will point user directly to app Google Maps.', 'ct-bones')
    ],
    [
      'name'    => __('Footer Copyright', 'ct-bones'),
      'id'      => $prefix . 'footer_copyright',
      'type'    => 'wysiwyg',
    ]
  );
}

/**
 * @return array
 */
function codetot_get_social_media_options()
{
  $prefix = 'codetot_';

  return apply_filters('codetot_social_links', array(
    [
      'name' => __('Facebook URL', 'ct-bones'),
      'id'   => $prefix . 'company_facebook',
      'type' => 'url',
    ],
    [
      'name' => __('Youtube URL', 'ct-bones'),
      'id'   => $prefix . 'company_youtube',
      'type' => 'url',
    ],
    [
      'name' => __('Zalo Official URL', 'ct-bones'),
      'id'   => $prefix . 'company_zalo',
      'type' => 'url',
    ],
    [
      'name' => __('Instagram URL', 'ct-bones'),
      'id'   => $prefix . 'company_instagram',
      'type' => 'url',
    ],
    [
      'name' => __('Pinterest URL', 'ct-bones'),
      'id'   => $prefix . 'company_pinterest',
      'type' => 'url',
    ],
    [
      'name' => __('LinkedIn URL', 'ct-bones'),
      'id'   => $prefix . 'company_linkedin',
      'type' => 'url',
    ]
  ));
}

/**
 * @return string
 */
function codetot_get_footer_copyright()
{
  return !empty(get_codetot_data('codetot_footer_copyright'))
    ? get_codetot_data('codetot_footer_copyright')
    : '<p>' . sprintf(
      __('Copyright &copy; by <strong>%1$s</strong>. Built with <a href="%2$s" target="_blank">%3$s</a> (version: %4$s).', 'ct-bones') . '</p>',
      get_bloginfo('name'),
      esc_url('https://codetot.com'),
      esc_html__('CT Web Builder', 'ct-bones'),
      CODETOT_VERSION
    )
  ;
}

function codetot_get_google_maps_api_key() {
  return get_codetot_data('codetot_google_maps_api_key');
}

function codetot_get_social_links() {
  $items = [];
  $setting_prefix = 'codetot_company_';
  $keys = apply_filters('codetot_social_link_types', ['facebook', 'youtube', 'zalo', 'instagram',' pinterest', 'linkedin']);

  foreach ($keys as $key) {
    $value = get_codetot_data($setting_prefix . $key);

    if (!empty($value)) {
      $items[] = array(
        'type' => $key,
        'url' => $value
      );
    }
  }

  return $items;
}

function codetot_get_contact_info() {
  $items = [];
  $setting_prefix = 'codetot_company_';
  $keys = ['hotline', 'address', 'email'];

  foreach ($keys as $key) {
    $value = get_codetot_data($setting_prefix . $key);

    if (!empty($value)) {
      $items[] = array(
        'type' => $key,
        'url' => $value
      );
    }
  }

  return $items;
}
