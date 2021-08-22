<?php

add_action('init', 'codetot_sync_settings');

function codetot_sync_settings() {
  // Access url: site.com??upgrade=ct_theme
  // Khi kiểm tra thấy dữ liệu có thể đồng bộ đúng, click vào nút Update settings ở cuối cùng
  if (!empty($_GET['upgrade']) && $_GET['upgrade'] === 'ct_theme' && WP_DEBUG) :
    $old_settings = get_option('ct_theme');
    $existing_new_theme_settings = get_theme_mod('codetot_theme_settings');
    $existing_pro_settings = get_theme_mod('codetot_pro_settings');
    $existing_woo_settings = get_theme_mod('codetot_woocommerce_settings');

    $new_theme_settings = [];
    $pro_settings = [];
    $woo_settings = [];

    echo '<h1>Sync Settings from CT Theme to Customizer</h1>';
    echo '<h2>You should backup database via phpMyAdmin before proceed.</h2>';

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
      'codetot_settings_footer_hide_social_links' => 'footer_hide_social_links',
      // Header settings
      'codetot_header_layout' => 'header_layout',
      'codetot_header_background_color' => 'header_background_color',
      'codetot_header_color_contract' => 'header_text_contract',
      'codetot_header_enable_sticky' => 'header_sticky_type',
      'codetot_header_hide_account_icon' => 'header_hide_account_icon',
      'codetot_header_hide_search_icon' => 'header_hide_search_icon',
      'codetot_header_hide_cart_icon' => 'header_hide_cart_icon',
      'codetot_home_icon_menu' => 'header_menu_home_icon',
      'codetot_header_display_phone' => 'header_display_phone_number',
      // Topbar settings
      'codetot_header_topbar_enable' => 'enable_topbar',
      'codetot_topbar_layout' => 'topbar_widget_column',
      // Global Layout
      'codetot_post_list_layout' => 'archive_post_layout',
      'codetot_category_column_number' => 'archive_post_column',
      'codetot_container_width' => 'container_width',
      'codetot_post_layout' => 'post_layout',
      'codetot_page_layout' => 'page_layout',
      'codetot_category_layout' => 'category_layout',
      'codetot_post_card_style' => 'post_card_style',
      // Single post
      'codetot_settings_hide_post_meta' => 'hide_post_meta',
      'codetot_settings_hide_social_share' => 'hide_social_share',
      'codetot_settings_hide_featured_image' => 'hide_featured_image',
      'codetot_settings_hide_related_posts' => 'hide_related_posts'
    );

    // From old theme key to new pro key, save on codetot_pro_options
    $exchange_pro_keys = array(
      'codetot_enable_mega_menu' => 'enable_mega_menu',
      'codetot_settings_enable_back_to_top' => 'enable_back_to_top',
      'codetot_homepage_heading' => 'seo_h1_homepage',
      'codetot_settings_enable_hero_image_single_post' => 'extra_single_post_layout'
    );

    $exchange_woo_keys = array(
      'codetot_woocommerce_enable_quick_view' => 'enable_quick_view',
      'codetot_woocommerce_enable_description_in_quick_view' => 'quick_view_short_description',
      'codetot_woocommerce_enable_product_video' => 'enable_product_video',
      'codetot_woocommerce_enable_minicart' => 'enable_mini_cart',
      'codetot_woocommerce_login_popup' => 'enable_login_popup',
      'codetot_woocommerce_product_card_style' => 'product_card_style',
      'codetot_woocommerce_product_image_visible' => 'product_card_image_type',
      'codetot_woocommerce_hide_product_stock_status' => 'hide_product_stock_status',
      'codetot_woocommerce_enable_facebook_comment' => 'single_product_enable_facebook_comment',
      'codetot_woocommerce_enable_product_star_rating_in_list' => 'archive_product_star_rating',
      'codetot_woocommerce_enable_countdown_price' => 'single_product_enable_countdown',
      'codetot_woocommerce_countdown_product_style' => 'single_product_countdown_style',
      'codetot_woocommerce_product_thumbnails_columns' => 'single_product_gallery_thumbnail_column',
      'codetot_woocommerce_product_thumbnails_count' => 'single_product_gallery_thumbnail_style',
      'codetot_woocommerce_cross_sell_products_colums' => 'single_product_cross_sell_column',
      'codetot_woocommerce_upsells_products_colums' => 'single_product_upsell_column',
      'codetot_woocommerce_enable_viewed_products_section' => 'enable_viewed_product_section',
      'codetot_woocommerce_viewed_products_colums' => 'single_product_viewed_products_column'
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

    foreach ($exchange_woo_keys as $old_key => $new_key) {
      if (isset($old_settings[$old_key])) :
        $new_value = $old_settings[$old_key];

        if (is_string($old_settings[$old_key])) {
          $new_value = str_replace('-columns', '', $new_value);
          $new_value = str_replace('header-', '', $new_value);
        }

        $woo_settings[$new_key] = $new_value;
      else :
        echo '<p>';
        echo "$old_key has no value";
        echo '</p>';
      endif;
    }

    $old_theme_keys = wp_parse_args(array_keys($exchange_theme_keys), wp_list_pluck($color_keys, 'id'));
    $pro_keys = array_keys($exchange_pro_keys);
    $woo_keys = array_keys($exchange_woo_keys);

    foreach ($old_settings as $key => $value) {
      if (in_array($key, $old_theme_keys)) {
        unset($old_settings[$key]);
      }

      if (in_array($key, $pro_keys)) {
        unset($old_settings[$key]);
      }

      if (in_array($key, $woo_keys)) {
        unset($old_settings[$key]);
      }
    }

    echo '<br><hr>';

    // Process keys

    echo 'Old settings (If you update, those settings will be lost.';
    var_dump($old_settings);

    echo 'Existing Theme Settings, will be override with new settings.';
    var_dump($existing_new_theme_settings);

    echo 'Existing Pro Settings, will be override with new settings.';
    var_dump($existing_pro_settings);

    echo 'Existing WooCommercer Settings, will be override with new settings.';
    var_dump($existing_woo_settings);

    echo 'New Theme Settings, will be available on [CT] Theme Options.';
    var_dump($new_theme_settings);

    echo 'New Pro Settings, will be available on [CT] Pro Options';
    var_dump($pro_settings);

    echo 'New WooCommerce Settings, will be available on [CT] WooCommerce Options';
    var_dump($woo_settings);

    echo '<p>Ready to update? Please backup database before starting!</p>';

    global $wp;
    $current_url = home_url(add_query_arg(array($_GET), $wp->request));

    printf('<p><a href="%1$s">Update query</a></p>',
      add_query_arg('action', 'update', $current_url)
    );

    if (isset($_GET['action']) && $_GET['action'] === 'update') {
      set_theme_mod('codetot_theme_settings', $new_theme_settings);
      set_theme_mod('codetot_pro_settings', $pro_settings);
      set_theme_mod('codetot_woocommerce_settings', $woo_settings);

      echo '<p style="color: red;">The process has been completed</p>';
    }

    exit;


  endif;
}
