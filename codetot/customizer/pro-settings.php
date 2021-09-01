<?php
if (!defined('ABSPATH')) exit;

class Codetot_Customizer_Pro_Settings
{
  /**
   * Singleton instance
   *
   * @var Codetot_Customizer_Pro_Settings
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Customizer_Pro_Settings
   */
  final public static function instance()
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
    $this->panel_id = 'codetot_pro_options';
    $this->settings_id = 'codetot_pro_settings';

    add_action('customize_register', array($this, 'register_panel'));
    add_action('customize_register', array($this, 'register_pro_addon_settings'));
    add_action('customize_register', array($this, 'register_pro_widget_settings'));
    add_action('customize_register', array($this, 'register_pro_layout_settings'));
    add_action('customize_register', array($this, 'register_pro_seo_settings'));
  }

  public function register_panel($wp_customize) {
    $wp_customize->add_panel(
      $this->panel_id,
      array(
        'priority' => 60,
        'title' => esc_html__('[CT] Pro Settings', 'ct-bones')
      )
    );

    return $wp_customize;
  }

  public function register_pro_addon_settings($wp_customize) {
    $section_settings_id = 'codetot_pro_addon_settings';

    $this->register_section(array(
      'id' => $section_settings_id,
      'label' => esc_html__('Addons Settings', 'ct-bones'),
      'priority' => 10
    ), $wp_customize);

    $this->register_control(array(
      'id' => 'enable_mega_menu',
      'label' => esc_html__('Enable Megamenu', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'control_args' => array(
        'type' => 'checkbox'
      )
    ), $wp_customize);

    $this->register_control(array(
      'id' => 'enable_back_to_top',
      'label' => esc_html__('Enable Back to top Button', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'section_settings' => array('default' => 1),
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
      'priority' => 20,
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
        'control_args' => array(
          'type' => 'checkbox'
        )
      ), $wp_customize);
    }
  }

  public function register_pro_layout_settings($wp_customize) {
    $section_settings_id = 'codetot_pro_layout_settings';

    $this->register_section(array(
      'id' => $section_settings_id,
      'label' => esc_html__('Extra Layout Settings', 'ct-bones'),
      'priority' => 30,
    ), $wp_customize);

    $this->register_control(array(
      'id' => 'extra_single_post_layout',
      'label' => esc_html__('Extra Post Layout', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'setting_args' => array('default' => 'none'),
      'control_args' => array(
        'type' => 'select',
        'choices' => apply_filters('codetot_extra_single_post_options', array(
          'none' => esc_html__('Default Layout', 'ct-bones'),
          'hero_image' => esc_html__('Hero Image', 'ct-bones')
        ))
      )
    ), $wp_customize);

    return $wp_customize;
  }

  public function register_pro_seo_settings($wp_customize) {
    $section_settings_id = 'codetot_pro_seo_settings';

    $this->register_section(array(
      'id' => $section_settings_id,
      'label' => esc_html__('SEO Settings', 'ct-bones'),
      'priority' => 40
    ), $wp_customize);

    $this->register_control(array(
      'id' => 'seo_h1_homepage',
      'label' => esc_html__('Homepage H1 Heading Text', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'section_settings' => array('default' => 'none'),
      'control_args' => array(
        'type' => 'select',
        'choices' => apply_filters('codetot_theme_seo_h1_homepage_options', array(
          'none' => __('None', 'ct-bones'),
          'page_title' => __('Using Homepage Title', 'ct-bones'),
          'logo' => __('Using Logo Title', 'ct-bones')
        ))
      )
    ), $wp_customize);

    return $wp_customize;
  }

  public function register_section($args, $wp_customize) {
    codetot_customizer_register_section(array(
      'id' => $args['id'],
      'label' => $args['label'],
      'panel' => $this->panel_id,
      'priority' => $args['priority']
    ), $wp_customize);

    return $wp_customize;
  }

  public function register_control($args, $wp_customize) {
    $final_args = wp_parse_args(array(
      'option_type' => $this->settings_id
    ), $args);

    codetot_customizer_register_control($final_args, $wp_customize);

    return $wp_customize;
  }
}

Codetot_Customizer_Pro_Settings::instance();
