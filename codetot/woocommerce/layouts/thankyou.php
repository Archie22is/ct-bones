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
class Codetot_Woocommerce_Layout_Thankyou extends Codetot_Woocommerce_Layout
{
  /**
   * Singleton instance
   *
   * @var Codetot_Woocommerce_Layout_Thankyou
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Woocommerce_Layout_Thankyou
   */
  public final static function instance()
  {
    add_filter('woocommerce_bacs_accounts', '__return_false');
    add_action( 'woocommerce_thankyou_bacs', 'codetot_thankyou_page' );
  }
}

function codetot_thankyou_page($order_id){
  codetot_bank_details($order_id);
}

function codetot_bank_details( $order_id = '' ) {

  $bacs_accounts = get_option('woocommerce_bacs_accounts');
  $title = __('Account details','ct-bones');

  if ( ! empty( $bacs_accounts ) ) {

    ob_start();
    echo '<div class="woocommerce-order-account-details">';
    echo '<h2 class="woocommerce-order-account-details__title">' . $title .'</h2>';
    echo '<table class="woocommerce-order-account-details__table">';
    ?>
    <?php
    foreach ( $bacs_accounts as $bacs_account ) {
      $bacs_account = (object) $bacs_account;
      $account_name = $bacs_account->account_name;
      $bank_name = $bacs_account->bank_name;
      $account_number = $bacs_account->account_number;
      ?>
      <tr class="bank-account-detail">
        <td>
          <strong><?php _e('Account number', 'ct-bones'); ?>:</strong> <?php echo $account_number; ?><br>
          <strong><?php _e('Account name', 'ct-bones'); ?>:</strong> <?php echo $account_name;?><br>
          <strong><?php _e('Bank name', 'ct-bones'); ?>:</strong> <?php echo $bank_name;?>
        </td>
      </tr>
      <?php
    }
    echo '</table> </div>';
    echo ob_get_clean();;
  }

}

Codetot_Woocommerce_Layout_Thankyou::instance();
