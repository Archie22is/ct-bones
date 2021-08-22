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
    add_action('customize_register', array($this, 'register_panel'));
    add_action('customize_register', array($this, 'register_color_schemas_settings'));
    add_action('customize_register', array($this, 'register_typography_settings'));
    add_action('customize_register', array($this, 'register_layout_settings'));
    add_action('customize_register', array($this, 'register_topbar_settings'));
    add_action('customize_register', array($this, 'register_header_settings'));
    add_action('customize_register', array($this, 'register_footer_settings'));
  }

  public function register_panel($wp_customize)
  {
    $wp_customize->add_panel(
      'codetot_theme_options',
      array(
        'priority' => 50,
        'title'    => __('[CT] Theme Options', 'ct-bones'),
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
      'codetot_theme_body_font' => __('Body Font Family', 'ct-bones'),
      'codetot_theme_heading_font' => __('Heading Font Family', 'ct-bones')
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
      '1067' => __('1.067 - Minor Second', 'ct-bones'),
      '1125' => __('1.125 - Major Second', 'ct-bones'),
      '1200' => __('1.200 - Minor Third', 'ct-bones'),
      '1250' => __('1.250 - Major Third', 'ct-bones'),
      '1333' => __('1.333  Perfect Fourth', 'ct-bones'),
      '1444' => __('1.444 - Augmented Fourth', 'ct-bones'),
      '1500' => __('1.500 - Perfect Fifth', 'ct-bones'),
      '1618' => __('1.618 - Golden Ratio', 'ct-bones')
    ));

    $this->register_control(array(
      'id' => 'codetot_theme_font_scale',
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
      'category' => __('Category', 'ct-bones'),
      'post' => __('Post', 'ct-bones'),
      'page' => __('Page', 'ct-bones')
    ));
    foreach ($layout_options as $layout_id => $layout_label) :
      $settings_id = 'codetot_' . $layout_id . '_layout';

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
      'id' => 'codetot_theme_container_width',
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
      'id' => 'codetot_theme_archive_post_layout',
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
      'header-1' => __('Header 1', 'ct-bones'),
      'header-2' => __('Header 2', 'ct-bones'),
      'header-3' => __('Header 3', 'ct-bones'),
      'header-4' => __('Header 4', 'ct-bones'),
      'header-5' => __('Header 5', 'ct-bones'),
      'header-6' => __('Header 6', 'ct-bones'),
      'header-theme' => __('Custom Theme Header', 'ct-bones')
    ));

    // Header layout
    $this->register_control(array(
      'id' => 'codetot_theme_header_layout',
      'label' => __('Header Layout', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'setting_args' => array('default' => 'header-1'),
      'control_args' => array(
        'type' => 'select',
        'choices' => $header_layout_options
      )
    ), $wp_customize);

    // Header Background Color
    $this->register_control(array(
      'id' => 'codetot_theme_header_background_color',
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
      'id' => 'codetot_theme_header_text_contract',
      'label' => esc_html__('Header Text Contract', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'setting_args' => array('default' => 'light'),
      'control_args' => array(
        'type' => 'select',
        'choices' => $this->get_background_text_contract_options()
      )
    ), $wp_customize);

    $sticky_header_options = apply_filters('codetot_theme_header_sticky_options', array(
      'none' => __('No Sticky Header', 'ct-bones'),
      'jump-down' => __('Jump Down', 'ct-bones'),
      'visible-scroll-up' => __('Visible when Scrolling up', 'ct-bones')
    ));

    $this->register_control(array(
      'id' => 'codetot_theme_header_sticky_type',
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
      'id' => 'codetot_theme_enable_topbar_widget',
      'label' => __('Enable Topbar', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'control_args' => array(
        'type' => 'checkbox'
      )
    ), $wp_customize);

    // Topbar Columns
    $this->register_control(array(
      'id' => 'codetot_theme_topbar_widget_column',
      'label' => __('Topbar Column', 'ct-bones'),
      'setting_args' => array('default' => 1),
      'section_settings_id' => $section_settings_id,
      'control_args' => array(
        'type' => 'select',
        'choices' => apply_filters('codetot_theme_topbar_column_options', array(
          1 => __('1 Column', 'ct-bones'),
          2 => __('2 Columns', 'ct-bones')
        ))
      )
    ), $wp_customize);

    // Topbar Background Color
    $this->register_control(array(
      'id' => 'codetot_theme_topbar_background_color',
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
      'id' => 'codetot_theme_topbar_text_contract',
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
      'id' => 'codetot_theme_hide_footer_copyright',
      'label' => __('Display Footer Copyright Text', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'control_args' => array(
        'type' => 'checkbox'
      )
    ), $wp_customize);

    // Customize Copyright text
    $this->register_control(array(
      'id' => 'codetot_theme_footer_copyright_text',
      'label' => esc_html__('Footer Copyright Text', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'setting_args' => array(
        'default' => sprintf(
          __('Copyright &copy; by %1$s. Build with %2$s (version %3$s).', 'ct-bones'),
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
      'id' => 'codetot_theme_hide_footer_widgets',
      'label' => __('Hide Footer Widgets', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'control_args' => array(
        'type' => 'checkbox'
      )
    ), $wp_customize);

    // Footer columns
    $this->register_control(array(
      'id' => 'codetot_theme_footer_widget_column',
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
      'id' => 'codetot_theme_footer_background_color',
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
      'id' => 'codetot_theme_footer_text_contract',
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
      1 => __('1 Column', 'ct-bones'),
      2 => __('2 Columns', 'ct-bones'),
      3 => __('3 Columns', 'ct-bones'),
      4 => __('4 Columns', 'ct-bones')
    );
  }

  public function get_background_color_options()
  {
    return array(
      'transparent' => __('Transparent (No Background Color)', 'ct-bones'),
      'primary'     => __('Primary', 'ct-bones'),
      'secondary'   => __('Secondary', 'ct-bones'),
      'white'       => __('White', 'ct-bones'),
      'dark'        => __('Dark', 'ct-bones'),
      'gray'        => __('Gray', 'ct-bones')
    );
  }

  public function get_background_text_contract_options()
  {
    return array(
      'light' => __('Light Background - Dark Text', 'ct-bones'),
      'dark' => __('Dark Background - White Text', 'ct-bones')
    );
  }

  public function register_section($args, $wp_customize)
  {
    $wp_customize->add_section($args['id'], array(
      'title' => $args['label'],
      'panel' => 'codetot_theme_options',
      'priority' => $args['priority']
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
      'settings' => $color['id']
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
      return new \WP_Error('400', __('Missing parameter.', 'ct-bones'));
    }

    $default_control_args = array(
      'label' => $args['label'],
      'section' => $args['section_settings_id']
    );

    $control_args = wp_parse_args($args['control_args'], $default_control_args);

    if (!empty($args['setting_args'])) :
      $wp_customize->add_setting(
        $args['id'],
        $args['setting_args']
      );
    else :
      $wp_customize->add_setting($args['id']);
    endif;

    $wp_customize->add_control(new WP_Customize_Control($wp_customize, $args['id'], $control_args));
  }
}

Codetot_Customizer_Settings::instance();
