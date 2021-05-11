<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

class CodeTot_Shortcode
{
  /**
   * Singleton instance
   *
   * @var CodeTot_Shortcode
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return CodeTot_Shortcode
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
    add_action('init', array($this, 'register_shortcodes'));
  }

  public function register_shortcodes()
  {
    add_shortcode('social-link', array($this, 'render_social_link_shortcode'));
    add_shortcode('contact', array($this, 'render_contact_shortcode'));
    add_shortcode('search-form', array($this, 'render_search_product_form'));
    add_shortcode('cart-icon', array($this, 'render_cart_icon'));
    add_shortcode('search-icon', array($this, 'render_search_icon'));
  }

  public function render_social_link_shortcode($atts) {
    $settings = shortcode_atts(array(
      'type' => 'light',
      'class' => ''
    ), $atts, 'social-link');

    $class ='social-links--' . $settings['type'] . '-contract';
    $class .= !empty($settings['class']) ? ' ' . $settings['class'] : '';

    return get_block('social-links', array(
      'class' => $class
    ));
  }

  public function render_contact_shortcode($atts) {
    $settings = shortcode_atts(array(
      'class' => 'contact-shortcode--default'
    ), $atts, 'contact');

    return get_block('contact-shortcode', array(
      'class' => $settings['class']
    ));
  }

  public function render_search_product_form() {
    return get_block('search-product-form');
  }

  public function render_cart_icon($atts) {
    $settings = shortcode_atts(array(
      'hide_icon' => get_global_option('codetot_header_hide_cart_icon') ?? false,
      'link' => class_exists('WooCommerce') ? wc_get_cart_url() : null,
      'svg_icon' => 'cart',
      'class' => 'cart-shortcode',
      'span_class' => ''
    ), $atts);

    ob_start();
    $text = !empty($settings['svg_icon']) ? codetot_svg($settings['svg_icon'], false) : esc_html__('Cart', 'woocommerce');
    printf('<span class="cart-shortcode__inner">%s</span>', $text);
    if (is_object(WC()->cart) && !empty(WC()->cart)) : ?>
      <span class="cart-shortcode__count">
        <?php printf ( _n( '%d', '%d', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?>
      </span>
    <?php endif;
    if (!empty($settings['svg_icon'])) {
      printf('<span class="screen-reader-text">%s</span>', esc_html__('Cart', 'woocommerce'));
    }
    $html = ob_get_clean();

    if (!empty($settings['link'])) {
      return sprintf('<a class="%1$s" href="%2$s" target="%3$s">%4$s</a>',
        !empty($settings['class']) ? $settings['class'] : 'cart-shortcode',
        $settings['link'],
        !empty($settings['link_target']) ? $settings['link_target'] : '_self',
        $html
      );
    } else {
      return $html;
    }
  }

  public function render_search_icon($atts) {
    $settings = shortcode_atts(array(
      'button_class' => 'search-icon',
      'button_attributes' => 'data-open-modal="modal-search-form"',
      'span_class' => 'search-icon__icon',
      'svg_icon' => 'search',
      'text' => ''
    ), $atts, 'search-icon');

    ob_start();
    printf('<button class="%1$s" %2$s>', $settings['button_class'], $settings['button_attributes']);
    printf('<span class="%s">', $settings['span_class']);
    if (!empty($settings['svg_icon'])) {
      codetot_svg($settings['svg_icon'], true);
    } elseif (!empty($settings['text'])) {
      echo $settings['text'];
    } else {
      echo esc_html__('Search', 'wordpress');
    }
    echo '</span>';
    echo '</button>';
    return ob_get_clean();
  }
}

CodeTot_Shortcode::instance();
