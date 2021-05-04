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
    remove_action( 'woocommerce_before_cart', 'woocommerce_output_all_notices', 10 );
    add_action('woocommerce_before_cart', array($this, 'print_errors'), 10);

    remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
    remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
    remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );

    add_action('woocommerce_before_cart', array($this, 'cart_page_container_open'), 15);
    add_action('woocommerce_before_cart', array($this, 'cart_page_grid_open'), 20);
    // Column: Cart table
    add_action('woocommerce_before_cart', array($this, 'cart_page_col_open_main'),  25);
    add_action('woocommerce_after_cart_table',  array($this, 'cart_page_col_close'), 90);

    // Column: Cart Totals
    add_action('woocommerce_before_cart_collaterals', array($this, 'cart_page_col_open_sidebar'),  1);
    add_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10);
    add_action('woocommerce_cart_collaterals', 'woocommerce_button_proceed_to_checkout', 20);

    add_action('woocommerce_after_cart_totals',  array($this, 'cart_page_col_close'), 90);
    add_action('woocommerce_after_cart', array($this, 'cart_page_grid_close'), 19);
    add_action('woocommerce_after_cart', array($this, 'cart_page_container_close'), 20);

    add_filter( 'woocommerce_add_to_cart_fragments', array($this, 'woocommerce_header_add_to_cart_fragment'),5);
  }

  public function cart_page_container_open() {
    echo '<div class="page-block page-block-cart">';
  }

  public function cart_page_container_close() {
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
    <span class="header__menu-icons__count">
      <?php echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'ct-theme'), $woocommerce->cart->cart_contents_count);?>
    </span>
    <?php
    $fragments['.header__menu-icons__count'] = ob_get_clean();
    return $fragments;
  }
}

Codetot_Woocommerce_Layout_Cart::instance();
