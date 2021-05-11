<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_CT_Settings_WooCommerce_Settings
{
  /**
   * Singleton instance
   *
   * @var Codetot_CT_Settings_WooCommerce_Settings
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_CT_Settings_WooCommerce_Settings
   */
  public final static function instance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function __construct()
  {
    add_filter('codetot_settings_fields', array($this, 'register_woocommerce_fields'));
  }

  public function register_woocommerce_fields($fields) {
    $fields = array_merge($fields, array(
      array(
        'key' => 'field_609a36c9c8ae2',
        'label' => 'WooCommerce',
        'name' => '',
        'type' => 'tab',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'placement' => 'top',
        'endpoint' => 0,
      ),
      array(
        'key' => 'field_609a36f2c8ae4',
        'label' => 'Guarantee List',
        'name' => 'guarantee_list',
        'type' => 'repeater',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'collapsed' => '',
        'min' => 0,
        'max' => 4,
        'layout' => 'row',
        'button_label' => 'Add Item',
        'sub_fields' => array(
          array(
            'key' => 'field_609a3705c8ae5',
            'label' => 'Icon Image',
            'name' => 'image',
            'type' => 'image',
            'instructions' => 'Maximum 100px',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'return_format' => 'array',
            'preview_size' => 'full',
            'library' => 'all',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => 100,
            'max_height' => 100,
            'max_size' => '',
            'mime_types' => '',
          ),
          array(
            'key' => 'field_609a3722c8ae6',
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
          ),
          array(
            'key' => 'field_609a3726c8ae7',
            'label' => 'Description',
            'name' => 'description',
            'type' => 'textarea',
            'instructions' => '',
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
            'rows' => '',
            'new_lines' => '',
          ),
        )
      )
    ));

    return $fields;
  }
}

Codetot_CT_Settings_WooCommerce_Settings::instance();
