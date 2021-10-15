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
    add_shortcode('social-share', array($this, 'render_social_share_shortcode'));
    add_shortcode('icon', array($this, 'render_icon_shortcode'));
		add_shortcode('ct_post_grid', array($this, 'render_post_grid'));
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

  public function render_icon_shortcode($atts) {
    $settings = shortcode_atts(array(
      'name' => 'star',
      'class' => 'icon_svg'
    ), $atts);

    ob_start();

    echo '<span class="'.$settings['class'].'">';
    codetot_svg($settings['name'], true);
    echo '</span>';

    $svg = ob_get_clean();

    return $svg;
  }

  public function render_social_share_shortcode() {
    ob_start();
    global $post;

    the_block('social-links', array(
      'class' => 'social-links--share',
      'label' => __('Share', 'ct-bones'),
      'items' => codetot_get_share_post_links($post)
    ));
    $html = ob_get_clean();
    return $html;
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
      'hide_icon' => codetot_get_theme_mod('header_hide_cart_icon') ?? false,
      'link' => class_exists('WooCommerce') && function_exists('wc_get_cart_url') ? wc_get_cart_url() : null,
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
      'button_attributes' => 'data-modal-component-open="modal-search-form"',
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
    printf('<span class="screen-reader-text">%s</span>', esc_html__('Open a search form', 'ct-bones'));
    echo '</button>';
    return ob_get_clean();
  }

	public function render_post_grid($atts) {
		$attributes = shortcode_atts( array(
			'columns' => 3,
			'number' => 3,
			'category' => null
		), $atts );

		if (!is_array($attributes['category'])) {
			$attributes['category'] = explode(',', $attributes['category']);
		}

		$post_args = array(
			'post_type' => 'post',
			'posts_per_page' => (int) $attributes['number']
		);

		if (!empty($attributes['category']) && is_array($attributes['category'])) {
			$post_args['category__in'] = $attributes['category'];
		}

		$post_query = new WP_Query($post_args);

		$_class = 'wp-block-group ct-post-grid';
		$_class .= ' has-' . esc_html($attributes['columns']) . '-columns';

		ob_start();
		if ($post_query->have_posts()) :
			echo '<div class="wp-block-columns">';
			while ($post_query->have_posts())  : $post_query->the_post();
				echo '<div class="wp-block-column">';
				the_block('post-card', array(
					'class' => 'card-content',
					'card_style' => !empty($post_card_style) ? $post_card_style : 'style-1'
				));
				echo '</div>';
			endwhile; wp_reset_postdata();
			echo '</div>';
		endif;
		$content = ob_get_clean();

		ob_start();
		echo '<div class="' . $_class . '">';
		echo $content;
		echo '</div>';
		return ob_get_clean();
	}
}

CodeTot_Shortcode::instance();
