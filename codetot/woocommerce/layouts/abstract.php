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
abstract class Codetot_Woocommerce_Layout {
  public function print_errors()
  {
    the_block('message-block', array(
      'content' => wc_print_notices(true)
    ));
  }
}
