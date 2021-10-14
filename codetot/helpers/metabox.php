<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

if (!function_exists('get_global_option')) {

  /**
   * A CT Theme Settings has been replaced with Customizer Settings.
   *
   * @param string $field_name
   * @return void
   * @deprecated
   */
  function get_global_option($field_name) {
    trigger_error(__FUNCTION__ . ': ' . esc_html__('This function has been deprecated from version 5.4.0.', 'ct-bones'), E_USER_NOTICE);

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
    return array(
        [
            'name'    => sprintf(__('%s Color', 'ct-bones'), __('Primary', 'ct-bones')),
            'id'      => 'primary_color',
            'type'    => 'color',
            'std'     => '#1e73be'
        ],
        [
            'name'    => sprintf(__('%s Color', 'ct-bones'), __('Secondary', 'ct-bones')),
            'id'      => 'secondary_color',
            'type'    => 'color',
            'std'     => '#d43d3d'
        ],
        [
            'name'    => sprintf(__('%s Color', 'ct-bones'), __('Dark', 'ct-bones')),
            'id'      => 'dark_color',
            'type'    => 'color',
            'std'     => '#3a3a3a'
        ],
        [
            'name'    => sprintf(__('%s Color', 'ct-bones'), __('Body Text', 'ct-bones')),
            'id'      => 'base_color',
            'type'    => 'color',
            'std'     => '#595959'
        ],
        [
            'name'    => sprintf(__('%s Color', 'ct-bones'), __('Gray', 'ct-bones')),
            'id'      => 'gray_color',
            'type'    => 'color',
            'std'     => '#b2b2b2'
        ],
        [
            'name'    => sprintf(__('%s Color', 'ct-bones'), __('Light', 'ct-bones')),
            'id'      => 'light_color',
            'type'    => 'color',
            'std'     => '#f2f2f2'
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
      'name' => __('Messenger URL', 'ct-bones'),
      'id'   => $prefix . 'company_messenger',
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
    ],
    [
      'name' => __('Twitter URL', 'ct-bones'),
      'id'   => $prefix . 'company_twitter',
      'type' => 'url',
    ],
    [
      'name' => __('Google Business Profile URL', 'ct-bones'),
      'id'   => $prefix . 'company_google_business_profile',
      'type' => 'url',
    ],
    [
      'name' => __('Tiktok', 'ct-bones'),
      'id'   => $prefix . 'company_tiktok',
      'type' => 'url',
    ]
  ));
}

function codetot_get_google_maps_api_key() {
  return get_codetot_data('codetot_google_maps_api_key');
}

function codetot_get_facebook_app_id() {
  return get_codetot_data('codetot_facebook_app_id');
}

function codetot_get_social_links() {
  $items = [];
  $setting_prefix = 'codetot_company_';
  $keys = apply_filters('codetot_social_link_types', ['facebook', 'youtube', 'zalo', 'messenger', 'instagram',' pinterest', 'linkedin', 'twitter', 'google_business_profile', 'tiktok']);

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
