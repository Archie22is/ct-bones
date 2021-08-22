<?php

add_action('init', 'codetot_sync_settings');

function codetot_sync_settings() {
  if (!empty($_GET['upgrade']) && $_GET['upgrade'] === 'ct_theme' && WP_DEBUG) :
    $old_settings = get_option('ct_theme');
    $existing_new_theme_settings = get_theme_mod('codetot_theme_settings');
    $existing_pro_settings = get_theme_mod('codetot_pro_settings');

    $new_theme_settings = [];
    $pro_settings = [];

    // Font size + font body + font heading
    $exchange_theme_keys = array(
      // Typography settings
      'codetot_font_size_scale' => 'font_scale',
      'codetot_font_family' => 'body_font',
      'codetot_font_heading' => 'heading_font',
      // Footer settings
      'codetot_footer_columns' => 'footer_widget_column',
      'codetot_settings_remove_theme_copyright' => 'hide_footer_copyright',
      // Header settings
      'codetot_header_layout' => 'header_layout',
      'codetot_header_background_color' => 'header_background_color',
      'codetot_header_color_contract' => 'header_text_contract',
      'codetot_header_enable_sticky' => 'header_sticky_type',
      // Topbar settings
      'codetot_header_topbar_enable' => 'enable_topbar',
      'codetot_topbar_layout' => 'topbar_widget_column',
      // Global Layout
      'codetot_post_list_layout' => 'archive_post_layout',
      'codetot_category_column_number' => 'archive_post_column',
      'codetot_container_width' => 'container_width',
      'codetot_post_layout' => 'post_layout',
      'codetot_page_layout' => 'page_layout',
      'codetot_category_layout' => 'category_layout'
    );

    $exchange_pro_keys = array(
      'codetot_enable_mega_menu' => 'enable_mega_menu',
      'codetot_settings_enable_back_to_top' => 'enable_back_to_top'
    );

    // Colors
    $color_keys = codetot_get_color_options();
    foreach ($color_keys as $color_key) {
      $new_key = str_replace('codetot_', '', $color_key['id']);

      $new_theme_settings[$new_key] = $old_settings[$color_key['id']];
    }

    foreach ($exchange_theme_keys as $old_key => $new_key) {
      if (!empty($old_settings[$old_key])) :
        $new_value = str_replace('-columns', '', $old_settings[$old_key]);
        $new_value = str_replace('header-', '', $new_value);

        $new_theme_settings[$new_key] = $new_value;
      else :
        echo "$old_key has no value." . PHP_EOL;
      endif;
    }

    foreach ($exchange_pro_keys as $old_key => $new_key) {
      if (isset($old_settings[$old_key])) :
        if (is_string($old_settings[$old_key])) {
          $new_value = str_replace('-columns', '', $old_settings[$old_key]);
          $new_value = str_replace('header-', '', $new_value);
        }

        $pro_settings[$new_key] = $new_value;
      else :
        echo "$old_key has no value." . PHP_EOL;
      endif;
    }

    $old_theme_keys = array_keys($exchange_theme_keys);
    $pro_keys = array_keys($exchange_pro_keys);

    foreach ($old_settings as $key => $value) {
      if (in_array($key, $old_theme_keys)) {
        unset($old_settings[$key]);
      }

      if (in_array($key, $pro_keys)) {
        unset($old_settings[$key]);
      }
    }

    // Process keys

    echo 'Old settings';
    var_dump($old_settings);

    echo 'New Theme Settings';
    var_dump($existing_new_theme_settings);

    echo 'Pro Settings';
    var_dump($existing_pro_settings);

    var_dump($new_theme_settings);

    exit;

  endif;
}
