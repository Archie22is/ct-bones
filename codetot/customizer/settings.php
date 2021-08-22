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

    $font_sizes = apply_filters('codetot_font_sizes_options', array(
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
      'font_scale',
      array('default' => '1125')
    );

    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'font_scale', array(
      'label'    => esc_html__('Font Size Scale Size', 'ct-bones'),
      'section'  => 'codetot_theme_typography_settings',
      'settings' => 'font_scale',
      'type' => 'select',
      'choices' => $font_sizes
    )));

    return $wp_customize;
  }

  public function register_header_settings($wp_customize)
  {
    $wp_customize->add_section('codetot_theme_header_settings', array(
      'title' => esc_html__('Header Section', 'ct-bones'),
      'panel' => 'codetot_theme_options',
      'priority' => 30
    ));

    return $wp_customize;
  }

  public function register_footer_settings($wp_customize)
  {
    $parent_theme = wp_get_theme()->parent();

    $wp_customize->add_section('codetot_theme_footer_settings', array(
      'title'    => esc_html__('Footer Section', 'ct-bones'),
      'panel'    => 'codetot_theme_options',
      'priority' => 100
    ));

    $wp_customize->add_setting('codetot_theme_footer_copyright_text', array(
      'default' => sprintf(
        __('Copyright &copy; by %1$s. Build with %2$s (version %3$s).', 'ct-bones'),
        get_bloginfo('name'),
        sprintf('<a href="%1$s" rel="sponsored" target="_blank">%2$s</a>', $parent_theme->Get('AuthorURI'), $parent_theme->Get('Author')),
        $parent_theme->Version
      ),
    ));

    $wp_customize->add_control('codetot_theme_footer_copyright_text', array(
      'label'   => 'Footer Text Here',
      'section' => 'codetot_theme_footer_settings',
      'type'    => 'textarea',
    ));

    return $wp_customize;
  }
}

Codetot_Customizer_Settings::instance();
