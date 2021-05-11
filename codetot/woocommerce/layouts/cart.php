<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

/**
 * @link       https://codetot.com
 * @since      1.0.0
 * @package    Codetot_Woocommerce
 * @subpackage Codetot_Woocommerce/includes/layout
 * @author     CODE TOT JSC <khoi@codetot.com>
 */
class Codetot_Woocommerce_Layout_Cart extends Codetot_Woocommerce_Layout
{
  /**
   * Singleton instance
   *
   * @var Codetot_Woocommerce_Layout_Cart
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Woocommerce_Layout_Cart
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
  private function __construct()
  {
    remove_action('woocommerce_before_cart', 'woocommerce_output_all_notices', 10);
    remove_action('woocommerce_cart_is_empty', 'wc_empty_cart_message', 10);
    add_action('woocommerce_before_cart', array($this, 'print_errors'), 10);

    remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
    remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
    remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );

    if (is_cart()) {
      add_action('codetot_page', array($this, 'cart_content'), 10);
    }

    add_action('woocommerce_before_cart', array($this, 'container_open'), 15);
    add_action('woocommerce_before_cart', array($this, 'cart_page_grid_open'), 20);

    // Case 2: Empty cart
    add_action('woocommerce_cart_is_empty', array($this, 'container_open'), 15);
    add_action('woocommerce_cart_is_empty', array($this, 'cart_page_grid_open'), 20);
    add_action('woocommerce_cart_is_empty', array($this, 'cart_page_col_open_main'),  25);
    add_action('woocommerce_cart_is_empty', 'wc_empty_cart_message', 40);

    // Column: Cart table
    add_action('woocommerce_before_cart', array($this, 'cart_page_col_open_main'),  25);
    add_action('woocommerce_after_cart_table',  array($this, 'cart_page_col_close'), 90);

    // Column: Cart Totals
    add_action('woocommerce_before_cart_collaterals', array($this, 'cart_page_col_open_sidebar'),  1);
    add_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10);
    add_action('woocommerce_cart_collaterals', 'woocommerce_button_proceed_to_checkout', 20);

    add_action('woocommerce_after_cart_totals',  array($this, 'cart_page_col_close'), 90);
    add_action('woocommerce_after_cart', array($this, 'cart_page_grid_close'), 19);
    add_action('woocommerce_after_cart', array($this, 'container_close'), 20);

    add_filter( 'woocommerce_add_to_cart_fragments', array($this, 'woocommerce_header_add_to_cart_fragment'),5);
  }

  public function cart_content() {
    the_content();
  }

  public function container_open() {
    if ( WC()->cart->is_empty() ) {
      $class  ='page-block page-block--cart-empty';
    } else {
      $class = 'page-block page-block--cart';
    }

    echo '<div class="' . $class . '">';
    echo '<div class="container page-block__container">';
  }

  public function container_close() {
    echo '</div>';
    echo '</div>';
  }

  public function cart_page_grid_open() {
    echo '<div class="grid page-block__grid">';
  }

  public function cart_page_grid_close() {
    echo '</div>';
  }

  public function cart_page_col_open_main() {
    echo '<div class="grid__col page-block__col page-block__col--main">';
    echo '<div class="page-block__inner">';
  }

  public function cart_page_col_open_sidebar() {
    echo '<div class="grid__col page-block__col page-block__col--sidebar">';
    echo '<div class="page-block__inner">';
  }

  public function cart_page_col_close() {
    echo '</div>';
    echo '</div>';
  }

  function woocommerce_header_add_to_cart_fragment( $fragments ) {
    global $woocommerce;
    ob_start();
    ?>
    <span class="cart-shortcode__count">
      <?php echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'ct-bones'), $woocommerce->cart->cart_contents_count);?>
    </span>
    <?php
    $fragments['.cart-shortcode__count'] = ob_get_clean();
    return $fragments;
  }
}

Codetot_Woocommerce_Layout_Cart::instance();
