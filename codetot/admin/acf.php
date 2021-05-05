<?php

/**
 * Global settings applies to all options
 */

/**
 * Class Codetot_Acf
 */
class Codetot_Acf {
  /**
   * Singleton instance
   *
   * @var Codetot_Acf
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Acf
   */
  public final static function instance() {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function __construct()
  {
    // Button settings
    add_filter('acf/load_field/name=button_style', array($this, 'load_button_styles'));
    add_filter('acf/load_field/name=button_target', array($this, 'load_button_targets'));
    add_filter('acf/load_field/name=button_size', array($this, 'load_button_sizes'));

    // Contact Form Select Settings
    add_filter('acf/load_field/name=contact_form', array($this, 'load_contact_form_options'));
    add_filter('acf/load_field/name=select_form', array($this, 'load_contact_form_options'));

    // Global Text Alignment
    add_filter('acf/load_field/name=content_position', array($this, 'load_alignments'));
    add_filter('acf/load_field/name=header_alignment', array($this, 'load_alignments'));
    add_filter('acf/load_field/name=tabs_alignment', array($this, 'load_alignments'));
    add_filter('acf/load_field/name=form_alignment', array($this, 'load_alignments'));
    add_filter('acf/load_field/name=footer_alignment', array($this, 'load_alignments'));
    add_filter('acf/load_field/name=content_alignment', array($this, 'load_alignments'));
    add_filter('acf/load_field/name=cell_alignment', array($this, 'load_alignments'));

    // Columns
    add_filter('acf/load_field/name=columns_count', array($this, 'load_columns'));

    // Background Contract: Light/Dark
    add_filter('acf/load_field/name=background_contract', array($this, 'load_background_contract'));
    // Background Color
    add_filter('acf/load_field/name=background_type', array($this, 'load_background_types'));
    add_filter('acf/load_field/name=style_color', array($this, 'load_background_types'));
    add_filter('acf/load_field/name=background_type_item', array($this, 'load_background_types'));

    // Block Presets
    add_filter('acf/load_field/name=block_preset', array($this, 'load_block_presets'));
    add_filter('acf/load_field/name=block_spacing', array($this, 'load_block_spacing'));

    // Contact Section - Layout Settings
    add_filter('acf/load_field/name=contact_primary_layout', array($this, 'load_primary_layouts'));
    add_filter('acf/load_field/name=contact_secondary_layout', array($this, 'load_secondary_layouts'));

    // Image Type
    add_filter('acf/load_field/name=image_size', array($this, 'load_image_types'));
  }

  public function load_button_styles($field) {
    $field['choices'] = apply_filters('codetot_button_styles', array(
      'primary' => __('Primary', 'ct-theme'),
      'secondary' => __('Secondary', 'ct-theme'),
      'dark' => __('Dark', 'ct-theme'),
      'outline' => __('Outline', 'ct-theme'),
      'outline-white' => __('Outline (Dark Background)', 'ct-theme'),
      'link' => __('Link', 'ct-theme'),
      'link-white' => __('Link (Dark Background)', 'ct-theme')
    ));

    return $field;
  }

  public function load_button_targets($field) {
    $field['choices'] = array(
      '_self' => __('Same Window/Tab', 'ct-theme'),
      '_blank' => __('New Window/Tab', 'ct-theme')
    );

    return $field;
  }

  public function load_button_sizes($field) {
    $field['choices'] = apply_filters('codetot_button_sizes', array(
      'normal' => __('Normal', 'ct-theme'),
      'small' => __('Small', 'ct-theme'),
      'large' => __('Large', 'ct-theme')
    ));

    return $field;
  }

  public function load_contact_form_options($field) {
    if (is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
      $field['choices'] = array();

      $form_args = array(
        'post_type' => 'wpcf7_contact_form',
        'posts_per_page' => -1
      );

      $forms = get_posts($form_args);

      foreach($forms as $form) {
        $field['choices'][$form->ID] = $form->post_title;
      }

      $field['class'] = 'contact-form-7';
    }

    if ( class_exists( 'GFFormsModel' ) ) {
      $choices = [];

      foreach ( \GFFormsModel::get_forms() as $form ) {
        $choices[ $form->id ] = $form->title;
      }

      if (empty($choices)) {
        $choices[''] = __('No available forms.', 'ct-theme');
      }

      $field['choices'] = $choices;
      $field['class'] = 'gravity-forms';
    }

    return $field;
  }

  public function load_alignments($field) {
    $field['choices'] = array(
      'left' => __('Left', 'ct-theme'),
      'center' => __('Center', 'ct-theme'),
      'right' => __('Right', 'ct-theme')
    );

    return $field;
  }

  public function load_background_contract($field) {
    $field['choices'] = array(
      'light' => __('Light Background - Dark Text', 'ct-theme'),
      'dark' => __('Dark Background - White Text', 'ct-theme')
    );

    return $field;
  }

  public function load_background_types($field) {
    $field['choices'] = apply_filters('codetot_background_types', array(
      'white' => __('White', 'ct-theme'),
      'light' => __('Light', 'ct-theme'),
      'gray' => __('Gray', 'ct-theme'),
      'dark' => __('Dark', 'ct-theme'),
      'black' => __('Black', 'ct-theme'),
      'primary' => __('Primary', 'ct-theme'),
      'secondary' => __('Secondary', 'ct-theme')
    ));

    return $field;
  }

  public function load_block_presets($field) {
    $preset_number = 7; // = 6
    $options = array(
      '' => __('Default', 'ct-theme'),
      'theme' => __('Theme Preset', 'ct-theme')
    );
    for($i = 1; $i < $preset_number; $i++) {
      $options[$i] = sprintf(__('Preset %s', 'ct-theme'), $i);
    }

    $field['choices'] = apply_filters('codetot_block_presets', $options);

    return $field;
  }

  public function load_block_spacing($field) {
    $field['choices'] = apply_filters('codetot_block_spacing', array(
      '' => __('Default', 'ct-theme'),
      's' => __('Small', 'ct-theme'),
      'm' => __('Medium', 'ct-theme'),
      'l' => __('Large', 'ct-theme'),
      'fullscreen' => __('Fullscreen', 'ct-theme')
    ));

    return $field;
  }

  public function load_primary_layouts($field) {
    $field['choices'] = array(
      'default' => __('Left Map - Right Content', 'ct-theme'),
      'switch' => __('Right Map - Left Content', 'ct-theme'),
      'top' => __('Top Map - Bottom Content', 'ct-theme'),
      'bottom' => __('Top Content - Bottom Map', 'ct-theme')
    );

    return $field;
  }

  public function load_secondary_layouts($field) {
    $field['choices'] = array(
      'default' => __('Top Content - Bottom Form', 'ct-theme'),
      'switch' => __('Top Form - Bottom Content', 'ct-theme'),
      'left' => __('Left Content - Right Form', 'ct-theme'),
      'right' => __('Left Form - Right Content', 'ct-theme')
    );

    return $field;
  }

  public function load_image_types($field) {
    $field['choices'] = array(
      'default' => __('Default Image Size', 'ct-theme'),
      'cover'   => __('Cover Image', 'ct-theme'),
      'contain' => __('Contain Image', 'ct-theme')
    );

    $field['default_value'] = 'default';

    return $field;
  }

  public function load_columns($field) {
    $field['choices'] = array(
      '' => __('Default', 'ct-theme'),
      'auto' => __('Auto', 'ct-theme')
    );

    $available_columns = range(2, 6, 1);
    foreach($available_columns as $column) {
      $field['choices'][$column] = sprintf(__('%s Columns', 'ct-theme'), $column);
    }

    return $field;
  }
}

Codetot_Acf::instance();
