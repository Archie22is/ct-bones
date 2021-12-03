<?php
// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Automattic\Jetpack\Constants;

/**
 * @link       https://codetot.com
 * @since      1.0.0
 * @package    Codetot_Woocommerce
 * @subpackage Codetot_Woocommerce/includes/layout
 * @author     CODE TOT JSC <khoi@codetot.com>
 */
class Codetot_Woocommerce_Layout_Checkout extends Codetot_Woocommerce_Layout {

	/**
	 * Singleton instance
	 *
	 * @var Codetot_Woocommerce_Layout_Checkout
	 */
	private static $instance;

	/**
	 * @var string
	 */
	private $theme_environment;

	/**
	 * Get singleton instance.
	 *
	 * @return Codetot_Woocommerce_Layout_Checkout
	 */
	final public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->theme_environment = $this->is_localhost() ? '' : '.min';

		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
		// add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );

		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
		// remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

		add_action( 'woocommerce_before_checkout_form', array( $this, 'render_shop_steps' ), 20 );

		// Layout
		remove_action( 'woocommerce_before_checkout_form_cart_notices', 'woocommerce_output_all_notices', 10 );
		add_action( 'woocommerce_checkout_before_customer_details', array( $this, 'page_block_open' ), 10 );
		add_action( 'woocommerce_checkout_after_customer_details', array( $this, 'page_block_between' ), 40 );
		add_action( 'woocommerce_after_checkout_form', array( $this, 'page_block_close' ), 90 );

		add_filter( 'woocommerce_default_address_fields', array( $this, 'update_fields_order' ) );
		add_filter( 'woocommerce_checkout_fields', array( $this, 'update_placeholder_fields' ) );

		if ( is_checkout() ) {
			add_action( 'codetot_page', array( $this, 'container_open' ), 1 );
			add_action( 'codetot_page', array( $this, 'checkout_content' ), 10 );
			add_action( 'codetot_page', array( $this, 'container_close' ), 10 );
		}

		add_action( 'woocommerce_checkout_order_review', 'ct_bones_render_checkout_total_block', 10 );
		add_action( 'woocommerce_checkout_order_review', 'ct_bones_render_order_review_title', 15 );
		add_action( 'woocommerce_checkout_order_review', 'ct_bones_render_coupon_form', 12 );

		add_filter( 'woocommerce_update_order_review_fragments', array( $this, 'update_checkout_fragments' ) );

		// Sticky mobile checkout
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'update_fragments' ) );
		add_filter( 'woocommerce_after_checkout_form', array( $this, 'sticky_mobile_checkout_block' ), 100 );

		// Move shipping section
		// add_filter('woocommerce_cart_ready_to_calc_shipping', '__return_false');
	}

	public function checkout_content() {
		the_content();
	}

	public function container_open() {
		echo '<div class="page-block page-block--checkout">';
		echo '<div class="container page-block__container">';
	}

	public function container_close() {
		echo '</div>';
		echo '</div>';
	}

	public function page_block_open() {
		echo '<div class="grid page-block__grid">';
		echo '<div class="grid__col page-block__col page-block__col--main">';
	}

	public function page_block_between() {
		echo '</div>';
		echo '<div class="grid__col page-block__col page-block__col--sidebar">';
	}

	public function page_block_close() {
		echo '</div>';
		echo '</div>';
	}

	public function sticky_mobile_checkout_block() {
		the_block( 'sticky-mobile-checkout' );
	}

	public function update_fields_order( $fields ) {
		unset( $fields['company'] );
		unset( $fields['address_2'] );
		unset( $fields['postcode'] );

		return $fields;
	}

	public function update_fragments( $fragments ) {
		ob_start(); ?>
	<span class="sticky-mobile-checkout__value">
		<?php wc_cart_totals_order_total_html(); ?>
	</span>
		<?php
		$sticky_checkout_price = ob_get_clean();

		$fragments['span.sticky-mobile-checkout__value'] = $sticky_checkout_price;

		return $fragments;
	}

	public function update_checkout_fragments( $fragments ) {
		ob_start();
		ct_bones_render_coupon_form();
		$checkout_form = ob_get_clean();

		$fragments['.checkout-total']       = '<div class="checkout-total">' . get_block( 'checkout-total' ) . '</div>';
		$fragments['.checkout-coupon-form'] = $checkout_form;

		return $fragments;
	}

	public function update_placeholder_fields( $fields ) {
		$fields['billing']['billing_first_name']['placeholder'] = esc_html__( 'First name', 'woocommerce' );
		$fields['billing']['billing_last_name']['placeholder']  = esc_html__( 'Last name', 'woocommerce' );
		$fields['billing']['billing_phone']['placeholder']      = esc_html__( 'Phone', 'woocommerce' );
		$fields['billing']['billing_city']['placeholder']       = esc_html__( 'City', 'woocommerce' );
		$fields['billing']['billing_email']['placeholder']      = esc_html__( 'Email', 'woocommerce' );

		$fields['shipping']['shipping_first_name']['placeholder'] = esc_html__( 'First name', 'woocommerce' );
		$fields['shipping']['shipping_last_name']['placeholder']  = esc_html__( 'Last name', 'woocommerce' );
		$fields['shipping']['shipping_phone']['placeholder']      = esc_html__( 'Phone', 'woocommerce' );
		$fields['shipping']['shipping_city']['placeholder']       = esc_html__( 'City', 'woocommerce' );

		return $fields;
	}

	public function render_shop_steps() {
		the_block( 'shop-steps' );
	}

	/**
	 * @return bool
	 */
	public function is_localhost() {
		return ! empty( $_SERVER['HTTP_X_CODETOT_CHILD_HEADER'] ) && $_SERVER['HTTP_X_CODETOT_CHILD_HEADER'] === 'development';
	}
}

function ct_bones_render_checkout_total_block() {
	echo '<div class="checkout-total">';
	the_block( 'checkout-total' );
	echo '</div>';
}

function ct_bones_render_order_review_title() {
	?>
	<h3 class="order-review__title"><?php echo esc_html__( 'Payment method:', 'woocommerce' ); ?></h3>
	<?php
}

function ct_bones_render_coupon_form() {
	$coupons        = WC()->cart->get_coupons();
	$close_svg_icon = '<svg viewBox="0 0 20 20">
		<path fill="currentColor" d="M15.898,4.045c-0.271-0.272-0.713-0.272-0.986,0l-4.71,4.711L5.493,4.045c-0.272-0.272-0.714-0.272-0.986,0s-0.272,0.714,0,0.986l4.709,4.711l-4.71,4.711c-0.272,0.271-0.272,0.713,0,0.986c0.136,0.136,0.314,0.203,0.492,0.203c0.179,0,0.357-0.067,0.493-0.203l4.711-4.711l4.71,4.711c0.137,0.136,0.314,0.203,0.494,0.203c0.178,0,0.355-0.067,0.492-0.203c0.273-0.273,0.273-0.715,0-0.986l-4.711-4.711l4.711-4.711C16.172,4.759,16.172,4.317,15.898,4.045z"></path>
	</svg>';

	?>
	<div class="checkout-coupon-form">
	<label class="h3 checkout-coupon-form__label" for="custom_coupon_code"><?php _e( 'Coupons', 'woocommerce' ); ?></label>
	<div class="checkout-coupon-form__wrapper">
		<?php if ( ! empty( $coupons ) ) : ?>
		<ul class="checkout-coupon-form__list">
			<?php
			foreach ( WC()->cart->get_coupons() as $code => $coupon ) :
				$remove_coupon_html = ' <a title="' . esc_html__( 'Remove coupon', 'woocommerce' ) . '" href="' . esc_url( add_query_arg( 'remove_coupon', rawurlencode( $coupon->get_code() ), Constants::is_defined( 'WOOCOMMERCE_CHECKOUT' ) ? wc_get_checkout_url() : wc_get_cart_url() ) ) . '" class="woocommerce-remove-coupon" data-coupon="' . esc_attr( $coupon->get_code() ) . '">' . $close_svg_icon . '</a>';
				?>
			<li class="checkout-coupon-form__item">
				<span class="coupon"><?php echo esc_attr( $code ); ?></span>
				<span class="action"><?php echo $remove_coupon_html; ?></span>
			</li>
			<?php endforeach; ?>
		</ul>
		<?php else : ?>
		<span class="checkout-coupon-form__description"><?php esc_html_e( 'If you have a coupon code, please apply it below.', 'woocommerce' ); ?></span>
		<?php endif; ?>
		<span class="w100 f checkout-coupon-form__form-wrapper">
		<input type="text" name="custom_coupon_code" class="checkout-coupon-form__input" id="custom_coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" />
		<span class="button button--secondary checkout-coupon-form__button js-coupon-trigger">
			<span class="button__text"><?php esc_html_e( 'Apply', 'woocommerce' ); ?></span>
		</span>
		</span>
		<?php do_action( 'woocommerce_cart_coupon' ); ?>
	</div>
	</div>
	<?php
}

Codetot_Woocommerce_Layout_Checkout::instance();
