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
    add_action('customize_register', array($this, 'init'), 1);
    add_action('customize_register', array($this, 'register_color_schemas_settings'));
    add_action('customize_register', array($this, 'register_typography_settings'));
    add_action('customize_register', array($this, 'register_layout_settings'));
    add_action('customize_register', array($this, 'register_header_settings'));
    add_action('customize_register', array($this, 'register_footer_settings'));
  }

  public function init($wp_customize)
  {
    $wp_customize->add_panel(
      'codetot_theme_options',
      array(
        'priority' => 100,
        'title'    => __('CT Theme - Theme Options', 'ct-bones'),
      )
    );

    return $wp_customize;
  }

  public function register_color_schemas_settings($wp_customize)
  {
    $wp_customize->add_section('codetot_theme_color_settings', array(
      'title' => esc_html__('Color Schemas', 'ct-bones'),
      'panel' => 'codetot_theme_options',
      'priority' => 10
    ));

    // Register color schemas
    $color_options = codetot_get_color_options();
    foreach ($color_options as $color) {
      $wp_customize->add_setting(
        $color['id'],
        array('default' => $color['std'])
      );

      $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $color['id'], array(
        'label'    => $color['name'],
        'section'  => 'codetot_theme_color_settings',
        'settings' => $color['id']
      )));
    }

    return $wp_customize;
  }

  public function register_typography_settings($wp_customize) {
    $wp_customize->add_section('codetot_theme_typography_settings', array(
      'title' => esc_html__('Typography', 'ct-bones'),
      'panel' => 'codetot_theme_options',
      'priority' => 20
    ));

    $font_family_options = codetot_get_font_family_options();
    $font_types = array(
      'body_font' => __('Body Font Family', 'ct-bones'),
      'heading_font' => __('Heading Font Family', 'ct-bones')
    );

    foreach ($font_types as $font_id => $font_type) {
      $wp_customize->add_setting(
        $font_id,
        array('default' => 'Averta')
      );

      $wp_customize->add_control(new WP_Customize_Control($wp_customize, $font_id, array(
        'label'    => $font_type,
        'section'  => 'codetot_theme_typography_settings',
        'settings' => $font_id,
        'type' => 'select',
        'choices' => $font_family_options
      )));
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

    $wp_customize->add_setting(
      'codetot_theme_font_scale',
      array('default' => '1125')
    );

    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'codetot_theme_font_scale', array(
      'label'    => esc_html__('Font Size Scale Size', 'ct-bones'),
      'section'  => 'codetot_theme_typography_settings',
      'settings' => 'codetot_theme_font_scale',
      'type' => 'select',
      'choices' => $font_sizes
    )));

    return $wp_customize;
  }

  public function register_layout_settings($wp_customize)
  {
    $section_settings_id = 'codetot_theme_layout_settings';
    $wp_customize->add_section($section_settings_id, array(
      'title' => esc_html__('Layout Section', 'ct-bones'),
      'panel' => 'codetot_theme_options',
      'priority' => 30
    ));

    $layout_options = apply_filters('codetot_theme_layout_options', array(
      'category' => __('Category', 'ct-bones'),
      'post' => __('Post', 'ct-bones'),
      'page' => __('Page', 'ct-bones')
    ));

    foreach ($layout_options as $layout_id => $layout_label) :
      $settings_id = $layout_id . '_layout';

      $wp_customize->add_setting(
        $settings_id,
        array('default' => 'sidebar-right')
      );

      $wp_customize->add_control(new WP_Customize_Control($wp_customize, $settings_id, array(
        'label'    => sprintf(__('%s Layout', 'ct-bones'), $layout_label),
        'section'  => $section_settings_id,
        'settings' => 'codetot_theme_' . $settings_id,
        'type'     => 'select',
        'choices'  => $this->get_sidebar_options()
      )));
    endforeach;

    $wp_customize->add_setting(
      'codetot_theme_container_width',
      array('default' => 1280)
    );

    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'codetot_theme_container_width', array(
      'label'    => esc_html__('Container Width', 'ct-bones') . ' (pixel)',
      'section'  => $section_settings_id,
      'settings' => 'codetot_theme_container_width',
      'type'     => 'number',
      'sanitize_callback' => 'absint',
      'input_attrs' => array(
        'min' => 900,
        'max' => 1400
      )
    )));

    return $wp_customize;
  }

  public function register_header_settings($wp_customize)
  {
    $section_settings_id = 'codetot_theme_header_settings';
    $wp_customize->add_section($section_settings_id, array(
      'title' => esc_html__('Header Section', 'ct-bones'),
      'panel' => 'codetot_theme_options',
      'priority' => 50
    ));

    return $wp_customize;
  }

  public function register_footer_settings($wp_customize)
  {
    $parent_theme = wp_get_theme()->parent();
    $theme_version = !empty($parent_theme) ? $parent_theme->Version : wp_get_theme()->Get('Version');
    $section_settings_id = 'codetot_theme_footer_settings';

    $wp_customize->add_section($section_settings_id, array(
      'title'    => esc_html__('Footer Section', 'ct-bones'),
      'panel'    => 'codetot_theme_options',
      'priority' => 100
    ));

    // Display Copyright text
    $wp_customize->add_setting('codetot_theme_hide_footer_copyright');
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'codetot_theme_hide_footer_copyright', array(
      'label' => __('Display Footer Copyright Text', 'ct-bones'),
      'section' => $section_settings_id,
      'type' => 'checkbox'
    )));

    // Customize Copyright text
    $wp_customize->add_setting('codetot_theme_footer_copyright_text', array(
      'default' => sprintf(
        __('Copyright &copy; by %1$s. Build with %2$s (version %3$s).', 'ct-bones'),
        get_bloginfo('name'),
        sprintf('<a href="%1$s" rel="sponsored" target="_blank">%2$s</a>', $parent_theme->Get('AuthorURI'), $parent_theme->Get('Author')),
        $theme_version
      ),
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'codetot_theme_footer_copyright_text', array(
      'label'   => esc_html__('Footer Copyright Text', 'ct-bones'),
      'section' => $section_settings_id,
      'type'    => 'textarea',
    )));

    // Hide footer widget
    $wp_customize->add_setting('codetot_theme_hide_footer_widgets');
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'codetot_theme_hide_footer_widgets', array(
      'label' => __('Hide Footer Widgets', 'ct-bones'),
      'section' => $section_settings_id,
      'type' => 'checkbox'
    )));

    // Footer columns
    $wp_customize->add_setting(
      'codetot_theme_footer_widget_column',
      array('default' => 3)
    );
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'codetot_theme_footer_widget_column', array(
      'label' => esc_html__('Footer Widget Column', 'ct-bones'),
      'section' => $section_settings_id,
      'type' => 'select',
      'choices' => $this->get_sidebar_column_options()
    )));

    // Footer Background Color
    $wp_customize->add_setting(
      'codetot_theme_footer_background_color',
      array('default' => 'transparent')
    );
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'codetot_theme_footer_background_color', array(
      'label' => esc_html__('Footer Background Color', 'ct-bones'),
      'section' => $section_settings_id,
      'type' => 'select',
      'choices' => $this->get_background_color_options()
    )));

    // Footer Text Contract
    $wp_customize->add_setting(
      'codetot_theme_footer_text_contract',
      array('default' => 'light')
    );
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'codetot_theme_footer_text_contract', array(
      'label' => esc_html__('Footer Text Contract', 'ct-bones'),
      'section' => $section_settings_id,
      'type' => 'select',
      'choices' => $this->get_background_text_contract_options()
    )));

    return $wp_customize;
  }

  public function get_sidebar_options() {
    return array(
      'sidebar-left' => esc_html__('Left Sidebar', 'ct-bones'),
      'sidebar-right' => esc_html__('Right Sidebar', 'ct-bones'),
      'no-sidebar' => esc_html__('No Sidebar', 'ct-bones')
    );
  }

  public function get_sidebar_column_options() {
    return array(
      1 => __('1 Column', 'ct-bones'),
      2 => __('2 Columns', 'ct-bones'),
      3 => __('3 Columns', 'ct-bones'),
      4 => __('4 Columns', 'ct-bones')
    );
  }

  public function get_background_color_options() {
    return array(
      'transparent' => __('Transparent (No Background Color)', 'ct-bones'),
      'primary'     => __('Primary', 'ct-bones'),
      'secondary'   => __('Secondary', 'ct-bones'),
      'white'       => __('White', 'ct-bones'),
      'dark'        => __('Dark', 'ct-bones'),
      'gray'        => __('Gray', 'ct-bones')
    );
  }

  public function get_background_text_contract_options() {
    return array(
      'light' => __('Light Background - Dark Text', 'ct-bones'),
      'dark' => __('Dark Background - White Text', 'ct-bones')
    );
  }
}

Codetot_Customizer_Settings::instance();
