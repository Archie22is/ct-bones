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
class Codetot_Woocommerce_Mini_Cart extends Codetot_Woocommerce_Layout
{
    /**
     * Singleton instance
     *
     * @var Codetot_Woocommerce_Mini_Cart
     */
    private static $instance;

    /**
     * Get singleton instance.
     *
     * @return Codetot_Woocommerce_Mini_Cart
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
        $this->enable = codetot_get_theme_mod('enable_mini_cart', 'woocommerce') ?? true;

        if ($this->enable) {
            add_action('wp_footer', array($this, 'woocommerce_cart_sidebar'));
            add_action('wp_ajax_update_quantity_in_mini_cart', array($this, 'ajax_update_quantity_in_mini_cart'));
            add_action('wp_ajax_nopriv_update_quantity_in_mini_cart', array($this, 'ajax_update_quantity_in_mini_cart'));

            add_filter('woocommerce_widget_cart_item_quantity', array($this, 'update_quantity_mini_cart'), 10, 3);

            add_filter('woocommerce_add_to_cart_fragments', array($this, 'woocommerce_header_add_to_cart_fragment'), 10);
        }
    }

    public function woocommerce_cart_sidebar()
    {
        $total = WC()->cart->cart_contents_count; ?>
		<div class="mini-cart-sidebar" data-woocommerce-block="mini-cart">
			<div class="w100 abs mini-cart__overlay js-mini-cart-close"></div>
			<div class="mini-cart__wrapper">
				<div class="mini-cart__head">
					<p class="text-uppercase mini-cart__title"><?php esc_html_e('Shopping cart', 'ct-bones'); ?></p>
					<span class="mini-cart__count"><?php echo esc_html($total); ?></span>
					<button class="mini-cart__close js-mini-cart-close" aria-label="<?php _e('Close a mini cart', 'ct-bones'); ?>">
						<?php codetot_svg('close', true); ?>
					</button>
				</div>
				<div class="widget_shopping_cart_content">
					<?php $this->codetot_mini_cart(); ?>
				</div>
			</div>
		</div>
	<?php
    }

    public function update_quantity_mini_cart($output, $cart_item, $cart_item_key)
    {
        $product        = $cart_item['data'];
        $stock_quantity = $product->get_stock_quantity();
        $product_price  = WC()->cart->get_product_price($product);

        ob_start(); ?>
		<span class="mini-cart__info">
			<span class="mini-cart-quantity quantity">
				<span class="product-qty" data-qty="minus"><?php codetot_svg('minus', true); ?></span>
				<input type="number" data-cart_item_key="<?php echo esc_attr($cart_item_key); ?>" class="input-text qty" step="1" min="1" max="<?php echo esc_attr($stock_quantity ? $stock_quantity : ''); ?>" value="<?php echo esc_attr($cart_item['quantity']); ?>" inputmode="numeric" />
				<span class="product-qty" data-qty="plus"><?php codetot_svg('plus', true); ?></span>
			</span>

			<span class="mini-cart-product-price"><?php echo wp_kses_post($product_price); ?></span>
		</span>
		<?php
        return ob_get_clean();
    }

    public function codetot_mini_cart()
    {
        do_action('woocommerce_before_mini_cart');

        if (!WC()->cart->is_empty()) {
            ?>
			<ul class="cart_list product_list_widget">
				<?php
                do_action('woocommerce_before_mini_cart_contents');

            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                // $bundled_cart_items = wc_pb_get_bundled_cart_items( $cart_item ); This is template code.

                if ($_product && $_product->exists() && $cart_item['quantity'] > 0) {
                    $product_name      = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                    $thumbnail         = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);
                    $product_price     = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key); ?>
						<li class="woocommerce-mini-cart-item mini_cart_item <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?>">
							<?php
                            echo apply_filters( // phpcs:ignore
                                'woocommerce_cart_item_remove_link',
                        sprintf(
                                    '<a href="%s" class="remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
                                    esc_url(wc_get_cart_remove_url($cart_item_key)),
                                    esc_attr__('Remove this item', 'ct-bones'),
                                    esc_attr($product_id),
                                    esc_attr($cart_item_key),
                                    esc_attr($_product->get_sku())
                                ),
                        $cart_item_key
                    ); ?>
							<?php if (empty($product_permalink)) : ?>
								<?php echo $thumbnail . $product_name; // phpcs:ignore
                                ?>
							<?php else : ?>
								<a href="<?php echo esc_url($product_permalink); ?>">
									<?php echo $thumbnail . $product_name; // phpcs:ignore
                                    ?>
								</a>
							<?php endif; ?>
							<?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore
                            ?>
							<?php echo apply_filters('woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf('%s &times; %s', $cart_item['quantity'], $product_price) . '</span>', $cart_item, $cart_item_key); // phpcs:ignore
                            ?>
						</li>
				<?php
                }
            }

            do_action('woocommerce_mini_cart_contents'); ?>
			</ul>

			<p class="woocommerce-mini-cart__total total">
				<?php
                /**
                 * Hook: woocommerce_widget_shopping_cart_total.
                 *
                 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
                 */
                do_action('woocommerce_widget_shopping_cart_total'); ?>
			</p>

			<?php do_action('woocommerce_widget_shopping_cart_before_buttons'); ?>

			<p class="woocommerce-mini-cart__buttons buttons"><?php do_action('woocommerce_widget_shopping_cart_buttons'); ?></p>

		<?php
            do_action('woocommerce_widget_shopping_cart_after_buttons');
        } else {
            ?>
			<p class="woocommerce-mini-cart__empty-message"><?php esc_html_e('No products in the cart.', 'ct-bones'); ?></p>
<?php
        }

        do_action('woocommerce_after_mini_cart');
    }

    public function ajax_update_quantity_in_mini_cart()
    {
        check_ajax_referer('codetot-config-nonce', 'nonce', false);
        if (!isset($_POST['key']) || !isset($_POST['qty'])) {
            wp_send_json_error();
        }

        $response = array();

        $cart_item_key = sanitize_text_field(wp_unslash($_POST['key']));
        $product_qty = absint($_POST['qty']);

        WC()->cart->set_quantity($cart_item_key, $product_qty);

        $count = WC()->cart->get_cart_contents_count();

        ob_start();
        $response['item'] = $count;
        $response['total_price'] = WC()->cart->get_cart_total();
        $response['content'] = ob_get_clean();

        wp_send_json_success($response);
    }

    public function woocommerce_header_add_to_cart_fragment($fragments)
    {
        global $woocommerce;
        ob_start();
        $this->codetot_mini_cart();
        $mini_cart = ob_get_clean();

        $fragments['.mini-cart__count'] = sprintf('<span class="mini-cart__count">' . sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'ct-bones'), $woocommerce->cart->cart_contents_count) . '</span>', $mini_cart);
        return $fragments;
    }
}

Codetot_Woocommerce_Mini_Cart::instance();
