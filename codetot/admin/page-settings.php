<?php
if (!defined('WPINC')) {
  die;
}

class Codetot_Page_Settings
{
  /**
   * Singleton instance
   *
   * @var Codetot_Page_Settings
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Page_Settings
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
    add_action('acf/init', array($this, 'register_page_settings'));
  }

  public function register_page_settings()
  {
    if( function_exists('acf_add_local_field_group') ):

      acf_add_local_field_group(array(
        'key' => 'group_60d2ef9eae6de',
        'title' => 'Page Settings',
        'fields' => array(
          array(
            'key' => 'field_60dc3cc3cccd4',
            'label' => 'Page Body Class',
            'name' => 'page_body_class',
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
            'rows' => 1,
            'new_lines' => '',
          ),
          array(
            'key' => 'field_60dc40236c005',
            'label' => 'Remove Footer Top Spacing',
            'name' => 'remove_footer_top_spacing',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'message' => '',
            'default_value' => 0,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
          ),
          array(
            'key' => 'field_60dc3cd5cccd5',
            'label' => 'Page CSS',
            'name' => 'page_css',
            'type' => 'textarea',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => 'js-page-css-editor',
              'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'maxlength' => '',
            'rows' => '',
            'new_lines' => '',
          ),
        ),
        'location' => array(
          array(
            array(
              'param' => 'page_template',
              'operator' => '==',
              'value' => 'flexible',
            ),
          ),
        ),
        'menu_order' => 10,
        'position' => 'normal',
        'style' => 'seamless',
        'label_placement' => 'left',
        'instruction_placement' => 'field',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
      ));

      endif;
  }
}

Codetot_Page_Settings::instance();
