<?php
if (!defined('ABSPATH')) exit;

class Codetot_Customizer_Settings
{
  /**
   * Singleton instance
   *
   * @var Codetot_Customizer_Settings
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Customizer_Settings
   */
  public final static function instance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Class constructor
   */
  private function __construct()
  {
    add_action('customize_register', array($this, 'register_panels'));

    // Global Theme Options
    add_action('customize_register', array($this, 'register_color_schemas_settings'));
    add_action('customize_register', array($this, 'register_typography_settings'));
    add_action('customize_register', array($this, 'register_layout_settings'));
    add_action('customize_register', array($this, 'register_topbar_settings'));
    add_action('customize_register', array($this, 'register_header_settings'));
    add_action('customize_register', array($this, 'register_footer_settings'));
    add_action('customize_register', array($this, 'register_single_post_settings'));

    // PRO Options
    add_action('customize_register', array($this, 'register_pro_addon_settings'));
    add_action('customize_register', array($this, 'register_pro_widget_settings'));
    add_action('customize_register', array($this, 'register_pro_seo_settings'));
  }

  public function register_panels($wp_customize)
  {
    $wp_customize->add_panel(
      'codetot_theme_options',
      array(
        'priority' => 50,
        'title'    => esc_html__('[CT] Theme Options', 'ct-bones'),
      )
    );

    $wp_customize->add_panel(
      'codetot_pro_options',
      array(
        'priority' => 60,
        'title' => esc_html__('[CT] Pro Settings', 'ct-bones')
      )
    );

    return $wp_customize;
  }

  public function register_color_schemas_settings($wp_customize)
  {
    $section_settings_id = 'codetot_theme_color_settings';

    $this->register_section(array(
      'id' => $section_settings_id,
      'label' => esc_html__('Color Schemas', 'ct-bones'),
      'priority' => 10
    ), $wp_customize);

    // Register color schemas
    $color_options = codetot_get_color_options();
    foreach ($color_options as $color) {
      $color['id'] = str_replace('codetot_', '', $color['id']);

      $this->register_color_control($color, $section_settings_id, $wp_customize);
    }

    return $wp_customize;
  }

  public function register_typography_settings($wp_customize)
  {
    $section_settings_id = 'codetot_theme_typography_settings';

    $this->register_section(array(
      'id' => $section_settings_id,
      'label' => esc_html__('Typography', 'ct-bones'),
      'priority' => 20
    ), $wp_customize);

    $font_family_options = codetot_get_font_family_options();
    $font_types = array(
      'body_font' => esc_html__('Body Font Family', 'ct-bones'),
      'heading_font' => esc_html__('Heading Font Family', 'ct-bones')
    );

    foreach ($font_types as $font_id => $font_type) {
      $this->register_control(array(
        'id' => $font_id,
        'label' => $font_type,
        'setting_args' => array('default' => 'Averta'),
        'section_settings_id' => $section_settings_id,
        'control_args' => array(
          'type' => 'select',
          'choices' => $font_family_options
        )

      ), $wp_customize);
    }

    $font_sizes = apply_filters('codetot_theme_font_size_scale_options', array(
      '1067' => esc_html__('1.067 - Minor Second', 'ct-bones'),
      '1125' => esc_html__('1.125 - Major Second', 'ct-bones'),
      '1200' => esc_html__('1.200 - Minor Third', 'ct-bones'),
      '1250' => esc_html__('1.250 - Major Third', 'ct-bones'),
      '1333' => esc_html__('1.333  Perfect Fourth', 'ct-bones'),
      '1444' => esc_html__('1.444 - Augmented Fourth', 'ct-bones'),
      '1500' => esc_html__('1.500 - Perfect Fifth', 'ct-bones'),
      '1618' => esc_html__('1.618 - Golden Ratio', 'ct-bones')
    ));

    $this->register_control(array(
      'id' => 'font_scale',
      'label' => esc_html__('Font Size Scale Size', 'ct-bones'),
      'setting_args' => array('default' => '1125'),
      'section_settings_id' => $section_settings_id,
      'control_args' => array(
        'type' => 'select',
        'choices' => $font_sizes
      )
    ), $wp_customize);

    return $wp_customize;
  }

  public function register_layout_settings($wp_customize)
  {
    $section_settings_id = 'codetot_theme_layout_settings';

    $this->register_section(array(
      'id' => $section_settings_id,
      'label' => esc_html__('Global Layout', 'ct-bones'),
      'priority' => 30
    ), $wp_customize);

    $layout_options = apply_filters('codetot_theme_layout_options', array(
      'category' => esc_html__('Category', 'ct-bones'),
      'post' => esc_html__('Post', 'ct-bones'),
      'page' => esc_html__('Page', 'ct-bones')
    ));
    foreach ($layout_options as $layout_id => $layout_label) :
      $settings_id = $layout_id . '_layout';

      $this->register_control(array(
        'id' => $settings_id,
        'label' => sprintf(__('%s Layout', 'ct-bones'), $layout_label),
        'setting_args' => array('default' => 'sidebar-right'),
        'section_settings_id' => $section_settings_id,
        'control_args' => array(
          'type'     => 'select',
          'choices'  => $this->get_sidebar_options()
        )
      ), $wp_customize);
    endforeach;

    // Global Container width
    $this->register_control(array(
      'id' => 'container_width',
      'label' => esc_html__('Container Width', 'ct-bones') . ' (pixel)',
      'setting_args' => array('default' => '1280'),
      'section_settings_id' => $section_settings_id,
      'control_args' => array(
        'type'     => 'number',
        'sanitize_callback' => 'absint',
        'input_attrs' => array(
          'min' => 900,
          'max' => 1400
        )
      )
    ), $wp_customize);

    // Archive Layout
    $this->register_control(array(
      'id' => 'archive_post_layout',
      'label' => esc_html__('Archive Post Layout', 'ct-bones'),
      'setting_args' => array('default' => 'list'),
      'section_settings_id' => $section_settings_id,
      'control_args' => array(
        'type' => 'select',
        'choices' => array(
          'list' => esc_html__('Post List', 'ct-bones'),
          'grid' => esc_html__('Post Grid', 'ct-bones')
        )
      )
    ), $wp_customize);

    return $wp_customize;
  }

  public function register_header_settings($wp_customize)
  {
    $section_settings_id = 'codetot_theme_header_settings';

    $this->register_section(array(
      'id' => $section_settings_id,
      'label' => esc_html__('Header', 'ct-bones'),
      'priority' => 50
    ), $wp_customize);

    $header_layout_options = apply_filters('codetot_theme_header_layout_options', array(
      'header-1' => esc_html__('Header 1', 'ct-bones'),
      'header-2' => esc_html__('Header 2', 'ct-bones'),
      'header-3' => esc_html__('Header 3', 'ct-bones'),
      'header-4' => esc_html__('Header 4', 'ct-bones'),
      'header-5' => esc_html__('Header 5', 'ct-bones'),
      'header-6' => esc_html__('Header 6', 'ct-bones'),
      'header-theme' => esc_html__('Custom Theme Header', 'ct-bones')
    ));

    // Header layout
    $this->register_control(array(
      'id' => 'header_layout',
      'label' => esc_html__('Header Layout', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'setting_args' => array('default' => 'header-1'),
      'control_args' => array(
        'type' => 'select',
        'choices' => $header_layout_options
      )
    ), $wp_customize);

    // Header Background Color
    $this->register_control(array(
      'id' => 'header_background_color',
      'label' => esc_html__('Header Background Color', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'setting_args' => array('default' => 'transparent'),
      'control_args' => array(
        'type' => 'select',
        'choices' => $this->get_background_color_options()
      )
    ), $wp_customize);

    // Header Text Contract
    $this->register_control(array(
      'id' => 'header_text_contract',
      'label' => esc_html__('Header Text Contract', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'setting_args' => array('default' => 'light'),
      'control_args' => array(
        'type' => 'select',
        'choices' => $this->get_background_text_contract_options()
      )
    ), $wp_customize);

    $sticky_header_options = apply_filters('codetot_theme_header_sticky_options', array(
      'none' => esc_html__('No Sticky Header', 'ct-bones'),
      'jump-down' => esc_html__('Jump Down', 'ct-bones'),
      'visible-scroll-up' => esc_html__('Visible when Scrolling up', 'ct-bones')
    ));

    $this->register_control(array(
      'id' => 'header_sticky_type',
      'label' => esc_html__('Sticky Header Type', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'setting_args' => array('default' => 'none'),
      'control_args' => array(
        'type' => 'select',
        'choices' => $sticky_header_options
      )
    ), $wp_customize);

    return $wp_customize;
  }

  public function register_topbar_settings($wp_customize)
  {
    $section_settings_id = 'codetot_theme_topbar_settings';

    $this->register_section(array(
      'id' => $section_settings_id,
      'label' => esc_html__('Topbar', 'ct-bones'),
      'priority' => 45
    ), $wp_customize);

    // Enable Topbar
    $this->register_control(array(
      'id' => 'enable_topbar_widget',
      'label' => esc_html__('Enable Topbar', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'control_args' => array(
        'type' => 'checkbox'
      )
    ), $wp_customize);

    // Topbar Columns
    $this->register_control(array(
      'id' => 'topbar_widget_column',
      'label' => esc_html__('Topbar Column', 'ct-bones'),
      'setting_args' => array('default' => 1),
      'section_settings_id' => $section_settings_id,
      'control_args' => array(
        'type' => 'select',
        'choices' => apply_filters('codetot_theme_topbar_column_options', array(
          1 => esc_html__('1 Column', 'ct-bones'),
          2 => esc_html__('2 Columns', 'ct-bones')
        ))
      )
    ), $wp_customize);

    // Topbar Background Color
    $this->register_control(array(
      'id' => 'topbar_background_color',
      'label' => esc_html__('Topbar Background Color', 'ct-bones'),
      'setting_args' => array('default' => 'transparent'),
      'section_settings_id' => $section_settings_id,
      'control_args' => array(
        'type' => 'select',
        'choices' => $this->get_background_color_options()
      )
    ), $wp_customize);

    // Topbar Text Contract
    $this->register_control(array(
      'id' => 'topbar_text_contract',
      'label' => esc_html__('Topbar Text Contract', 'ct-bones'),
      'setting_args' => array('default' => 'light'),
      'section_settings_id' => $section_settings_id,
      'control_args' => array(
        'type' => 'select',
        'choices' => $this->get_background_text_contract_options()
      )
    ), $wp_customize);

    return $wp_customize;
  }

  public function register_footer_settings($wp_customize)
  {
    $parent_theme = wp_get_theme()->parent();
    $theme_version = !empty($parent_theme) ? $parent_theme->Version : wp_get_theme()->Get('Version');
    $section_settings_id = 'codetot_theme_footer_settings';

    $this->register_section(array(
      'id' => $section_settings_id,
      'label' => esc_html__('Footer', 'ct-bones'),
      'priority' => 100
    ), $wp_customize);

    // Display Copyright text
    $this->register_control(array(
      'id' => 'hide_footer_copyright',
      'label' => esc_html__('Hide Footer Copyright Text', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'control_args' => array(
        'type' => 'checkbox'
      )
    ), $wp_customize);

    // Customize Copyright text
    $this->register_control(array(
      'id' => 'footer_copyright_text',
      'label' => esc_html__('Footer Copyright Text', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'setting_args' => array(
        'default' => sprintf(
          esc_html__('Copyright &copy; by %1$s. Build with %2$s (version %3$s).', 'ct-bones'),
          get_bloginfo('name'),
          sprintf('<a href="%1$s" rel="sponsored" target="_blank">%2$s</a>', $parent_theme->Get('AuthorURI'), $parent_theme->Get('Author')),
          $theme_version
        )
      ),
      'control_args' => array(
        'type' => 'textarea'
      )
    ), $wp_customize);

    // Hide footer widget
    $this->register_control(array(
      'id' => 'hide_footer_widgets',
      'label' => esc_html__('Hide Footer Widgets', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'control_args' => array(
        'type' => 'checkbox'
      )
    ), $wp_customize);

    // Footer columns
    $this->register_control(array(
      'id' => 'footer_widget_column',
      'label' => esc_html__('Footer Widget Column', 'ct-bones'),
      'setting_args' => array('default' => 3),
      'section_settings_id' => $section_settings_id,
      'control_args' => array(
        'type' => 'select',
        'choices' => $this->get_sidebar_column_options()
      )
    ), $wp_customize);

    // Footer Background Color
    $this->register_control(array(
      'id' => 'footer_background_color',
      'label' => esc_html__('Footer Background Color', 'ct-bones'),
      'setting_args' => array('default' => 'transparent'),
      'section_settings_id' => $section_settings_id,
      'control_args' => array(
        'type' => 'select',
        'choices' => $this->get_background_color_options()
      )
    ), $wp_customize);

    // Footer Text Contract
    $this->register_control(array(
      'id' => 'footer_text_contract',
      'label' => esc_html__('Footer Text Contract', 'ct-bones'),
      'setting_args' => array('default' => 'light'),
      'section_settings_id' => $section_settings_id,
      'control_args' => array(
        'type' => 'select',
        'choices' => $this->get_background_text_contract_options()
      )
    ), $wp_customize);

    return $wp_customize;
  }

  public function register_single_post_settings($wp_customize) {
    $section_settings_id = 'codetot_theme_single_post_settings';

    $this->register_section(array(
      'id' => $section_settings_id,
      'label' => esc_html__('Single Post', 'ct-bones')
    ), $wp_customize);

    $hide_options = array(
      'codetot_theme_hide_post_meta' => esc_html__('Hide post meta', 'ct-bones'),
      'codetot_theme_hide_social_share' => esc_html__('Hide social share', 'ct-bones'),
      'codetot_theme_hide_comments' => esc_html__('Hide comments', 'ct-bones'),
      'codetot_theme_hide_featured_image' => esc_html__('Hide featured image', 'ct-bones'),
      'codetot_theme_hide_related_posts' => esc_html__('Hide related posts', 'ct-bones')
    );

    foreach ($hide_options as $settings_id => $label) {
      $this->register_control(array(
        'id' => $settings_id,
        'label' => $label,
        'section_settings_id' => $section_settings_id,
        'control_args' => array(
          'type' => 'checkbox'
        )
      ), $wp_customize);
    }

    $this->register_control(array(
      'id' => 'related_posts_number',
      'label' => esc_html__('Related Posts Number', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'setting_args' => array('default' => 3),
      'control_args' => array(
        'type'     => 'number',
        'sanitize_callback' => 'absint',
        'input_attrs' => array(
          'min' => 2,
          'max' => 5
        )
      )
    ), $wp_customize);

    return $wp_customize;
  }

  public function register_pro_addon_settings($wp_customize) {
    $section_settings_id = 'codetot_pro_addon_settings';

    $this->register_section(array(
      'id' => $section_settings_id,
      'label' => esc_html__('Addons Settings', 'ct-bones'),
      'panel' => 'codetot_pro_options'
    ), $wp_customize);

    $this->register_control(array(
      'id' => 'codetot_pro_enable_megamenu',
      'label' => esc_html__('Enable Megamenu', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'is_pro' => true,
      'control_args' => array(
        'type' => 'checkbox'
      )
    ), $wp_customize);

    $this->register_control(array(
      'id' => 'codetot_pro_enable_back_to_top',
      'label' => esc_html__('Enable Back to top Button', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'is_pro' => true,
      'control_args' => array(
        'type' => 'checkbox'
      )
    ), $wp_customize);

    return $wp_customize;
  }

  public function register_pro_widget_settings($wp_customize) {
    $section_settings_id = 'codetot_pro_widgets_settings';

    $this->register_section(array(
      'id' => $section_settings_id,
      'label' => esc_html__('Extra Widgets Settings', 'ct-bones'),
      'panel' => 'codetot_pro_options'
    ), $wp_customize);

    $available_widgets = apply_filters('codetot_pro_widget_options', array(
      'company_info' => esc_html__('Company Info', 'ct-bones'),
      'related_posts' => esc_html__('Related Posts', 'ct-bones'),
      'latest_posts' => esc_html__('Latest Posts', 'ct-bones'),
      'icon_box' => esc_html__('Icon Box', 'ct-bones'),
      'social_links' => esc_html__('Social Links', 'ct-bones')
    ));

    foreach ($available_widgets as $widget_id => $label) {
      $this->register_control(array(
        'id' => 'codetot_pro_widget_' . esc_html($widget_id),
        'label' => sprintf(__('Enable widget: [CT] %s', 'ct-bones'), $label),
        'section_settings_id' => $section_settings_id,
        'is_pro' => true,
        'control_args' => array(
          'type' => 'checkbox'
        )
      ), $wp_customize);
    }
  }

  public function register_pro_seo_settings($wp_customize) {
    $section_settings_id = 'codetot_pro_seo_settings';

    $this->register_section(array(
      'id' => $section_settings_id,
      'label' => esc_html__('SEO Settings', 'ct-bones'),
      'panel' => 'codetot_pro_options'
    ), $wp_customize);

    $this->register_control(array(
      'id' => 'codetot_pro_seo_h1_homepage',
      'label' => esc_html__('Homepage H1 Heading Text', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'section_settings' => array('default' => 'none'),
      'is_pro' => true,
      'control_args' => array(
        'type' => 'checkbox',
        'choices' => apply_filters('codetot_theme_seo_h1_homepage_options', array(
          'none' => __('None', 'ct-bones'),
          'page_title' => __('Using Homepage Title', 'ct-bones'),
          'logo' => __('Using Logo Title', 'ct-bones')
        ))
      )
    ), $wp_customize);

    return $wp_customize;
  }

  public function get_sidebar_options()
  {
    return array(
      'sidebar-left' => esc_html__('Left Sidebar', 'ct-bones'),
      'sidebar-right' => esc_html__('Right Sidebar', 'ct-bones'),
      'no-sidebar' => esc_html__('No Sidebar', 'ct-bones')
    );
  }

  public function get_sidebar_column_options()
  {
    return array(
      1 => esc_html__('1 Column', 'ct-bones'),
      2 => esc_html__('2 Columns', 'ct-bones'),
      3 => esc_html__('3 Columns', 'ct-bones'),
      4 => esc_html__('4 Columns', 'ct-bones')
    );
  }

  public function get_background_color_options()
  {
    return array(
      'transparent' => esc_html__('Transparent (No Background Color)', 'ct-bones'),
      'primary'     => esc_html__('Primary', 'ct-bones'),
      'secondary'   => esc_html__('Secondary', 'ct-bones'),
      'white'       => esc_html__('White', 'ct-bones'),
      'dark'        => esc_html__('Dark', 'ct-bones'),
      'gray'        => esc_html__('Gray', 'ct-bones')
    );
  }

  public function get_background_text_contract_options()
  {
    return array(
      'light' => esc_html__('Light Background - Dark Text', 'ct-bones'),
      'dark' => esc_html__('Dark Background - White Text', 'ct-bones')
    );
  }

  public function register_section($args, $wp_customize)
  {
    $wp_customize->add_section($args['id'], array(
      'title' => $args['label'],
      'panel' => !empty($args['panel']) ? $args['panel'] : 'codetot_theme_options',
      'priority' => !empty($args['priority']) ? $args['priority'] : 10
    ));
  }

  public function register_color_control($color, $section_settings_id, $wp_customize)
  {
    $wp_customize->add_setting(
      $color['id'],
      array('default' => $color['std'])
    );

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $color['id'], array(
      'label'    => $color['name'],
      'section'  => $section_settings_id,
      'settings' => $color['id'],
      'sanitize_callback' => 'sanitize_hex_color'
    )));
  }

  /**
   * Undocumented function
   *
   * @param array $args
   * @param object $wp_customize
   * @return void
   */
  public function register_control($args, $wp_customize)
  {
    if (
      empty($args) ||
      empty($args['id']) ||
      empty($args['label']) ||
      empty($args['section_settings_id']) ||
      empty($wp_customize)
    ) {
      return new \WP_Error('400', esc_html__('Missing parameter.', 'ct-bones'));
    }

    $settings_id = (isset($args['is_pro']) && $args['is_pro']) ? sprintf('codetot_pro_settings[%s]', $args['id']) : sprintf('codetot_theme_settings[%s]', $args['id']);

    $default_control_args = array(
      'label' => $args['label'],
      'section' => $args['section_settings_id'],
      'settings' => $settings_id
    );

    $control_args = wp_parse_args($args['control_args'], $default_control_args);

    if (!empty($args['setting_args'])) :
      $wp_customize->add_setting(
        $settings_id,
        $args['setting_args']
      );
    else :
      $wp_customize->add_setting($settings_id);
    endif;

    $wp_customize->add_control(new WP_Customize_Control($wp_customize, $args['id'], $control_args));
  }
}

Codetot_Customizer_Settings::instance();
