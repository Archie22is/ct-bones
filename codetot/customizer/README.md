# Customizer Settings

## Cấu trúc và đăng ký field settings

Customizer có các settings, nhưng sẽ cần đăng ký lần lượt:

- Đăng ký panel (lớp menu ngoài cùng)

```php
$wp_customize->add_panel();
```

- Đăng ký section (các lớp menu phụ thứ 2, chỉ dùng để phân chia)

```php
$wp_customizer->add_section();

// Function này được công ty chuyển thành 1 function helper để tránh code lặp
codetot_customizer_register_section(array(), $wp_customize); // Cần pass $wp_customize vào
```

- Đăng ký setting và control (một setting cần 1 control thì mới hoạt động)

```php
$wp_customizer->add_setting('id', array());
$wp_customizer->add_control();

// Hai function này được thay thế bằng 1 helper function để tránh code lặp
codetot_customizer_register_control(array(), $wp_customize);
```

## Nguyên tắc sử dụng filter cho các options và text value

Trong `settings.php`, các option nên sử dụng cả filter để dễ dàng mở rộng trong các file khác.

```php
$layout_options = apply_filters('codetot_theme_layout_options', array(
    'category' => esc_html__('Category', 'ct-bones'),
    'post' => esc_html__('Post', 'ct-bones'),
    'page' => esc_html__('Page', 'ct-bones')
));

// Trong file woocommerce-settings.php đã mở rộng phần filter này là 1 ví dụ:
add_filter('codetot_theme_layout_options', array($this, 'layout_options'));
function layout_options($options) {
    // Gộp 2 array tương tự array_merge($args_1, $args_2);
    return wp_parse_args(array(
      'shop' => esc_html__('Shop page', 'ct-bones'),
      'product_category' => esc_html__('Product Category', 'ct-bones'),
      'single_product' => esc_html__('Single Product', 'ct-bones')
    ), $options);
}
```

## Lấy dữ liệu

```php
function codetot_get_theme_mod($field_id, $type) {
    // $type nếu không truyền sẽ lấy từ 'codetot_theme_settings', có thể truyền 'codetot_pro_settings', 'codetot_woocommerce_settings'
    // Chia ra làm nhiều settings để sau tách thành các plugin độc lập

    // Lưu ý: check điều kiện isset() chứ không check !empty() để có giá trị trung thực
}

// Khi call bên ngoài
$page_layout = codetot_get_theme_mod('page_layout') ?? 'no-sidebar;
```

## Đồng bộ settings

Trong file `sync-settings.php` đang viết nháp phần cập nhật dữ liệu từ CT Theme và CT Data để chuyển sang lưu trong Customizer.

Đọc file này để biết thêm. Hiện chưa hoàn tất cho tới khi toàn bộ field của CT Theme và CT Data chuyển xong.

## Quy tắc đặt tên

- `_options` là các key của `add_panel()`
- `_settings` là các key của `add_section()`
- `_settings[key_name]` là các key của `add_control()`
