<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_Woocommerce_Layout_Product
{
  /**
   * Singleton instance
   *
   * @var Codetot_Woocommerce_Layout_Product
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Woocommerce_Layout_Product
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
    add_action('wp_enqueue_scripts', array($this, 'enqueue_single_product_assets'));

    // Swap position price and rating star.
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);

    add_action('woocommerce_before_main_content', array($this, 'print_errors'), 11);

    add_filter('woocommerce_get_stock_html', array($this, 'update_stock_text'), 10, 2);

    remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10);
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);

    add_action('woocommerce_before_single_product_summary', array($this, 'print_errors'), 5);
    add_action('woocommerce_before_single_product_summary', array($this, 'single_product_grid_open'), 12); // .grid
    // Product Gallery column
    add_action('woocommerce_before_single_product_summary', array($this, 'single_product_column_open'), 15); // .grid__col
    add_action('woocommerce_before_single_product_summary', array($this, 'single_product_gallery'), 20);
    add_action('woocommerce_before_single_product_summary', array($this, 'single_product_column_close'), 50); // /.grid__col

    // Column: Product Detail (Right)
    add_action('woocommerce_before_single_product_summary', array($this, 'single_product_column_open'), 60); // .grid__col

    // Product Title
    add_action('woocommerce_before_single_product_summary', array($this, 'single_product_title_open'), 61);
    add_action('woocommerce_before_single_product_summary', 'woocommerce_template_single_title', 65);
    add_action('woocommerce_before_single_product_summary', array($this, 'single_product_title_close'), 70);

    add_action('woocommerce_after_single_product_summary', array($this, 'single_product_column_close'), 4); // .grid__col
    add_action('woocommerce_after_single_product_summary', array($this, 'single_product_grid_close'), 5); // ./grid
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
    add_action('woocommerce_single_product_summary',  array($this, 'woocommerce_single_meta'), 6);
    add_action('woocommerce_single_product_summary',  array($this, 'woocommerce_single_meta_tag'), 40);
    add_action('woocommerce_after_single_product', array($this, 'render_cross_sell_products'), 10);
    add_action('woocommerce_after_single_product', array($this, 'render_upsell_sections'), 20);
    add_action('woocommerce_after_single_product_summary', array($this,'open_content_single_product'), 5);
    add_action('woocommerce_after_single_product_summary', array($this,'close_content_single_product'), 25);
  }

  public function page_block_open() {
    if (is_product()) :
      $class = 'page-block page-block--product';
      $sidebar_layout = get_global_option('codetot_product_layout') ?? 'sidebar-left';
      $class .= ' ' . esc_attr($sidebar_layout);

      echo '<div class="' . esc_attr($class) . '">';
      echo '<div class="container page-block__container">';
      echo '<div class="grid page-block__grid">';
      echo '<div class="grid__col page-block__col page-block__col--main">';
    endif;
  }

  public function page_block_between() {
    if (is_product()) :
      echo '</div>'; // Close .page-block__col--main
      echo '<div class="grid__col page-block__col page-block__col--sidebar">';
    endif;
  }

  public function page_block_close() {
    if (is_product()) :
      echo '</div>'; // close .page-block__col--sidebar
      echo '</div>'; // close .page-block__grid
      echo '</div>'; // close .page-block__container
      echo '</div>'; // close .page-block--product-category
    endif;
  }

  public function open_content_single_product() {
    echo '<div class="single-product-main">';
    echo '<div class="container single-product-main__container">';
  }
  public function close_content_single_product() {
    echo '</div>';
    echo '</div>';
  }
  public function enqueue_single_product_assets() {
    if (is_singular('product')) {
      wp_enqueue_style('fancybox-style', '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css', null, '3.5.7', 'all');
      wp_enqueue_script('fancybox-script', '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js', array('jquery'), '3.5.7', true);
    }
  }

  public function single_product_grid_open() {
    echo '<div class="single-product-top">';
    echo '<div class="container single-product-top__container">';
    echo '<div class="single-product-top__grid">';
  }

  public function single_product_grid_close() {
    echo '</div>';
    echo '</div>';
    echo '</div>';
  }

  public function single_product_column_open() {
    echo '<div class="single-product-top__col">';
  }
  public function woocommerce_single_meta() {
    global $product;
   echo  '<div class="single-product-meta">';

    do_action( 'woocommerce_product_meta_start' );

    if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) :
      echo '<div class="sku_wrapper">';
      esc_html_e( 'SKU: ', 'woocommerce' );
      echo '<span class="sku">';
      echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woocommerce' );
      echo '</span>';
      echo '</div>';
    endif;

    echo wc_get_product_category_list( $product->get_id(), ', ', '<div class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</div>' );

    do_action( 'woocommerce_product_meta_end' );

  echo '</div>' ;

  }

  public function woocommerce_single_meta_tag() {
    global $product;
    do_action( 'woocommerce_product_meta_start' );
    echo wc_get_product_tag_list( $product->get_id(), ', ', '<div class="single-product-tag tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</div>' );
    do_action( 'woocommerce_product_meta_end' );

  }

  public function render_cross_sell_products() {
    global $post;
    $cross_sell_product_ids = get_post_meta( $post->ID, '_crosssell_ids',true);

    if (empty($cross_sell_product_ids)) {
      return '';
    }

    $post_args = array(
      'post_type' => 'product',
      'post_status' => 'publish',
      'posts_per_page' => apply_filters('codetot_related_products_number', 4),
      'post__in' => $cross_sell_product_ids
    );

    $post_query = new WP_Query($post_args);

    if ($post_query->have_posts()) :
      the_block('product-grid', array(
        'class' => 'product-grid--related-products product-grid--4-columns',
        'title' => apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'woocommerce' ) ),
        'query' => $post_query
      ));
    endif;
  }

  public function single_product_column_close() {
    echo '</div>';
  }

  public function single_product_title_open() {
    echo '<div class="single-product-top__header">';
  }

  public function single_product_title_close() {
    echo '</div>';
  }

  public function render_upsell_sections() {
    if ( ! is_singular( 'product' ) ) {
      return;
    }


    $upsell_products = codetot_get_upsell_products();

    if (empty($upsell_products)) {
      return;
    }

    the_block('product-grid', array(
      'class' => 'product-grid--no-container product-grid--upsells',
      'title' => apply_filters( 'woocommerce_product_upsells_products_heading', __( 'You may also like', 'woocommerce' ) ),
      'list' => $upsell_products
    ));
  }

  public function update_stock_text( $html, $product ) {
    $availability = $product->get_availability();

    if ( isset( $availability['class'] ) && 'in-stock' === $availability['class'] ) {
      return '';
    }

    return $html;
  }

  public function single_product_gallery() {
    the_block('product-gallery');
  }

  public function print_errors()
  {
    if (is_singular('product')) {
      the_block('message-block', array(
        'content' => wc_print_notices(true)
      ));
    }
  }
}

Codetot_Woocommerce_Layout_Product::instance();
