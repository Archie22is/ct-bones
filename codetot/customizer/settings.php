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
    add_action('customize_register', array($this, 'register_global_settings'));
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

  public function register_global_settings($wp_customize)
  {
    $wp_customize->add_section('codetot_theme_global_settings', array(
      'title' => esc_html__('Global', 'ct-bones'),
      'panel' => 'codetot_theme_options',
      'priority' => 10
    ));

    // Register color schemas
    $color_options = codetot_get_color_options();
    foreach ($color_options as $color) {
      $wp_customize->add_setting(
        $color['id'],
        array(
          'default'        => $color['std'],
        )
      );

      $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $color['id'], array(
        'label'    => $color['name'],
        'section'  => 'codetot_theme_global_settings',
        'settings' => $color['id']
      )));
    }

    return $wp_customize;
  }

  public function register_header_settings($wp_customize)
  {
    $wp_customize->add_section('codetot_theme_footer_settings', array(
      'title' => esc_html__('Header Section', 'ct-bones'),
      'panel' => 'codetot_theme_options',
      'priority' => 20
    ));

    return $wp_customize;
  }

  public function register_footer_settings($wp_customize)
  {
    $parent_theme = wp_get_theme()->parent();

    $wp_customize->add_section('codetot_theme_footer_settings', array(
      'title'    => esc_html__('Footer Section', 'ct-bones'),
      'panel'    => 'codetot_theme_options',
      'priority' => 30
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
