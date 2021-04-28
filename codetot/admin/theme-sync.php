<?php

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) exit;

class CodeTot_Admin {
  /**
   * Singleton instance
   *
   * @var CodeTot_Admin
   */
  private static $instance;
  /**
   * @var string|void
   */
  private $admin_sync_page_url;
  /**
   * @var string
   */
  private $menu_slug;
  /**
   * @var string
   */
  private $parent_slug;
  /**
   * @var string
   */
  private $option;

  /**
   * Get singleton instance.
   *
   * @return CodeTot_Admin
   */
  public final static function instance() {
    if ( is_null( self::$instance ) ) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Class constructor
   */
  private function __construct() {
    $this->parent_slug = 'themes.php';
    $this->menu_slug = 'ct-theme-sync';
    $this->option = 'ct_theme';
    $this->admin_sync_page_url = admin_url() . $this->parent_slug . '?page=' . $this->menu_slug;

    add_action('init', function() {
      add_action( 'admin_menu', array( $this, 'add_import_export_page' ) );

      $this->import_process_init();
      $this->reset_settings_init();
    });

    add_action('admin_notices',  array($this, 'display_message'));
  }

  public function display_message() {
    if (empty($_GET['result'])) {
      return;
    }

    if ($_GET['result'] === 'success') {
      $this->display_success_message();
    }

    if ($_GET['result'] === 'failure') {
      $this->display_error_message();
    }
  }

  public function display_success_message() {
    $class = 'notice notice-success';
    $message = __( 'Your action has been processed!', 'ct-theme' );

    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
  }

  public function display_error_message() {
    $class = 'notice notice-error';
    $message = __( 'There is unknown error with your action. Please try to contact Administrator for more detail.', 'ct-theme' );

    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
  }

  /**
   * Display a message: current settings were a theme default settings
   */
  public function display_default_settings_message() {
    $class = 'notice notice-warning';
    $message = __( 'Your current theme settings were a theme default settings.', 'ct-theme' );

    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
  }

  /**
   * @param array $settings
   * @param bool $redirect
   * @return bool
   */
  public function update_settings($settings, $redirect = true) {
    if (empty($settings) || !is_array($settings)) {
      return false;
    }

    $result = update_option($this->option, $settings);

    if ($redirect) {
      $result_code = $result ? 'success' : 'failure';
      wp_redirect(add_query_arg('result', $result_code, $this->admin_sync_page_url));
      exit;
    } else {
      return $result;
    }
  }

  public function import_process_init() {
    global $pagenow;

    if ($pagenow === $this->parent_slug &&
      !empty($_GET['page']) && $_GET['page'] === $this->menu_slug
      && !empty($_POST['import_settings'])
      && empty($_GET['result'])
    ) {
      $settings = $this->format_json_to_array($_POST['import_settings']);
      $this->update_settings($settings, true);
    }
  }

  /**
   * @param string $json_content
   * @return array
   */
  public function format_json_to_array($json_content) {
    if (empty($json_content)) {
      return [];
    }

    return json_decode(stripslashes($json_content), true);
  }

  public function reset_settings_init() {
    global $pagenow;

    if ($pagenow === $this->parent_slug &&
      !empty($_GET['page']) && $_GET['page'] === $this->menu_slug
      && !empty($_GET['action']) && $_GET['action'] == 'reset_settings'
      && empty($_GET['result'])
    ) {
      // Try to access settings.json in child theme
      $theme_data_file = get_stylesheet_directory() . '/settings.json';
      $theme_data = file_exists($theme_data_file) ? file_get_contents($theme_data_file) : '';
      $default_theme_settings = !empty($theme_data) ? $this->format_json_to_array($theme_data) : [];
      $current_settings = get_option($this->option);

      if ($default_theme_settings === $current_settings) {
        $this->display_default_settings_message();
      }

      if (!empty($default_theme_settings) && $default_theme_settings !== $current_settings) {
        $this->update_settings($default_theme_settings, false);
      }
    }
  }

  /**
   * Register options page
   */
  public function add_import_export_page() {
    add_submenu_page(
      'themes.php',
      __('CT Theme Sync', 'ct-theme'),
      __('CT Theme Sync', 'ct-theme'),
      'manage_options',
      $this->menu_slug,
      array( $this, 'import_export_page' )
    );
  }

  public function load_settings_to_textarea() {
    $settings = get_option($this->option);
    $output_array = array();

    foreach($settings as $key => $setting) {
      if (!empty($setting)) {
        $output_array[$key] = $setting;
      }
    }

    if (!empty($output_array)) {
      return wp_json_encode($output_array);
    } else {
      return '{}';
    }
  }

  public function import_export_page() {
    ?>
    <div class="wrap">
      <h1><?php _e('CT Theme Sync', 'ct-themes'); ?></h1>
      <p><?php _e('Here you can import/export settings for a current theme. Please be careful or ask our Support Team to help you update this.', 'ct-theme'); ?></p>
      <table class="form-table" role="presentation">
        <tbody>
          <tr>
            <th scope="row"><?php _e('Export Theme Settings', 'ct-theme'); ?></th>
            <td>
              <label class="screen-reader-text" for="export_settings"><?php _e('Export data', 'ct-theme');?></label>
              <p><textarea name="export_settings" id="export_settings" rows="10" cols="50" class="large-text code" disabled><?php echo $this->load_settings_to_textarea(); ?></textarea></p>
            </td>
          </tr>
          <tr>
            <th scope="row"><?php _e('Import Theme Settings', 'ct-theme'); ?></th>
            <td>
              <form method="POST" action="<?php echo $this->admin_sync_page_url; ?>">
                <label class="screen-reader-text" for="import_settings"><?php _e('Export data', 'ct-theme');?></label>
                <p><textarea name="import_settings" id="import_settings" rows="10" cols="50" class="large-text code"></textarea></p>
                <?php submit_button(esc_html__('Start import', 'ct-theme')); ?>
              </form>
            </td>
          </tr>
          <tr>
            <th scope="row"><?php _e('Reset to default', 'ct-theme'); ?></th>
            <td>
              <p><?php _e('If you wish to reset back to current theme\'s default settings, click a below button.', 'ct-theme');?></p>
              <p>
                <a href="<?php echo add_query_arg('action', 'reset_settings', $this->admin_sync_page_url); ?>"><?php _e('Reset Settings', 'ct-theme'); ?></a>
              </p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php
  }
}

CodeTot_Admin::instance();
