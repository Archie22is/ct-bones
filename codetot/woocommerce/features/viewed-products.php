<?php
if (! defined('ABSPATH')) {
    exit;
}

class Codetot_WooCommerce_Viewed_Products
{
    /**
     * Singleton instance
     *
     * @var Codetot_WooCommerce_Viewed_Products
     */
    private static $instance;

    /**
     * Get singleton instance.
     *
     * @return Codetot_WooCommerce_Viewed_Products
     */
    final public static function instance()
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
        $this->prefix = 'codetot_';

        add_action('customize_register', array($this, 'register_customizer_settings'));
        add_action('wp', function () {
            $this->enable = codetot_get_theme_mod('enable_viewed_product_section', 'woocommerce') ?? true;

            if ($this->enable) {
                add_action('codetot_single_product_sections', 'codetot_render_viewed_products_section', 40);
            }
        });
    }

    public function register_customizer_settings($wp_customize)
    {
        $section_settings_id = 'codetot_woocommerce_addon_settings';

        codetot_customizer_register_control(array(
      'id' => 'enable_viewed_product_section',
      'label' => esc_html__('Enable Viewed Product Section', 'ct-bones'),
      'section_settings_id' => $section_settings_id,
      'option_type' => 'codetot_woocommerce_settings',
      'control_args' => array(
        'type' => 'checkbox'
      )
    ), $wp_customize);

        return $wp_customize;
    }
}

function codetot_render_viewed_products_section($class = '')
{
    $viewed_products = ! empty($_COOKIE['woocommerce_recently_viewed']) ? (array) explode('|', wp_unslash($_COOKIE['woocommerce_recently_viewed'])) : array(); // @codingStandardsIgnoreLine
    $viewed_products = array_reverse(array_filter(array_map('absint', $viewed_products)));

    if (empty($viewed_products)) {
        return;
    }

    $columns = codetot_get_theme_mod('single_product_viewed_products_column', 'woocommerce') ?? '4-col';
    $_columns = str_replace('-col', '', $columns);
    $enable_slider = codetot_get_theme_mod('single_product_viewed_products_enable_slider', 'woocommerce') ?? true;
    $enable_container = codetot_get_theme_mod('single_product_sections_enable_container', 'woocommerce') ?? false;

    $post_args = array(
    'posts_per_page' => apply_filters('codetot_viewed_products_number', $_columns),
    'no_found_rows'  => 1,
    'post_status'    => 'publish',
    'post_type'      => 'product',
    'post__in'       => $viewed_products,
    'orderby'        => 'post__in',
  );

    if ('yes' === get_option('woocommerce_hide_out_of_stock_items')) {
        $post_args['tax_query'] = array(
      array(
        'taxonomy' => 'product_visibility',
        'field'    => 'name',
        'terms'    => 'outofstock',
        'operator' => 'NOT IN',
      ),
    );
    }

    $_post_args = apply_filters('woocommerce_recently_viewed_products_widget_query_args', $post_args);

    $post_query = new WP_Query($_post_args);

    $_class = 'section product-grid--viewed-products';
    $_class .= !$enable_container ? ' default-section--no-container' : '';
    $_class .= !empty($class) ? ' ' . esc_html($class) : '';

    if ($post_query->have_posts()) :
    the_block('product-grid', array(
      'loop_args' => array(
        'name' => 'viewed_products'
      ),
      'enable_slider' => $enable_slider,
      'class' => $_class,
      'title' => apply_filters('codetot_product_viewed_products_heading', __('Recently Viewed Products', 'woocommerce')),
      'query' => $post_query,
      'columns' => $_columns
    ));
    endif;
}
add_action('template_redirect', 'codetot_track_product_view', 20);

function codetot_track_product_view()
{
    if (! is_singular('product')) {
        return;
    }

    global $post;

    if (empty($_COOKIE['woocommerce_recently_viewed'])) {
        $viewed_products = array();
    } else {
        $viewed_products = (array) explode('|', $_COOKIE['woocommerce_recently_viewed']);
    }

    if (! in_array($post->ID, $viewed_products)) {
        $viewed_products[] = $post->ID;
    }

    if (sizeof($viewed_products) > 15) {
        array_shift($viewed_products);
    }

    wc_setcookie('woocommerce_recently_viewed', implode('|', $viewed_products));
}

Codetot_WooCommerce_Viewed_Products::instance();
