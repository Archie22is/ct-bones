<?php

// Prevent direct access.
if (!defined('ABSPATH')) exit;

/**
 * Display a coming soon template and replace all pages.
 *
 * @package ct_bones
 * @author Code Tot JSC <dev@codetot.com>
 */
class CT_Bones_Coming_Soon {
    /**
   * Singleton instance
   *
   * @var CT_Bones_Coming_Soon
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return CT_Bones_Coming_Soon
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
  public function __construct()
  {
    $enable = get_global_option('codetot_settings_enable_coming_soon') ?? false;
    $type = get_global_option('codetot_settings_coming_soon_type') ?? 'default';

    if ($enable) {

    }
  }
}

CT_Bones_Coming_Soon::instance();
