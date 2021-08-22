<?php

add_action('init', 'codetot_sync_settings');

function codetot_sync_settings() {
  // Access url: site.com??upgrade=ct_theme
  // Khi kiểm tra thấy dữ liệu có thể đồng bộ đúng, click vào nút Update settings ở cuối cùng
  if (!empty($_GET['upgrade']) && $_GET['upgrade'] === 'ct_theme' && WP_DEBUG) :
    $old_settings = get_option('ct_theme');
    $existing_new_theme_settings = get_theme_mod('codetot_theme_settings');
    $existing_pro_settings = get_theme_mod('codetot_pro_settings');

    $new_theme_settings = [];
    $pro_settings = [];

    // From old theme key to new theme key, save on codetot_theme_options
    $exchange_theme_keys = array(
      // Excude colors
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
      'codetot_header_hide_account_icon' => 'header_hide_account_icon',
      'codetot_header_hide_search_icon' => 'header_hide_search_icon',
      'codetot_header_hide_cart_icon' => 'header_hide_cart_icon',
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

    // From old theme key to new pro key, save on codetot_pro_options
    $exchange_pro_keys = array(
      'codetot_enable_mega_menu' => 'enable_mega_menu',
      'codetot_settings_enable_back_to_top' => 'enable_back_to_top'
    );

    // Because colors are calling as array, we run
    $color_keys = codetot_get_color_options();
    foreach ($color_keys as $color_key) {
      $new_key = str_replace('codetot_', '', $color_key['id']);
      $value = $old_settings[$color_key['id']];

      echo '<p>';
      echo "SUCCESS: Updating $new_key with value $value";
      echo '</p>';
      $new_theme_settings[$new_key] = $value;
    }

    foreach ($exchange_theme_keys as $old_key => $new_key) {
      if (!empty($old_settings[$old_key])) :
        $new_value = str_replace('-columns', '', $old_settings[$old_key]);
        $new_value = str_replace('header-', '', $new_value);

        $new_theme_settings[$new_key] = $new_value;
      else :
        echo '<p>' . 'exchange_theme_keys:: ';
        echo "$old_key has no value.";
        echo '</p>';
      endif;
    }

    foreach ($exchange_pro_keys as $old_key => $new_key) {
      if (isset($old_settings[$old_key])) :
        $new_value = $old_settings[$old_key];

        if (is_string($old_settings[$old_key])) {
          $new_value = str_replace('-columns', '', $new_value);
          $new_value = str_replace('header-', '', $new_value);
        }

        $pro_settings[$new_key] = $new_value;
      else :
        echo '<p>';
        echo "$old_key has no value";
        echo '</p>';
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

    echo '<br><hr>';

    // Process keys

    echo 'Old settings';
    var_dump($old_settings);

    echo 'New Theme Settings';
    var_dump($existing_new_theme_settings);

    echo 'Pro Settings';
    var_dump($existing_pro_settings);

    echo 'New Theme Settings';
    var_dump($new_theme_settings);

    echo 'New Pro Settings';
    var_dump($pro_settings);

    echo '<p>Ready to update? Please backup database before starting!</p>';

    global $wp;
    $current_url = home_url( add_query_arg( array(), $wp->request ) );

    printf('<p><a href="%1$s">Update query</a></p>',
      add_query_arg('action', 'update', $current_url)
    );

    if (isset($_GET['action']) && $_GET['action'] === 'update') {
      set_theme_mod('codetot_theme_options', $new_theme_settings);
      set_theme_mod('codetot_pro_options', $pro_settings);
    }

    exit;


  endif;
}
