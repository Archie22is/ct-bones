<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

/**
 * @link       https://codetot.com
 * @since      1.0.0
 * @package    Codetot_Woocommerce
 * @subpackage Codetot_Woocommerce/includes/features
 * @author     CODE TOT JSC <khoi@codetot.com>
 */
class Codetot_Woocommerce_Quick_View extends Codetot_Woocommerce_Layout
{
  /**
   * Singleton instance
   *
   * @var Codetot_Woocommerce_Quick_View
   */
  private static $instance;

  /**
   * @var bool
   */
  private $enable;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Woocommerce_Quick_View
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
    // Settings
    $this->enable = get_global_option('codetot_woocommerce_enable_quick_view') ?? false;

    if ($this->enable) {
      // Add elements
      add_action('woocommerce_before_shop_loop_item_title', array($this, 'quick_view_button'), 20);
      add_action('wp_footer', array($this, 'quick_view_modal'));

      // Load JSON content
      add_action('wp_ajax_shop_quick_view', array($this, 'get_content_json'));
      add_action('wp_ajax_nopriv_shop_quick_view', array($this, 'get_content_json'));

      add_action('wp_ajax_shop_quick_view_add_to_cart', array($this, 'add_to_cart_json'));
      add_action('wp_ajax_nopriv_shop_quick_view_add_to_cart', array($this, 'add_to_cart_json'));
    }
  }

  public function quick_view_button()
  {
    global $product;
    $product_card_style = get_global_option('codetot_woocommerce_product_card_style') ?? 1;
    ?>
    <div class="product__quick-view">
        <span title="<?php esc_attr_e('Quick view', 'ct-theme'); ?>"
              data-quick-view-modal-id="<?php echo esc_attr($product->get_id()); ?>"
              class="product__quick-view-text">
          <?php if (!empty($product_card_style) && in_array($product_card_style, array('1', '2'))) : ?>
            <?php codetot_svg('eyeglasses', true); ?>
          <?php else : ?>
            <?php esc_attr_e('Quick view', 'ct-theme'); ?>
          <?php endif; ?>
        </span>
    </div>
    <?php
  }

  public function quick_view_modal()
  {
    the_block('quick-view-modal');
  }

  /**
   * Ajax Quick View
   */
  public function get_content_json()
  {

    check_ajax_referer('codetot-config-nonce', 'ajax_nonce', false);

    $response = array(
      'status' => 500,
      'message' => esc_html__('Something is wrong, please try again later...', 'ct-theme'),
      'content' => false,
    );

    if (!isset($_POST['product_id'])) {
      echo json_encode($response);
      exit();
    }

    $product_id = absint($_POST['product_id']);

    // For cross-sells on Cart page.
    $get_product = wc_get_product($product_id);
    $parent_id = $get_product->get_parent_id();

    if (!empty($parent_id)) {
      $product_id = $parent_id;
    }

    wp('p=' . $product_id . '&post_type=product');
    ob_start();

    if (have_posts()) {
      while (have_posts()) {
        the_post();
        ?>
        <div class="quick-view-modal__header">
          <h2 class="quick-view-modal__title"><?php the_title(); ?></h2>
        </div>
        <div class="quick-view-modal__summary">
          <?php
          remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
          do_action('woocommerce_single_product_summary');
          add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
          ?>
        </div>
        <?php
      }
    }

    $content_html = ob_get_clean();

    $output_arr = array(
      'sliderHtml' => get_block('quick-view-modal-slider', array(
        'product_id' => $product_id
      )),
      'imagesHtml' => get_block('quick-view-modal-images', array(
        'product_id' => $product_id
      )),
      'contentHtml' => $content_html
    );

    wp_send_json($output_arr);

  }

  public function add_to_cart_json()
  {
    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = absint($_POST['variation_id']);
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);

    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

      do_action('woocommerce_ajax_added_to_cart', $product_id);

      if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
        wc_add_to_cart_message(array($product_id => $quantity), true);
      }

      WC_AJAX:: get_refreshed_fragments();
    } else {

      $data = array(
        'error' => true,
        'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id),
        'message' => __('Quantity of products in stock has been exhausted. The product is not added to the cart.', 'ct-theme'),
      );

      wp_send_json($data);
    }

    wp_die();
  }

}

Codetot_Woocommerce_Quick_View::instance();
