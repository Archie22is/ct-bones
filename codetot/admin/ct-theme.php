<?php

// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_CT_Theme_Settings
{
  /**
   * Singleton instance
   *
   * @var Codetot_CT_Theme_Settings
   */
  private static $instance;
  /**
   * Get singleton instance.
   *
   * @return Codetot_CT_Theme_Settings
   */
  public final static function instance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function __construct()
  {
    $this->prefix = 'codetot_';
    $this->filter_prefix = 'codetot_settings_';
    $this->setting_id = 'ct-bones';
    $this->option_name = 'ct_theme';

    add_filter('mb_settings_pages', array($this, 'register_settings_pages'));

    add_filter('rwmb_meta_boxes', array($this, 'register_general_settings_fields'));
    add_filter('rwmb_meta_boxes', array($this, 'register_addons_settings_fields'));
    add_filter('rwmb_meta_boxes', array($this, 'register_font_family_settings_fields'));
    add_filter('rwmb_meta_boxes', array($this, 'register_layout_settings_fields'));
    add_filter('rwmb_meta_boxes', array($this, 'register_header_settings_fields'));
    add_filter('rwmb_meta_boxes', array($this, 'register_footer_settings_fields'));
  }

  public function register_settings_pages($setting_pages)
  {
    $settings_pages[] = [
      'menu_title'    => __('CT Theme', 'ct-bones'),
      'id'            => $this->setting_id,
      'option_name'   => $this->option_name,
      'capability'    => 'level_10',
      'style'         => 'no-boxes',
      'columns'       => 1,
      'tabs'          => apply_filters('codetot_settings_tabs', array(
        'general'     => __('General', 'ct-bones'),
        'addons'  => __('Addons', 'ct-bones'),
        'layout'      => __('Layout', 'ct-bones'),
        'header'      => __('Header', 'ct-bones'),
        'footer'      => __('Footer', 'ct-bones')
      )),
      'submit_button' => __('Save'),
      'customizer'    => false,
      'icon_url'      => 'dashicons-forms',
    ];

    return $settings_pages;
  }

  public function register_general_settings_fields($meta_boxes)
  {
    $fields = array_merge(
      array(
        [
          'type'    => 'heading',
          'name'    => __('Color Schema', 'ct-bones')
        ]
      ),
      apply_filters('codetot_color_fields', codetot_get_color_options()),
      array(
        [
          'name'   => __('Font Size Scale', 'ct-bones'),
          'id'     => $this->prefix . 'font_size_scale',
          'type'   => 'image_select',
          'class' => 'codetot-text-select',
          'std'    => 1200,
          'inline' => false,
          'options' => codetot_get_font_size_scale_options()
        ]
      )
    );

    $meta_boxes[] = [
      'title'          => __('General', 'ct-bones'),
      'id'             => 'ct-theme-general-settings',
      'settings_pages' => [$this->setting_id],
      'tab'            => 'general',
      'fields'         => apply_filters('codetot_general_fields', $fields),
    ];

    return $meta_boxes;
  }

  public function register_addons_settings_fields($meta_boxes)
  {
    $meta_boxes[] = [
      'title'          => __('Addons', 'ct-bones'),
      'id'             => 'ct-theme-addons-settings',
      'settings_pages' => [$this->setting_id],
      'tab'            => 'addons',
      'fields'         => [
        [
          'type'      => 'switch',
          'id'        => $this->prefix . 'enable_mega_menu',
          'name'      => __('Enable Mega Menu?', 'ct-bones'),
          'desc'    => __('Display mega menu in Primary Menu', 'ct-bones'),
          'style'     => 'rounded',
        ],
        [
          'name'    => __('Store Locator Maps', 'ct-bones'),
          'id'      => $this->filter_prefix . 'enable_store_locator_map',
          'type'    => 'switch',
          'style'   => 'rounded',
          'desc' => __('Enable maps with multiple store locations.', 'ct-bones')
        ],
      ],
    ];

    return $meta_boxes;
  }

  public function register_font_family_settings_fields($meta_boxes)
  {
    $meta_boxes[] = [
      'title'          => __('Font Family', 'ct-bones'),
      'settings_pages' => [$this->setting_id],
      'tab'            => 'general',
      'fields'         => [
        [
          'type' => 'heading',
          'name' => __('Font Family', 'ct-bones'),
          'columns' => 12,
        ],
        [
          'name'     => __('Global Font', 'ct-bones'),
          'id'       => $this->prefix . 'font_family',
          'type'     => 'select',
          'options'  => apply_filters('codetot_font_family_options', codetot_get_font_family_options()),
          'columns'  => 12,
        ],
        [
          'name'     => __('Heading Font', 'ct-bones'),
          'id'       => $this->prefix . 'font_heading',
          'type'     => 'select',
          'options'  => apply_filters('codetot_font_family_options', codetot_get_font_family_options()),
          'columns'  => 12,
        ],
      ],
    ];

    return $meta_boxes;
  }

  public function register_layout_settings_fields($meta_boxes)
  {
    $default_layouts = apply_filters(
      'codetot_layout_settings',
      [
        'category' => __('Category', 'ct-bones'),
        'post' => __('Post', 'ct-bones'),
        'page' => __('Page', 'ct-bones')
      ]
    );
    foreach ($default_layouts as $key => $label) {
      $layout_fields[] = array(
        'name' => sprintf(__('%s Layout', 'ct-bones'), $label),
        'id'   => $this->prefix . str_replace('-', '_', $key) . '_layout',
        'type' => 'image_select',
        'class' => 'codetot-image-icon',
        'columns' => apply_filters('codetot_layout_settings_column', 3),
        'options' => codetot_sidebar_layouts()
      );
    }

    $layout_fields = array_merge(
      array(
        array(
          'type' => 'heading',
          'name' => __('Sidebar Layout', 'ct-bones')
        )
      ),
      $layout_fields,
      array(
        [
          'type'    => 'heading',
          'name'    => __('Container Layout', 'ct-bones')
        ],
        [
          'name'    => __('Container Width', 'ct-bones'),
          'id'      => $this->prefix . 'container_width',
          'type'    => 'number',
          'desc'    => __('(pixel) Only work with boxed container layout.', 'ct-bones'),
          'min'     => 900,
          'max'     => 1440,
          'std'     => 1280
        ]
      ),
      array(
        [
          'type' => 'heading',
          'name' => __('Category Layout', 'ct-bones'),
        ],
        [
          'name'    => __('Archive/Category: Posts Per Row', 'ct-bones'),
          'id'      => $this->prefix . 'category_column_number',
          'type'    => 'select',
          'std'     => 3,
          'options' => [
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5
          ],
        ],
        [
          'name'    => __('Post Card Style', 'ct-bones'),
          'id'      => $this->prefix . 'post_card_style',
          'type'    => 'select',
          'std'     => 'style-1',
          'options' => [
            'style-1' => sprintf(__('Style %s', 'ct-bones'), 1),
            'style-2' => sprintf(__('Style %s', 'ct-bones'), 2),
            'style-3' => sprintf(__('Style %s', 'ct-bones'), 3),
            'style-4' => sprintf(__('Style %s', 'ct-bones'), 4)
          ],
        ]
        ),
        array(
          [
            'type' => 'heading',
            'name' => __('Single Post Layout', 'ct-bones'),
          ],
          [
            'name'    => __('Hide Post Meta (date, category and author)', 'ct-bones'),
            'id'      => $this->filter_prefix . 'hide_post_meta',
            'type'    => 'switch',
            'style'   => 'rounded'
          ],
          [
            'name'    => __('Hide Social Share', 'ct-bones'),
            'id'      => $this->filter_prefix . 'hide_social_share',
            'type'    => 'switch',
            'style'   => 'rounded'
          ],
          [
            'name'    => __('Hide Featured Image', 'ct-bones'),
            'id'      => $this->filter_prefix . 'hide_featured_image',
            'type'    => 'switch',
            'style'   => 'rounded'
          ]
        )
    );

    $meta_boxes[] = [
      'title'          => __('Layout Settings', 'ct-bones'),
      'id'             => 'ct-theme-layout-settings',
      'settings_pages' => [$this->setting_id],
      'tab'            => 'layout',
      'fields'         => apply_filters(
        $this->filter_prefix . 'layouts_fields',
        $layout_fields
      ),
    ];

    return $meta_boxes;
  }

  public function register_header_settings_fields($meta_boxes)
  {
    $meta_boxes[] = [
      'title'          => __('Header', 'ct-bones'),
      'id'             => 'ct-theme-header-settings',
      'settings_pages' => [$this->setting_id],
      'tab'            => 'header',
      'fields'         => apply_filters(
        $this->filter_prefix . 'header_fields',
        array(
          [
            'type' => 'image_select',
            'name' => __('Header Layout', 'ct-bones'),
            'id'   => $this->prefix . 'header_layout',
            'class' => 'codetot-image-select',
            'std' => 'header-1',
            'options' => apply_filters(
              $this->filter_prefix . 'header_options',
              codetot_get_header_options()
            )
          ],
          [
            'type' => 'radio',
            'name' => __('Header Background Color', 'ct-bones'),
            'id' => $this->prefix . 'header_background_color',
            'std' => 'white',
            'options' => apply_filters(
              $this->filter_prefix . 'header_background_colors_options',
              codetot_header_background_colors()
            )
          ],
          [
            'type' => 'radio',
            'name' => __('Header Text Color Contract', 'ct-bones'),
            'id' => $this->prefix . 'header_color_contract',
            'std' => 'light',
            'options' => codetot_background_contracts()
          ],
          [
            'type' => 'select',
            'name' => sprintf(__('Enable %s', 'ct-bones'), esc_html__('Sticky Header', 'ct-bones')),
            'id'   => $this->prefix . 'header_enable_sticky',
            'std'  => 'jump-down',
            'options' => [
              'none' => __('None', 'ct-bones'),
              'jump-down' => __('Jump Down', 'ct-bones'),
              'visible-scroll-up' => __('Visible Scroll Up', 'ct-bones'),
            ],
          ],
          [
            'type' => 'switch',
            'name' => sprintf(__('Hide %s Icon', 'ct-bones'), esc_html__('Account', 'ct-bones')),
            'id' => $this->prefix . 'header_hide_account_icon'
          ],
          [
            'type' => 'switch',
            'name' => sprintf(__('Hide %s Icon', 'ct-bones'), esc_html__('Search', 'ct-bones')),
            'id' => $this->prefix . 'header_hide_search_icon'
          ],
          [
            'type' => 'switch',
            'name' => sprintf(__('Hide %s Icon', 'ct-bones'), esc_html__('Cart', 'ct-bones')),
            'id' => $this->prefix . 'header_hide_cart_icon'
          ],
          [
            'type' => 'switch',
            'name' => sprintf(__('Display %s', 'ct-bones'), esc_html__('Phone Number', 'ct-bones')),
            'id' => $this->prefix . 'header_display_phone',
            'desc' => sprintf(
              __('Display a "%1$s" from <a href="%2$s">%3$s</a> settings.', 'ct-bones'),
              esc_html__('Company Hotline', 'ct-bones'),
              admin_url() . 'admin.php?page=ct-data',
              esc_html__('CT Data', 'ct-bones')
            )
          ],
          [
            'type' => 'switch',
            'name' => sprintf(__('Display %s', 'ct-bones'), esc_html__('Home Icon in Primary Menu', 'ct-bones')),
            'id'   => $this->prefix . 'home_icon_menu'
          ],
          [
            'type' => 'switch',
            'name' => sprintf(__('Enable %s', 'ct-bones'), esc_html__('Topbar', 'ct-bones')),
            'id'   => $this->prefix . 'header_topbar_enable',
            'std' => 1
          ],
          [
            'type' => 'radio',
            'name' => sprintf(__('%s Layout', 'ct-bones'), esc_html__('Topbar', 'ct-bones')),
            'id'   => $this->prefix . 'topbar_layout',
            'options' => array(
              1 => sprintf(__('%s Column', 'ct-bones'), 1),
              2 => sprintf(__('%s Columns', 'ct-bones'), 2)
            )
          ]
        )
      )
    ];

    return $meta_boxes;
  }

  public function register_footer_settings_fields($meta_boxes)
  {
    $footer_columns_field = array(
      'name' => __('Footer Columns', 'ct-bones'),
      'id'   => $this->prefix . 'footer_columns',
      'class' => 'codetot-image-icon',
      'type' => 'image_select',
      'std'  => '3-columns',
      'columns' => 6,
      'options' => apply_filters('codetot_footer_columns_options', codetot_footer_widget_columns())
    );

    $fields = apply_filters('codetot_footer_fields', [
      apply_filters('codetot_footer_columns_fields', $footer_columns_field),
      [
        'type' => 'radio',
        'name' => __('Footer Background Color', 'ct-bones'),
        'id' => $this->prefix . 'footer_background_color',
        'columns' => 6,
        'options' => apply_filters(
          $this->filter_prefix . 'footer_background_colors_options',
          codetot_footer_background_colors()
        )
      ],
      [
        'name'    => __('Remove Theme Copyright', 'ct-bones'),
        'id'      => $this->filter_prefix . 'remove_theme_copyright',
        'type'    => 'switch',
        'style'   => 'rounded',
        'columns' => 2,
      ],
      [
        'name'    => __('Hide Footer Social Links', 'ct-bones'),
        'id'      => $this->filter_prefix . 'footer_hide_social_links',
        'columns' => 2,
        'type'    => 'switch'
      ]
    ]);

    $meta_boxes[] = [
      'title'          => __('Footer', 'ct-bones'),
      'id'             => 'ct-theme-footer-settings',
      'settings_pages' => [$this->setting_id],
      'tab'            => 'footer',
      'fields'         => $fields,
    ];

    return $meta_boxes;
  }
}

Codetot_CT_Theme_Settings::instance();
