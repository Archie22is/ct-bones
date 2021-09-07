<?php
// Prevent direct access.
if (!defined('ABSPATH')) {
    exit;
}

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
    final public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        // Settings
        $this->enable = codetot_get_theme_mod('enable_quick_view', 'woocommerce') ?? false;

        if ($this->enable) {
            add_action('wp_enqueue_scripts', array($this, 'load_assets'));
            add_filter('body_class', array($this, 'add_body_class'));

            // Add elements
            // add_action('woocommerce_before_shop_loop_item_title', 'codetot_quick_view_button', 21);
            add_action('wp_footer', 'codetot_quick_view_modal');

            // Load JSON content
            add_action('wp_ajax_shop_quick_view', array($this, 'get_content_json'));
            add_action('wp_ajax_nopriv_shop_quick_view', array($this, 'get_content_json'));

            add_action('wp_ajax_shop_quick_view_add_to_cart', array($this, 'add_to_cart_json'));
            add_action('wp_ajax_nopriv_shop_quick_view_add_to_cart', array($this, 'add_to_cart_json'));

            add_action('codetot_product_quick_view_markup', 'codetot_product_quick_view_icon_html', 1);
        }
    }

    public function load_assets()
    {
        wp_enqueue_script('flexslider');
        wp_enqueue_script('zoom');
        wp_enqueue_script('wc-single-product');
    }

    public function add_body_class($classes)
    {
        $classes[] = 'has-quick-view-product';

        return $classes;
    }

    /**
     * Ajax Quick View
     */
    public function get_content_json()
    {
        check_ajax_referer('codetot-config-nonce', 'ajax_nonce', false);

        $response = array(
      'status' => 500,
      'message' => __('Something is wrong, please try again later&hellip;', 'ct-bones'),
      'content' => false,
    );

        if (!isset($_POST['product_id'])) {
            echo json_encode($response);
            exit();
        }

        $product_id = absint($_POST['product_id']);

        // For cross-sells on Cart page.
        $product_obj = wc_get_product($product_id);
        $parent_id = $product_obj->get_parent_id();

        if (!empty($parent_id)) {
            $product_id = $parent_id;
        }

        wp('p=' . $product_id . '&post_type=product');

        ob_start();

        $final_price = codetot_get_price_discount_percentage($product_obj, 'percentage');
        $classes = ['product__tag', 'product__tag--onsale'];

        if (!empty($final_price)) :
      ?>
      <span class="<?php echo esc_attr(implode(' ', array_filter($classes))); ?>">
        <?php echo esc_html($final_price); ?>
      </span>
      <?php
    endif;
        $sale_badge_html = ob_get_clean();

        ob_start();

        if (have_posts()) {
            while (have_posts()) {
                the_post(); ?>
        <div class="quick-view-modal__header">
          <h2 class="quick-view-modal__title">
            <a class="d-block quick-view-modal__title-link" href="<?php the_permalink(); ?>" title="<?php printf(__('View product %s', 'ct-bones'), get_the_title()); ?>"><?php the_title(); ?></a>
          </h2>
        </div>
        <div class="quick-view-modal__summary">
          <?php
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
                remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
                do_action('woocommerce_single_product_summary');

                $enable_excerpt = codetot_get_theme_mod('quick_view_short_description', 'woocommerce') ?? true;
                if ($enable_excerpt) {
                    echo '<div class="wysiwyg woocommerce-product-details__short-description">';
                    the_excerpt();
                    echo '</div>';
                } ?>
        </div>
        <?php
            }
        }

        $content_html = ob_get_clean();

        ob_start();
        echo $sale_badge_html;
        echo '<div class="quick-view-modal__images">';
        woocommerce_show_product_images();
        echo '</div>';
        $slider_html = ob_get_clean();

        $output_arr = array(
      'sliderHtml' => $slider_html,
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
        'message' => __('Quantity of products in stock has been exhausted. The product is not added to the cart.', 'ct-bones'),
      );

            wp_send_json($data);
        }

        wp_die();
    }
}

function codetot_product_quick_view_icon_html()
{
    echo apply_filters('codetot_product_quick_view_html', codetot_svg('eyeglasses', false));
}

function codetot_quick_view_button()
{
    global $product; ?>
  <div class="product__quick-view">
    <span title="<?php esc_attr_e('Quick view', 'ct-bones'); ?>"
      data-quick-view-modal-id="<?php echo esc_attr($product->get_id()); ?>"
      class="product__quick-view-text">
        <?php
        /**
         * @hook codetot_product_quick_view_icon_html - 1
         */
        do_action('codetot_product_quick_view_markup'); ?>
    </span>
  </div>
  <?php
}

function codetot_quick_view_modal()
{
    the_block('quick-view-modal');
}

Codetot_Woocommerce_Quick_View::instance();
