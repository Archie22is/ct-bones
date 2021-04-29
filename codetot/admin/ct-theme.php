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
        $this->setting_id = 'ct-theme';
        $this->option_name = 'ct_theme';

        add_filter('mb_settings_pages', array($this, 'register_settings_pages'));

        add_filter('rwmb_meta_boxes', array($this, 'register_general_settings_fields'));
        add_filter('rwmb_meta_boxes', array($this, 'register_typography_settings_fields'));
        add_filter('rwmb_meta_boxes', array($this, 'register_font_family_settings_fields'));
        add_filter('rwmb_meta_boxes', array($this, 'register_layout_settings_fields'));
        add_filter('rwmb_meta_boxes', array($this, 'register_header_settings_fields'));
        add_filter('rwmb_meta_boxes', array($this, 'register_footer_settings_fields'));
        add_filter('rwmb_meta_boxes', array($this, 'register_addons_settings_fields'));
    }

    public function register_settings_pages($setting_pages)
    {
        $settings_pages[] = [
            'menu_title'    => __('CT Theme', 'ct-theme'),
            'id'            => $this->setting_id,
            'option_name'   => $this->option_name,
            'capability'    => 'level_10',
            'style'         => 'no-boxes',
            'columns'       => 1,
            'tabs'          => apply_filters('codetot_settings_tabs', array(
                'general'     => __('General', 'ct-theme'),
                'typography'  => __('Typography', 'ct-theme'),
                'layout'      => __('Layout', 'ct-theme'),
                'header'      => __('Header', ' ct-theme'),
                'footer'      => __('Footer', 'ct-theme'),
                'addons'      => __('Addons', 'ct-theme')
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
                    'name'    => __('Color Schema', 'ct-theme')
                ]
            ),
            apply_filters('codetot_color_fields', codetot_get_color_options())
        );

        $meta_boxes[] = [
            'title'          => __('General', 'ct-theme'),
            'id'             => 'ct-theme-general-settings',
            'settings_pages' => [$this->setting_id],
            'tab'            => 'general',
            'fields'         => apply_filters('codetot_general_fields', $fields),
        ];

        return $meta_boxes;
    }

    public function register_typography_settings_fields($meta_boxes)
    {
        $meta_boxes[] = [
            'title'          => __('Typography', 'ct-theme'),
            'id'             => 'ct-theme-typography-settings',
            'settings_pages' => [$this->setting_id],
            'tab'            => 'typography',
            'fields'         => [
                [
                    'name'   => __('Font Size Scale', 'ct-theme'),
                    'id'     => $this->prefix . 'font_size_scale',
                    'type'   => 'image_select',
                    'class' => 'codetot-text-select',
                    'std'    => 1200,
                    'inline' => false,
                    'options' => codetot_get_font_size_scale_options()
                ],
            ],
        ];

        return $meta_boxes;
    }

    public function register_font_family_settings_fields($meta_boxes)
    {
        $meta_boxes[] = [
            'title'          => __('Font Family', 'ct-theme'),
            'settings_pages' => [$this->setting_id],
            'tab'            => 'typography',
            'fields'         => [
                [
                    'type' => 'heading',
                    'name' => __('Font Family', 'ct-theme'),
                    'columns' => 12,
                ],
                [
                    'name'     => __('Global Font', 'ct-theme'),
                    'id'       => $this->prefix . 'font_family',
                    'type'     => 'select',
                    'options'  => apply_filters('codetot_font_family_options', codetot_get_font_family_options()),
                    'columns'  => 12,
                ],
                [
                    'name'     => __('Heading Font', 'ct-theme'),
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
        $default_layouts = apply_filters('codetot_layout_settings', ['Category', 'Post', 'Page']);
        $layout_fields = array_map(function ($layout_name) {
            return array(
                'name' => sprintf(__('%s Layout', 'ct-theme'), $layout_name),
                'id'   => $this->prefix . str_replace('-', '_', sanitize_title_with_dashes($layout_name)) . '_layout',
                'type' => 'image_select',
                'class' => 'codetot-image-icon',
                'columns' => apply_filters('codetot_layout_settings_column', 3),
                'options' => codetot_sidebar_layouts()
            );
        }, $default_layouts);

        $layout_fields = array_merge(
            array(
                array(
                    'type' => 'heading',
                    'name' => __('Sidebar Layout', 'ct-theme')
                )
            ),
            $layout_fields,
            array(
                [
                    'type'    => 'heading',
                    'name'    => __('Container Layout', 'ct-theme')
                ],
                [
                    'name'    => __('Container Width', 'codetot'),
                    'id'      => $this->prefix . 'container_width',
                    'type'    => 'number',
                    'desc'    => __('(pixel) Only work with boxed container layout.', 'ct-theme'),
                    'min'     => 900,
                    'max'     => 1440,
                    'std'     => 1280,
                    'columns' => 6,
                ],
                [
                    'name'    => __('Container Layout', 'codetot'),
                    'id'      => $this->prefix . 'container_layout',
                    'type'    => 'image_select',
                    'options' => codetot_container_layouts(),
                    'std'     => 'boxed',
                    'columns' => 6,
                    'inline'  => false
                ]
            ),
            array(
            [
                'type' => 'heading',
                'name' => __('Category Layout', 'ct-theme'),
            ],
            [
                'name'    => __('Archive/Category: Posts Per Row', 'ct-theme'),
                'id'      => $this->prefix . 'category_column_number',
                'type'    => 'select',
                'std'     => 3,
                'options' => [
                    1 => __('1', 'ct-theme'),
                    2 => __('2', 'ct-theme'),
                    3 => __('3', 'ct-theme'),
                    4 => __('4', 'ct-theme'),
                    5 => __('5', 'ct-theme'),
                ],
            ],
            [
                'name'    => __('Post Card Style', 'ct-theme'),
                'id'      => $this->prefix . 'post_card_style',
                'type'    => 'select',
                'std'     => 'style-1',
                'options' => [
                    'style-1' => __('Style 1', 'ct-theme'),
                    'style-2' => __('Style 2', 'ct-theme'),
                    'style-3' => __('Style 3', 'ct-theme'),
                    'style-4' => __('Style 4', 'ct-theme'),
                ],
            ])
        );

        $meta_boxes[] = [
            'title'          => __('Layout Settings', 'ct-theme'),
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
            'title'          => __('Header', 'ct-theme'),
            'id'             => 'ct-theme-header-settings',
            'settings_pages' => [$this->setting_id],
            'tab'            => 'header',
            'fields'         => apply_filters(
                $this->filter_prefix . 'header_fields',
                array(
                    [
                        'type' => 'image_select',
                        'name' => __('Header Layout', 'ct-theme'),
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
                        'name' => __('Header Background Color', 'ct-theme'),
                        'id' => $this->prefix . 'header_background_color',
                        'std' => 'white',
                        'options' => apply_filters(
                            $this->filter_prefix . 'header_background_colors_options',
                            codetot_header_background_colors()
                        )
                    ],
                    [
                        'type' => 'radio',
                        'name' => __('Header Text Color Contract', 'ct-theme'),
                        'id' => $this->prefix . 'header_color_contract',
                        'std' => 'light',
                        'options' => codetot_background_contracts()
                    ],
                    [
                        'type' => 'switch',
                        'name' => __('Enable Sticky Header', 'ct-theme'),
                        'id'   => $this->prefix . 'header_enable_sticky',
                        'std' => 1
                    ],
                    [
                        'type' => 'switch',
                        'name' => __('Hide Account Icon', 'ct-theme'),
                        'id' => $this->prefix . 'header_hide_account_icon'
                    ],
                    [
                        'type' => 'switch',
                        'name' => __('Hide Search Icon', 'ct-theme'),
                        'id' => $this->prefix . 'header_hide_search_icon'
                    ],
                    [
                        'type' => 'switch',
                        'name' => __('Hide Cart Icon', 'ct-theme'),
                        'id' => $this->prefix . 'header_hide_cart_icon'
                    ],
                    [
                        'type' => 'switch',
                        'name' => __('Display Phone Number', 'ct-theme'),
                        'id' => $this->prefix . 'header_display_phone',
                        'desc' => sprintf(
                            __('Display a "%1$s" from <a href="%2$s">%3$s</a> settings.', 'ct-theme'),
                            esc_html__('Company Hotline', 'ct-theme'),
                            admin_url() . 'admin.php?page=ct-data',
                            esc_html__('CT Data', 'ct-theme')
                        )
                    ],
                    [
                        'type' => 'switch',
                        'name' => __('Enable Header Topbar', 'ct-theme'),
                        'id'   => $this->prefix . 'header_topbar_enable',
                        'std' => 1
                    ]
                )
            )
        ];

        return $meta_boxes;
    }

    public function register_footer_settings_fields($meta_boxes)
    {
        $footer_columns_field = array(
            'name' => __('Footer Columns', 'ct-theme'),
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
                'name' => __('Footer Background Color', 'ct-theme'),
                'id' => $this->prefix . 'footer_background_color',
                'columns' => 6,
                'options' => apply_filters(
                    $this->filter_prefix . 'footer_background_colors_options',
                    codetot_footer_background_colors()
                )
            ],
            [
                'name'    => __('Remove Theme Copyright', 'ct-pro-toolkit'),
                'id'      => $this->filter_prefix . 'remove_theme_copyright',
                'type'    => 'switch',
                'style'   => 'rounded',
                'columns' => 2,
            ],
            [
                'name'    => __('Hide Footer Social Links', 'ct-pro-toolkit'),
                'id'      => $this->filter_prefix . 'footer_hide_social_links',
                'columns' => 2,
                'type'    => 'switch'
            ]
        ]);

        $meta_boxes[] = [
            'title'          => __('Footer', 'ct-theme'),
            'id'             => 'ct-theme-footer-settings',
            'settings_pages' => [$this->setting_id],
            'tab'            => 'footer',
            'fields'         => $fields,
        ];

        return $meta_boxes;
    }

    public function register_addons_settings_fields($meta_boxes)
    {
        $meta_boxes[] = array(
            'title'          => __('Addons', 'ct-theme'),
            'id'             => 'ct-theme-addons-settings',
            'settings_pages' => [$this->setting_id],
            'tab'            => 'addons',
            'fields' => [
                [
                    'type' => 'switch',
                    'name' => __('Enable Sticky Social Links', 'ct-theme'),
                    'id'   => $this->prefix . 'enable_sticky_social_links',
                    'desc' => esc_html_x('Display company social links in left window.', 'settings description', 'ct-theme'),
                    'std' => 0,
                    'columns' => 12
                ],
                [
                    'type' => 'switch',
                    'name' => __('Enable Back to Top button', 'ct-theme'),
                    'id'   => $this->prefix . 'enable_back_to_top',
                    'desc' => esc_html_x('Display a button scroll to header.', 'settings description', 'ct-theme'),
                    'std' => 1,
                    'columns' => 12
                ]
            ]
        );

        return $meta_boxes;
    }
}

Codetot_CT_Theme_Settings::instance();
