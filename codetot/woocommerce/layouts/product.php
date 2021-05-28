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
   * Global product sidebar
   *
   * @var bool
   */
  private $enable_sidebar;

  /**
   * Top sidebar
   *
   * @var bool
   */
  private $enable_top_sidebar;

  /**
   * Bottom sidebar
   *
   * @var bool
   */
  private $enable_bottom_sidebar;

  /**
   * Class constructor
   */
  private function __construct()
  {
    $product_sidebar_layout = get_global_option('codetot_product_layout') ?? 'no-sidebar';
    $this->enable_sidebar = $product_sidebar_layout !== 'no-sidebar';
    $this->enable_top_sidebar = is_active_sidebar('top-product-sidebar');
    $this->enable_bottom_sidebar = is_active_sidebar('bottom-product-sidebar');

    add_action('wp_enqueue_scripts', array($this, 'enqueue_single_product_assets'));

    // Swap position price and rating star.
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);

    add_action('woocommerce_before_main_content', array($this, 'print_errors'), 11);

    add_filter('woocommerce_get_stock_html', array($this, 'update_stock_text'), 10, 2);

    // Container and wrapper
    if (is_singular('product')) {
      $this->generate_wrapper();
    }

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
    add_action('woocommerce_before_single_product_summary', array($this, 'single_product_column_open_secondary'), 60); // .grid__col

    // Product Title
    add_action('woocommerce_single_product_summary', array($this, 'single_product_title_open'), 3);
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 4);
    add_action('woocommerce_single_product_summary', array($this, 'single_product_title_close'), 5);

    add_action('woocommerce_after_single_product_summary', array($this, 'single_product_column_close'), 4); // .grid__col
    add_action('woocommerce_after_single_product_summary', array($this, 'single_product_grid_close'), 5); // ./grid

    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
    add_action('woocommerce_single_product_summary',  array($this, 'woocommerce_single_meta'), 35);
    add_action('woocommerce_single_product_summary',  array($this, 'woocommerce_single_meta_tag'), 40);

    //single-product-main
    add_action('woocommerce_after_single_product_summary', array($this,'open_content_single_product'), 5);
    add_action('woocommerce_after_single_product_summary', array($this,'close_content_single_product'), 25);
    add_filter( 'woocommerce_product_tabs', array($this,'woo_custom_description_tab'), 98 );

    // Render sections after top product section
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
    if ($this->enable_bottom_sidebar) :
      add_action('woocommerce_after_single_product_summary', array($this, 'after_single_product_container_grid_open'), 6);
    endif;
    add_action('woocommerce_after_single_product_summary', array($this, 'render_cross_sell_products'), 30);
    add_action('woocommerce_after_single_product_summary', array($this, 'render_upsell_sections'), 35);

    if ($this->enable_bottom_sidebar) :
      add_action('woocommerce_after_single_product_summary', array($this, 'after_single_product_container_grid_close'), 24);
    endif;
  }

  public function generate_wrapper() {
    $sidebar_layout = get_global_option('codetot_product_layout') ?? 'no-sidebar';

    add_action('codetot_after_header', array($this, 'breadcrumbs'), 5);

    if ($sidebar_layout !== 'no-sidebar') {
      add_action('codetot_after_header', array($this, 'page_block_open'), 10);
      add_action('codetot_before_sidebar', array($this, 'page_block_between'), 10);
      add_action('codetot_footer', array($this, 'page_block_close'), 10);
    }

    if ($this->enable_top_sidebar) {
      add_action('woocommerce_before_single_product_summary', array($this, 'top_product_sidebar_open'), 75);
      add_action('woocommerce_after_single_product_summary', array($this, 'top_product_sidebar_between'), 2);
      add_action('woocommerce_after_single_product_summary', array($this, 'top_product_sidebar_close'), 3);
    }
  }

  public function breadcrumbs() {
    if (is_singular('product')) {
      woocommerce_breadcrumb();
    }
  }

  public function page_block_open() {
    $class = 'page-block page-block--product';
    $sidebar_layout = get_global_option('codetot_product_layout') ?? 'no-sidebar';
    $class .= ' ' . esc_attr($sidebar_layout);

    echo '<div class="' . esc_attr($class) . '">';
    echo '<div class="container page-block__container">';
    if ($sidebar_layout !== 'no-sidebar') :
      echo '<div class="grid page-block__grid">';
      echo '<div class="grid__col page-block__col page-block__col--main">';
    endif;
  }

  public function page_block_between() {
    if (is_singular('product')) :
      echo '</div>'; // Close .page-block__col--main
      echo '<div class="grid__col page-block__col page-block__col--sidebar">';
    endif;
  }

  public function page_block_close() {
    if (is_singular('product')) :
      $sidebar_layout = get_global_option('codetot_product_layout') ?? 'no-sidebar';

      if ($sidebar_layout !== 'no-sidebar') :
        echo '</div>'; // close .page-block__col--sidebar
        echo '</div>'; // close .page-block__grid
      endif;
      echo '</div>'; // close .page-block__container
      echo '</div>'; // close .page-block--product-category
    endif;
  }

  public function open_content_single_product() {
    echo '<div class="single-product-main">';
    if (!$this->enable_sidebar) :
      echo '<div class="container single-product-main__container">';
    endif;
  }

  public function woo_custom_description_tab( $tabs ) {
    $tabs['description']['callback'] = array($this,'woo_custom_description_tab_content');
    return $tabs;
  }

  public function woo_custom_description_tab_content() {
    the_block('product-description', array(
      'content' => apply_filters('the_content', get_the_content())
    ));
  }

  public function close_content_single_product() {
    if (!$this->enable_sidebar) :
      echo '</div>';
    endif;
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
    if (!$this->enable_sidebar) :
      echo '<div class="container single-product-top__container">';
    endif;
    echo '<div class="single-product-top__grid">';
  }

  public function single_product_grid_close() {
    echo '</div>';
    if (!$this->enable_sidebar) :
      echo '</div>';
    endif;
    echo '</div>';
  }

  public function single_product_column_open() {
    echo '<div class="single-product-top__col">';
  }


  // Top product sidebar
  public function single_product_column_open_secondary() {
    echo '<div class="single-product-top__col single-product-top__col--sidebar">';
    // echo '</div>';
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

    $availability = $product->get_availability();
    echo '<div class="availability_wrapper">';
    esc_html_e( 'Availability: ', 'woocommerce' );
    echo '<span class="availability">';
    echo ( $availability['class'] != 'in-stock') ? ( $availability['availability'],'woocommerce') : esc_html__( 'In stock', 'woocommerce' );
    echo '</span>';
    echo '</div>';

    if ( !empty( $product->get_height() ) || !empty( $product->get_width() ) || !empty( $product->get_length() ) ) {
      echo '<div class="dimensions_wrapper">';
      esc_html_e( 'Size: ', 'woocommerce' );
      $space = ' x ';

      if(!empty($product->get_height())) :
        echo '<span class="height">';
        echo  $product->get_height() . get_option( 'woocommerce_dimension_unit' );
        echo '</span>';
        echo $space;
      endif;

      if(!empty($product->get_width()) && !empty($product->get_height())) :
        echo '<span class="width">';
        echo  $product->get_width() . get_option( 'woocommerce_dimension_unit' );
        echo '</span>';
        echo $space;
      endif;

      if(!empty($product->get_length()) && !empty($product->get_width())) :
        echo '<span class="length">';
        echo  $product->get_length() . get_option( 'woocommerce_dimension_unit' );
        echo '</span>';
      endif;
      echo '</div>';
    }

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
    $sidebar_layout = get_global_option('codetot_product_layout') ?? 'no-sidebar';
    $cross_sell_product_ids = get_post_meta( $post->ID, '_crosssell_ids',true);
    $columns = get_global_option('codetot_woocommerce_cross_sell_products_colums') ?? '4';
    if (empty($cross_sell_product_ids)) {
      return '';
    }
    $post_args = array(
      'post_type' => 'product',
      'post_status' => 'publish',
      'posts_per_page' => apply_filters('codetot_related_products_number', 4),
      'post__in' => $cross_sell_product_ids
    );

    $_class = 'section product-grid--cross-sell-products';
    if ($sidebar_layout !== 'no-sidebar') {
      $_class .= ' product-grid--no-container';
    }

    $post_query = new WP_Query($post_args);

    if ($post_query->have_posts()) :
      the_block('product-grid', array(
        'class' => $_class,
        'title' => apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'woocommerce' ) ),
        'query' => $post_query,
        'columns' => $columns
      ));
    endif;
  }

  public function render_upsell_sections() {
    if ( ! is_singular( 'product' ) ) {
      return;
    }

    $sidebar_layout = get_global_option('codetot_product_layout') ?? 'no-sidebar';

    $_class = 'section product-grid--upsells';

    if ($sidebar_layout !== 'no-sidebar') {
      $_class .= ' product-grid--no-container';
    }
    $upsell_products = codetot_get_upsell_products();

    if (empty($upsell_products)) {
      return;
    }
    $columns = get_global_option('codetot_woocommerce_upsells_products_colums') ?? '4';
    the_block('product-grid', array(
      'class' => $_class,
      'title' => apply_filters( 'woocommerce_product_upsells_products_heading', __( 'You may also like&hellip;', 'woocommerce' ) ),
      'list' => $upsell_products,
      'columns' => $columns
    ));
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

  public function top_product_sidebar_open() {
    echo '<div class="single-product-top__main">';
    echo '<div class="grid single-product-top__main-grid">';
    echo '<div class="grid__col single-product-top__main-col single-product-top__main-col--left">';
  }

  public function top_product_sidebar_between() {
    echo '</div>'; // Close .single-product-top__main-col
    echo '<div class="grid__col single-product-top__main-col single-product-top__main-col--right">';
    dynamic_sidebar('top-product-sidebar');
  }

  public function top_product_sidebar_close() {
    echo '</div>'; // Close .single-product-top__main-col--right
    echo '</div>'; // Close .single-product-top__main-grid
    echo '</div>'; // Close .single-product-top__main
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

  public function after_single_product_container_grid_open() {
    if ($this->enable_bottom_sidebar) :
      echo '<div class="grid single-product-main__grid">';
      echo '<div class="grid__col single-product-mains__col single-product-main__col--left">';
    endif;
  }

  public function after_single_product_container_grid_close() {
    if ($this->enable_bottom_sidebar) :
      echo '</div>'; // Close .single-product-sections__col--left
      echo '<div class="grid__col single-product-main__col single-product-mains__col--right">';
      dynamic_sidebar('bottom-product-sidebar');
      echo '</div>'; // Close .single-product-sections__col--right
      echo '</div>'; // Close .single-product-sections__grid
    endif;
  }
}

Codetot_Woocommerce_Layout_Product::instance();
