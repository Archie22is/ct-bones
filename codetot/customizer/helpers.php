<?php

/**
 * Undocumented function
 *
 * @param string $field_id
 * @param string $type
 * @return void
 */
if ( !function_exists('codetot_get_theme_mod') ) :
  function codetot_get_theme_mod($field_id, $type = 'default') {
    $options = isset($type) && $type === 'pro' ? get_theme_mod('codetot_pro_settings') : get_theme_mod('codetot_theme_settings');

    if ( !empty($field_id) && isset($options[sanitize_key($field_id)]) ) {
      return $options[sanitize_key($field_id)];
    } else {
      return null;
    }
  }
endif;

if ( !function_exists('codetot_customizer_register_section') ) :
  function codetot_customizer_register_section($args, $wp_customize)
  {
    $wp_customize->add_section($args['id'], array(
      'title' => $args['label'],
      'panel' => !empty($args['panel']) ? $args['panel'] : 'codetot_theme_options',
      'priority' => !empty($args['priority']) ? $args['priority'] : 10
    ));
  }
endif;

if ( !function_exists('codetot_customizer_register_color_control') ) :
  function codetot_customizer_register_color_control($color, $section_settings_id, $wp_customize)
  {
    $settings_id = sprintf('codetot_theme_settings[%s]', $color['id']);

    $wp_customize->add_setting(
      $settings_id,
      array('default' => $color['std'])
    );

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $settings_id, array(
      'label'    => $color['name'],
      'section'  => $section_settings_id,
      'settings' => $settings_id,
      'sanitize_callback' => 'sanitize_hex_color'
    )));
  }
endif;

if ( !function_exists('codetot_customizer_register_control') ) :
  function codetot_customizer_register_control($args, $wp_customize)
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

    $option_type = !empty($args['option_type']) ? sanitize_key($args['option_type']) : 'codetot_theme_settings';
    $settings_id = sprintf('%s[%s]', $option_type, $args['id']);

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
endif;

if ( !function_exists('codetot_customizer_get_column_options') ) :
  function codetot_customizer_get_column_options() {
    return array(
      2 => esc_html__('2 Columns', 'ct-bones'),
      3 => esc_html__('3 Columns', 'ct-bones'),
      4 => esc_html__('4 Columns', 'ct-bones'),
      5 => esc_html__('5 Columns', 'ct-bones'),
      6 => esc_html__('6 Columns', 'ct-bones')
    );
  }
endif;

if ( !function_exists('codetot_customizer_get_sidebar_options') ) :
  function codetot_customizer_get_sidebar_options()
  {
    return array(
      'sidebar-left' => esc_html__('Left Sidebar', 'ct-bones'),
      'sidebar-right' => esc_html__('Right Sidebar', 'ct-bones'),
      'no-sidebar' => esc_html__('No Sidebar', 'ct-bones')
    );
  }
endif;
