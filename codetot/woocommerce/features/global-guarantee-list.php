<?php

// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_WooCommerce_Global_Guarantee_List
{
  /**
   * Singleton instance
   *
   * @var Codetot_WooCommerce_Global_Guarantee_List
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_WooCommerce_Global_Guarantee_List
   */
  public final static function instance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  private function __construct()
  {
    $this->position = get_global_option('codetot_woocommerce_enable_global_guarantee_list') ?? 'no';
    $this->enable = $this->position !== 'no';

    if ($this->enable) {
      $this->init();
    }
  }

  public function init() {
    add_filter('codetot_woocommerce_settings_fields', array($this, 'register_fields'));

    if ($this->position === 'footer') {
      $this->display_at_footer();
    }
  }

  public function register_fields($fields) {
    return array_merge($fields, array(
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
  }

  public function display_at_footer() {
    add_action('codetot_footer_row_top', array($this, 'render_section'));
  }

  public function render_section() {
    $data = get_field('guarantee_list', 'options');
    $footer_background = get_global_option('codetot_footer_background_color') ?? 'dark';

    $class = 'section-bg guarantee-list--' . $this->position;
    $class .= codetot_is_dark_background($footer_background) ? ' bg-light is-light-contract' : ' bg-dark is-dark-contract';

    $guarantee_list_section_settings = apply_filters('codetot_woocommerce_global_guarantee_list_settings', [
      'class' => $class,
      'layout' => 'row',
      'content_alignment' => 'left',
      'columns' => count($data)
    ]);

    $data = array_map(function($data_row) {
      $data_row['icon_type'] = 'image';
      $data_row['icon_image'] = $data_row['image'];

      return $data_row;
    }, $data);

    $guarantee_list_section_settings['items'] = $data;

    if (!empty($data)) {
      the_block('guarantee-list', $guarantee_list_section_settings);
    }
  }
}

Codetot_WooCommerce_Global_Guarantee_List::instance();
