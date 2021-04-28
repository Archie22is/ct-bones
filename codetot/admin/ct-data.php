<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_CT_Data_Settings {
  /**
   * Singleton instance
   *
   * @var Codetot_CT_Data_Settings
   */
  private static $instance;
    /**
   * @var string
   */
  public $prefix;
  /**
   * @var string
   */
  public $filter_prefix;
  /**
   * @var string
   */
  public $setting_id;
    /**
   * Get singleton instance.
   *
   * @return Codetot_CT_Data_Settings
   */
  public final static function instance() {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function __construct()
  {
    $this->prefix = 'codetot_';
    $this->filter_prefix = 'codetot_data_';
    $this->setting_id = 'ct-data';
    $this->option_name = 'ct_data';

    add_filter('mb_settings_pages', array($this, 'register_settings_pages'));
    add_filter('rwmb_meta_boxes', array($this, 'register_global_settings_fields'));
    add_filter('rwmb_meta_boxes', array($this, 'register_social_settings_fields'));
    add_filter('rwmb_meta_boxes', array($this, 'register_api_settings_fields'));
  }

  public function register_settings_pages($setting_pages) {
    $setting_pages[] = [
      'menu_title'    => __( 'CT Data', 'ct-theme' ),
      'id'            => 'ct-data',
      'option_name'   => $this->option_name,
      'capability'    => 'level_10',
      'style'         => 'no-boxes',
      'columns'       => 1,
      'tabs'          => apply_filters('codetot_data_tabs', array(
        'global'      => __('Global', 'ct-theme'),
        'social'      => __('Social Links', 'ct-theme'),
        'api'         => __('API Keys', 'ct-theme'),
      )),
      'submit_button' => __( 'Save' ),
      'customizer'    => false,
      'icon_url'      => 'dashicons-forms',
    ];

    return $setting_pages;
  }

  public function register_global_settings_fields( $meta_boxes ) {
    $default_fields = array_merge(
      array(
        [
          'type' => 'heading',
          'name' => __( 'Company Information', 'ct-theme' ),
        ]
      ),
      apply_filters('codetot_company_fields', codetot_get_company_info_inputs())
    );

    $meta_boxes[] = [
      'title'          => __( 'Global', 'ct-theme' ),
      'id'             => 'ct-data-global-settings',
      'settings_pages' => [$this->setting_id],
      'tab'            => 'global',
      'fields'         => apply_filters(
        $this->filter_prefix . 'general_fields',
        $default_fields
      ),
    ];

    return $meta_boxes;
  }

  public function register_social_settings_fields( $meta_boxes ) {
    $default_fields = array_merge(
      array(
        [
          'type' => 'heading',
          'name' => __( 'Social Media Profile URL', 'ct-theme' )
        ]
      ),
      apply_filters('codetot_social_fields', codetot_get_social_media_options())
    );

    $meta_boxes[] = [
      'title'          => __( 'Social Links', 'ct-theme' ),
      'id'             => 'ct-data-social-settings',
      'settings_pages' => [$this->setting_id],
      'tab'            => 'social',
      'fields'         => apply_filters(
        $this->filter_prefix . 'social_fields',
        $default_fields
      ),
    ];

    return $meta_boxes;
  }

  public function register_api_settings_fields( $meta_boxes ) {
    $meta_boxes[] = [
      'title'          => __( 'API Keys', 'ct-theme' ),
      'id'             => 'ct-data-api-settings',
      'settings_pages' => [$this->setting_id],
      'tab'            => 'api',
      'fields'         => [
        [
          'name'    => __( 'Google Maps API Key', 'ct-theme' ),
          'id'      => $this->prefix . 'google_maps_api_key',
          'desc'    => esc_html__('Using Google Maps API Key to create map interactive with visitors.', 'ct-theme'),
          'type'    => 'text',
        ]
      ],
    ];

    return $meta_boxes;
  }
}

Codetot_CT_Data_Settings::instance();
