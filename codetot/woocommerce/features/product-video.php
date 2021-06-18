<?php

// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_WooCommerce_Product_Video {
  /**
   * Singleton instance
   *
   * @var Codetot_WooCommerce_Product_Video
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_WooCommerce_Product_Video
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
    $this->tab_id = 'ct_product_video';
    $this->data_key = 'ct_product_video_data';
    $this->field_name = '_ct_product_video_url';

    add_filter('woocommerce_product_data_tabs', array($this, 'register_data_tab_field'));
    add_action('woocommerce_product_data_panels', array($this, 'load_data_tab_panel'), 100);
    add_action('save_post_product', array($this, 'save_field_data'));
  }

  public function register_data_tab_field($tabs) {
    $tabs[$this->tab_id] = array(
      'label' => esc_html__('Product Video', 'ct-bones'),
      'target' => $this->data_key,
      'priority' => 90
    );

    return $tabs;
  }

  public function load_data_tab_panel() {
    ?>
    <div id="<?php echo $this->data_key; ?>" class="panel woocommerce_options_panel">
      <?php
      woocommerce_wp_select(array(
        'id' => '_ct_product_video_type',
        'label' => __('Video Type', 'ct-bones'),
        'options' => array(
          'url' => __('URL', 'ct-bones'),
          'attachment_id' => __('Attachment ID', 'ct-bones'),
          'youtube' => __('Youtube URL', 'ct-bones')
        )
      ));

      woocommerce_wp_text_input(array(
        'id' => '_ct_product_video_url',
        'placeholder' => __('Enter a product video URL', 'ct-bones'),
        'label' => __('Video URL', 'ct-bones')
      ));
      ?>
    </div>
    <?php
  }

  public function save_field_data($product_id) {
    global $pagenow, $typenow;
    if ( 'post.php' !== $pagenow || 'product' !== $typenow ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    if (!empty($_POST['_ct_product_video_type'])) {
      update_post_meta($product_id, '_ct_product_video_type', esc_html($_POST['_ct_product_video_type']));
    }

    if (!empty($_POST['_ct_product_video_url'])) {
      update_post_meta($product_id, '_ct_product_video_url', esc_url_raw($_POST['_ct_product_video_url']));
    } else {
      delete_post_meta($product_id, '_ct_product_video_url');
    }
  }
}

Codetot_WooCommerce_Product_Video::instance();
