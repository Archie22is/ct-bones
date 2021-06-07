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
    $this->sidebar_layout = get_global_option('codetot_product_layout') ?? 'no-sidebar';
    $this->enable_sidebar = $this->sidebar_layout !== 'no-sidebar';
    $this->enable_top_sidebar = is_active_sidebar('top-product-sidebar');
    $this->enable_bottom_sidebar = is_active_sidebar('bottom-product-sidebar');

    $this->generate_wrapper();

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
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

    add_action('woocommerce_before_single_product_summary', array($this, 'print_errors'), 5);
    add_action('woocommerce_before_single_product_summary', array($this, 'single_product_top_open'), 12); // .grid

    // Product Gallery column
    add_action('woocommerce_before_single_product_summary', array($this, 'single_product_column_open'), 15); // .grid__col
    add_action('woocommerce_before_single_product_summary', array($this, 'change_sale_flash_in_gallery'), 20);
    add_action('woocommerce_before_single_product_summary', array($this, 'single_product_gallery'), 25);
    add_action('woocommerce_before_single_product_summary', array($this, 'single_product_column_close'), 50); // /.grid__col

    // Column: Product Detail (Right)
    add_action('woocommerce_before_single_product_summary', array($this, 'single_product_column_open_secondary'), 60); // .grid__col

    // Product Title
    add_action('woocommerce_single_product_summary', array($this, 'single_product_title_open'), 1);
    add_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    // add_action('woocommerce_single_product_summary', array($this, 'display_product_stars'), 6);
    add_action('woocommerce_single_product_summary', array($this, 'single_product_title_close'), 15);

    add_action('woocommerce_after_single_product_summary', array($this, 'single_product_column_close'), 4); // .grid__col
    add_action('woocommerce_after_single_product_summary', array($this, 'single_product_top_close'), 5); // ./grid

    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
    add_action('woocommerce_single_product_summary',  array($this, 'woocommerce_single_meta'), 35);
    add_action('woocommerce_single_product_summary',  array($this, 'woocommerce_single_meta_tag'), 40);

    //single-product-main
    add_action('woocommerce_after_single_product_summary', array($this,'open_content_single_product'), 5);
    add_action('woocommerce_after_single_product_summary', array($this,'close_content_single_product'), 15);
    add_filter( 'woocommerce_product_tabs', array($this, 'woo_custom_description_tab'), 98 );

    add_action('woocommerce_after_single_product_summary', array($this, 'after_single_product_container_open'), 100);

    if ($this->enable_bottom_sidebar) :
      add_action('woocommerce_after_single_product_summary', array($this, 'after_single_product_container_grid_open'), 6);
    endif;

    add_action('woocommerce_after_single_product', array($this, 'render_related_products'), 10);
    add_action('woocommerce_after_single_product', array($this, 'render_cross_sell_products'), 15);
    add_action('woocommerce_after_single_product', array($this, 'render_upsell_sections'), 20);

    if ($this->enable_bottom_sidebar) :
      add_action('woocommerce_after_single_product_summary', array($this, 'after_single_product_container_grid_close'), 11);
    endif;

    add_action('woocommerce_after_single_product', array($this, 'after_single_product_container_close'), 40);
  }

  public function generate_wrapper() {
    if (!is_singular('product')) {
      return;
    }

    add_action('woocommerce_before_single_product', array($this, 'breadcrumbs'), 5);

    if ($this->sidebar_layout !== 'no-sidebar') {
      add_action('woocommerce_before_single_product', array($this, 'page_block_open'), 10);
      add_action('codetot_before_sidebar', array($this, 'page_block_between'), 1);
      add_action('codetot_footer', array($this, 'page_block_close'), 100);
    }

    if ($this->enable_top_sidebar) {
      add_action('woocommerce_before_single_product_summary', array($this, 'top_product_sidebar_open'), 75);
      add_action('woocommerce_after_single_product_summary', array($this, 'top_product_sidebar_close'), 2);
    }
  }

  public function breadcrumbs() {
    if (is_singular('product')) {
      woocommerce_breadcrumb();
    }
  }

  public function page_block_open() {
    $class = 'page-block page-block--product';
    $class .= ' ' . esc_attr($this->sidebar_layout);

    echo '<div class="' . esc_attr($class) . '">';
    echo '<div class="container page-block__container">';
    if ($this->sidebar_layout !== 'no-sidebar') :
      echo '<div class="grid page-block__grid">';
      echo '<div class="grid__col page-block__col page-block__col--main">';
    endif;
  }

  public function page_block_between() {
    if (is_singular('product') && $this->sidebar_layout !== 'no-sidebar') :
      // echo '</div>'; // Close .page-block__col--main
      echo '<div class="grid__col page-block__col page-block__col--sidebar">';
    endif;
  }

  public function page_block_close() {
    if (is_singular('product')) :

      if ($this->sidebar_layout !== 'no-sidebar') :
        echo '</div>'; // close .page-block__col--sidebar
        echo '</div>'; // close .page-block__grid
      endif;
      echo '</div>'; // close .page-block__container
      echo '</div>'; // close .page-block--product-category
    endif;
  }

  public function display_product_stars() {
    global $product;

    $average = $product->get_average_rating();
    $enable_star_rating = get_global_option('codetot_woocommerce_enable_product_star_rating_in_list') ?? false;

    if (!empty($average) || $enable_star_rating) :
      if ($enable_star_rating && $average == 0) {
        $average = 5;
      }
      ?>
      <div class="product__rating">
        <?php echo '<div class="product__rating-stars"><span style="width:'.( ( $average / 5 ) * 100 ) . '%"></span></div>'; ?>
      </div>
      <?php
    endif;
  }

  public function open_content_single_product() {
    echo '<div class="single-product-main">';
    if (!$this->enable_sidebar) :
      echo '<div class="container single-product-main__container">';
    endif;
  }

  public function woo_custom_description_tab( $tabs ) {
    if (!empty($tabs['description'])) {
      $tabs['description']['callback'] = array($this,'woo_custom_description_tab_content');
    }

    if (!empty($tabs['additional_information'])) {
      unset($tabs['additional_information']);
    }

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

  public function single_product_top_open() {
    echo '<div class="single-product-top">';
    if (!$this->enable_sidebar) :
      echo '<div class="container single-product-top__container">';
    endif;
    echo '<div class="single-product-top__grid">';
  }

  public function single_product_top_close() {
    echo '</div>'; // Close .single-product-top__grid
    if (!$this->enable_sidebar) :
      echo '</div>'; // Close .single-product-top__container
    endif;
    echo '</div>'; // Close .single-product-top
  }

  public function single_product_column_open() {
    echo '<div class="single-product-top__col">';
    echo '<div class="single-product-top__inner">';
  }

  public function single_product_column_close() {
    echo '</div>'; // Close .single-product-top__col
    echo '</div>'; // Close .single-product-top__inner
  }

  // Top product sidebar
  public function single_product_column_open_secondary() {
    echo '<div class="single-product-top__col single-product-top__col--sidebar">';
  }

  public function woocommerce_single_meta() {
    global $product;

    echo  '<div class="single-product-meta">';

    do_action( 'woocommerce_product_meta_start' );

    if ( wc_product_sku_enabled() && !empty($product->get_sku()) ) :
      printf('<p class="product-meta product-meta--sku"><span class="product-meta__label">%s:</span> <span class="product-meta__value">%s</span></p>',
      esc_html__( 'SKU: ', 'woocommerce' ),
      $product->get_sku()
    );
    endif;

    if ($product->has_weight()) {
      $weight_unit = get_option('woocommerce_weight_unit');

      printf('<p class="product-meta product-meta--weight"><span class="product-meta__label">%s:</span> <span class="product-meta__value">%s</span></p>',
        esc_html__('Weight', 'woocommerce'),
        $product->get_weight() . $weight_unit
      );
    }

    $availability = $product->get_availability();
    printf('<p class="product-meta product-meta--stock"><span class="product-meta__label">%s:</span> <span class="product-meta__value">%s</span></p>',
      esc_html__( 'Stock', 'woocommerce' ),
      $availability['class'] != 'in-stock' ? $availability['availability'] : esc_html__( 'In stock', 'woocommerce' )
    );

    if ( !empty( $product->get_height() ) || !empty( $product->get_width() ) || !empty( $product->get_length() ) ) {
      $space = ' x ';

      ob_start();
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
      $dimesions_html = ob_get_clean();

      printf('<p class="product-meta product-meta--dimesions"><span class="product-meta__label">%s:</span> <span class="product-meta__value">%s</span></p>',
        esc_html__( 'Size', 'woocommerce' ),
        $dimesions_html
      );
    }

    $product_categories = get_the_terms($product->get_id(), 'product_cat');
    if (!empty($product_categories) && !is_wp_error($product_categories)) {
      $product_category_label = _n('Category', 'Categories', count($product_categories), 'woocommerce');

      printf('<p class="product-meta product-meta--categories"><span class="product-meta__label">%s:</span> <span class="product-meta__value">%s</span></p>',
        $product_category_label,
        wc_get_product_category_list($product->get_id(), ', ')
      );
    }

    do_action( 'woocommerce_product_meta_end' );

    echo '</div>' ;

  }

  public function woocommerce_single_meta_tag() {
    global $product;
    do_action( 'woocommerce_product_meta_start' );
    echo wc_get_product_tag_list( $product->get_id(), ', ', '<div class="single-product-tag tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</div>' );
    do_action( 'woocommerce_product_meta_end' );
  }

  public function render_related_products() {
    global $product;
    $columns = get_global_option('codetot_woocommerce_cross_sell_products_colums') ?? '4';
    $related_product_ids = wc_get_related_products($product->get_id());

    if (empty($related_product_ids)) {
      return '';
    }

    $post_args = array(
      'post_type' => 'product',
      'post_status' => 'publish',
      'posts_per_page' => apply_filters('codetot_related_products_number', $columns),
      'post__in' => $related_product_ids
    );

    $_class = 'section product-grid--related-products';
    if ($this->sidebar_layout !== 'no-sidebar') {
      $_class .= ' default-section--no-container';
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

  public function render_cross_sell_products() {
    global $post;

    $cross_sell_product_ids = get_post_meta( $post->ID, '_crosssell_ids',true);
    $columns = get_global_option('codetot_woocommerce_cross_sell_products_colums') ?? '4';
    if (empty($cross_sell_product_ids)) {
      return '';
    }
    $post_args = array(
      'post_type' => 'product',
      'post_status' => 'publish',
      'posts_per_page' => apply_filters('codetot_related_products_number', $columns),
      'post__in' => $cross_sell_product_ids
    );

    $_class = 'section product-grid--cross-sell-products';
    if ($this->sidebar_layout !== 'no-sidebar') {
      $_class .= ' default-section--no-container';
    }

    $display_section = apply_filters('codetot_enable_cross_selling_sections', true);
    $post_query = new WP_Query($post_args);

    if ($post_query->have_posts() && $display_section) :
      the_block('product-grid', array(
        'class' => $_class,
        'title' => apply_filters( 'woocommerce_product_cross_sells_products_heading', __( 'You may be interested in&hellip;', 'woocommerce' )),
        'query' => $post_query,
        'columns' => $columns
      ));
    endif;
  }

  public function render_upsell_sections() {
    if ( ! is_singular( 'product' ) ) {
      return;
    }

    $_class = 'section product-grid--upsells';

    if ($this->sidebar_layout !== 'no-sidebar') {
      $_class .= ' default-section--no-container';
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

  public function top_product_sidebar_close() {
    echo '</div>'; // Close .single-product-top__main-col--left
    echo '<div class="grid__col single-product-top__main-col single-product-top__main-col--right">';
    dynamic_sidebar('top-product-sidebar');
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

  public function change_sale_flash_in_gallery()
  {
    global $product;
    if (empty($product)) {
      return;
    }

    $sale = $product->is_on_sale();
    $price_sale = $product->get_sale_price();
    $price = $product->get_regular_price();
    $simple = $product->is_type('simple');
    $variable = $product->is_type('variable');
    $external = $product->is_type('external');
    $sale_text = __('On Sale', 'ct-bones');
    $sale_percent = true;
    $final_price = '';
    $out_of_stock = codetot_is_product_out_of_stock($product);

    // Out of stock.
    if ($out_of_stock) {
      return;
    }

    if ($sale) {
      // For simple product.
      if ($simple || $external) {
        if ($sale_percent) {
          $final_price = (($price - $price_sale) / $price) * 100;
          $final_price = '-' . round($final_price) . '%';
        } elseif ($sale_text) {
          $final_price = $sale_text;
        }
      } elseif ($variable && $sale_text) {
        // For variable product.
        $final_price = $sale_text;
      }

      if (!$final_price) {
        return;
      }

      $classes[] = 'product__tag product__tag--onsale';
      $classes[] = 'sale-right';
      $classes[] = 'is-square';
    ?>
      <span class="<?php echo esc_attr(implode(' ', array_filter($classes))); ?>">
        <?php echo esc_html($final_price); ?>
      </span>
    <?php
    }
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

  public function after_single_product_container_open() {
    echo '<div class="single-product-sections">';
  }

  public function after_single_product_container_grid_open() {
    if ($this->enable_bottom_sidebar) :
      echo '<div class="grid single-product-main__grid">';
      echo '<div class="grid__col single-product-main__col single-product-main__col--left">';
    endif;
  }

  public function after_single_product_container_grid_close() {
    if ($this->enable_bottom_sidebar) :
      echo '</div>'; // Close .single-product-sections__col--left
      echo '<div class="grid__col single-product-main__col single-product-main__col--right">';
      dynamic_sidebar('bottom-product-sidebar');
      echo '</div>'; // Close .single-product-sections__col--right
      echo '</div>'; // Close .single-product-sections__grid
    endif;
  }

  public function after_single_product_container_close() {
    echo '</div>'; // Close .single-product-sections
  }
}

Codetot_Woocommerce_Layout_Product::instance();
