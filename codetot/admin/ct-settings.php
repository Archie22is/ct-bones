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
      acf_add_options_page(array(
        'page_title' 	=> __('CT Settings', 'ct-bones'),
        'menu_title'	=> __('CT Settings', 'ct-bones'),
        'menu_slug' 	=> 'ct-settings',
        'capability'	=> 'edit_posts',
        'position'    => 120,
        'redirect'		=> false
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
    endif;
  }
}

Codetot_CT_Settings::instance();
