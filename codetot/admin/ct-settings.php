<?php

// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_CT_Settings
{
  /**
   * Singleton instance
   *
   * @var Codetot_CT_Settings
   */
  private static $instance;
  /**
   * Get singleton instance.
   *
   * @return Codetot_CT_Settings
   */
  final public static function instance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function __construct()
  {
    add_action('acf/init', array($this, 'register_page_options'));
    add_action('acf/init', array($this, 'register_group_fields'));
  }

  public function register_page_options() {
    if( function_exists('acf_add_options_page') ) {
      $parent = acf_add_options_page(array(
        'page_title' 	=> __('CT Settings', 'ct-bones'),
        'menu_title'	=> __('CT Settings', 'ct-bones'),
        'menu_slug' 	=> 'ct-settings',
        'capability'	=> 'edit_posts',
        'position'    => 120,
        'redirect'		=> false
      ));
      $child = acf_add_options_sub_page(array(
        'page_title'  => __('Insert Scripts', 'ct-bones'),
        'menu_title'  => __('Insert Scripts', 'ct-bones'),
        'parent_slug' => $parent['menu_slug']
    ));
    }
  }

  public function register_group_fields() {
    if( function_exists('acf_add_local_field_group') ) :
      $fields = apply_filters('codetot_settings_fields', []);

      acf_add_local_field_group(array(
        'key' => 'group_609a36b51f3fd',
        'title' => __('CT Settings', 'ct-bones'),
        'fields' => $fields,
        'location' => array(
          array(
            array(
              'param' => 'options_page',
              'operator' => '==',
              'value' => 'ct-settings',
            ),
          ),
        ),
        'menu_order' => 0,
        'position' => 'acf_after_title',
        'style' => 'seamless',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => ''
      ));

      acf_add_local_field_group(array(
        'key' => 'group_60efdfcf3ba71',
        'title' => 'Insert Scripts',
        'fields' => array(
          array(
            'key' => 'field_ctscriptsinheader',
            'label' => 'Scripts in Header',
            'name' => 'ct_scripts_in_header',
            'type' => 'textarea',
            'instructions' => 'These scripts will be printed in the head section.',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'maxlength' => '',
            'rows' => 12,
            'new_lines' => '',
          ),
          array(
            'key' => 'field_ctscriptsinbody',
            'label' => 'Scripts in Body',
            'name' => 'ct_scripts_in_body',
            'type' => 'textarea',
            'instructions' => 'These scripts will be printed just below the opening body tag.',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'maxlength' => '',
            'rows' => 12,
            'new_lines' => '',
          ),
          array(
            'key' => 'field_ctscriptsinfooter',
            'label' => 'Scripts in Footer',
            'name' => 'ct_scripts_in_footer',
            'type' => 'textarea',
            'instructions' => 'These scripts will be printed above the closing body tag.',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'maxlength' => '',
            'rows' => 12,
            'new_lines' => '',
          ),
        ),
        'location' => array(
          array(
            array(
              'param' => 'options_page',
              'operator' => '==',
              'value' => 'acf-options-insert-scripts',
            ),
          ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
      ));

    endif;
  }
}

Codetot_CT_Settings::instance();
