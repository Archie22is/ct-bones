<?php

// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_WooCommerce_Product_Video
{
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

    $enable = codetot_get_theme_mod('enable_product_video', 'woocommerce') ?? false;

    if ($enable) {
      add_action('wp', function() {
        if (is_singular('product')) {
          global $post;
          $video_value = get_post_meta($post->ID, '_ct_product_video_url', true);

          if (!empty($video_value)) :
            remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
            add_action('woocommerce_before_single_product_summary', 'codetot_woocommerce_product_video_section', 20);
          endif;
        }
      }, 20);

      add_filter('woocommerce_product_data_tabs', array($this, 'register_data_tab_field'));
      add_action('woocommerce_product_data_panels', array($this, 'load_data_tab_panel'), 100);
      add_action('save_post_product', array($this, 'save_field_data'));

      add_filter('woocommerce_single_product_image_thumbnail_html', array($this, 'update_product_gallery'), 10, 2);
    }
  }

  public function register_data_tab_field($tabs)
  {
    $tabs[$this->tab_id] = array(
      'label' => esc_html__('Product Video', 'ct-bones'),
      'target' => $this->data_key,
      'priority' => 90
    );

    return $tabs;
  }

  public function load_data_tab_panel()
  {
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

  public function save_field_data($product_id)
  {
    global $pagenow, $typenow;
    if ('post.php' !== $pagenow || 'product' !== $typenow) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (!empty($_POST['_ct_product_video_type'])) {
      update_post_meta($product_id, '_ct_product_video_type', esc_html($_POST['_ct_product_video_type']));
    }

    if (!empty($_POST['_ct_product_video_url'])) {
      update_post_meta($product_id, '_ct_product_video_url', esc_url_raw($_POST['_ct_product_video_url']));
    } else {
      delete_post_meta($product_id, '_ct_product_video_url');
    }
  }

  public function update_product_gallery($html, $attachment_id) {
    return $html;
  }
}

function codetot_woocommerce_render_video() {
  global $product;

  $video_type = get_post_meta($product->get_id(), '_ct_product_video_type', true);
  $video_value = get_post_meta($product->get_id(), '_ct_product_video_url', true);

  $logo_id = get_theme_mod('custom_logo');
  $image_url = !empty($logo_id) ? wp_get_attachment_image_src($logo_id, 'medium')[0] : 'http://via.placeholder.com/600';

  if ($video_type === 'url' && !empty($video_value)) : ?>
    <div
      class="woocommerce-product-gallery__image has-video js-video-wrapper"
      data-video-type="<?php echo esc_attr($video_type); ?>"
      data-video-value="<?php echo esc_attr($video_value); ?>"
      data-thumb="<?php echo esc_url($image_url); ?>"
    >
      <div class="woocommerce-product-gallery__video-wrapper">
        <video class="woocommerce-product-gallery__video js-video" muted="" playsinline="" loop="">
          <source src="<?php echo esc_url($video_value); ?>" type="video/mp4">
        </video>
        <?php
        echo wp_get_attachment_image($logo_id, 'medium', false, array(
          'class' => 'wp-post-image',
          'loading' => false
        ));
        ?>
      </div>
    </div>
    <?php
  endif;
}

function codetot_woocommerce_product_video_section()
{
  global $product;

  $video_type = get_post_meta($product->get_id(), '_ct_product_video_type', true);
  $video_value = get_post_meta($product->get_id(), '_ct_product_video_url', true);

  $columns           = apply_filters('codetot_woocommerce_product_thumbnails_columns', 4);
  $post_thumbnail_id = $product->get_image_id();
  $wrapper_classes   = apply_filters(
    'woocommerce_single_product_image_gallery_classes',
    array(
      'woocommerce-product-gallery',
      'woocommerce-product-gallery--' . ($post_thumbnail_id ? 'with-images' : 'without-images'),
      'woocommerce-product-gallery--columns-' . absint($columns),
      'woocommerce-product-gallery--has-video',
      'js-slider-wrapper',
      'images',
    )
  );
  ?>
  <div
    class="product-gallery"
    data-video-type="<?php echo $video_type; ?>"
    data-video-url="<?php echo $video_value; ?>"
    data-woocommerce-block="product-video-slider"
  >
    <div
      class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>"
      data-columns="<?php echo esc_attr($columns); ?>"

    >
      <figure class="js-slider woocommerce-product-gallery__wrapper">
        <?php

        codetot_woocommerce_render_video();

        if (!empty($post_thumbnail_id)) {
          $html = wc_get_gallery_image_html($post_thumbnail_id, true);
        } else {
          $html  = '<div class="woocommerce-product-gallery__image js-slider-item woocommerce-product-gallery__image--placeholder">';
          $html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src('woocommerce_single')), esc_html__('Awaiting product image', 'woocommerce'));
          $html .= '</div>';
        }

        echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

        do_action('woocommerce_product_thumbnails');
        ?>
      </figure>
    </div>
  </div>
<?php
}

Codetot_WooCommerce_Product_Video::instance();
