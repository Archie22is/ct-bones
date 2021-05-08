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
class Codetot_Woocommerce_Layout_Archive
{
  /**
   * Singleton instance
   *
   * @var Codetot_Woocommerce_Layout_Archive
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Woocommerce_Layout_Archive
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
    $this->remove_default_hooks();
    add_filter('woocommerce_pagination_args', array($this, 'change_woocommerce_arrow_pagination'));

    if (is_shop() || is_product_category()) :
      $this->build_wrapper();
    endif;

    add_action('woocommerce_before_shop_loop', array($this, 'sorting_open'), 12);
    add_action('woocommerce_before_shop_loop', array($this, 'sorting_close'), 31);
    add_action('woocommerce_before_shop_loop', array($this, 'product_grid_open'), 32);
    add_action('woocommerce_after_shop_loop', array($this, 'product_grid_close'), 10);

    add_action('wp', array($this, 'build_wrapper'));

    $this->build_product_column();
    $this->update_product_card_style();
  }

  public function remove_default_hooks()
  {
    // Move out header to outside of .main
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);
    remove_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10);
    remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
    remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
  }

  public function build_wrapper()
  {
    add_action('codetot_product_archive_after_page_block_main', array($this, 'archive_title'), 10);
    add_action('codetot_product_archive_after_page_block_main', array($this, 'top_product_category_content'), 20);
    add_action('codetot_after_header', array($this, 'breadcrumbs'), 10);
    add_action('codetot_after_header', array($this, 'page_block_open'), 50);
    add_action('woocommerce_after_shop_loop', array($this, 'bottom_product_category_content'), 10);
    add_action('codetot_before_sidebar', array($this, 'page_block_between'), 20);
    add_action('codetot_footer', array($this, 'page_block_close'), 90);
  }

  public function build_product_column()
  {
    add_filter('codetot_woocommerce_archive_loop_button', array($this, 'loop_product_add_to_cart_button_text'));

    add_action('woocommerce_before_shop_loop_item_title', array($this, 'loop_product_image_wrapper_open'), 20);
    add_action('woocommerce_before_shop_loop_item_title', array($this, 'print_out_of_stock_label'), 22);
    add_action('woocommerce_before_shop_loop_item_title', array($this, 'change_sale_flash'), 23);
    add_action('woocommerce_before_shop_loop_item_title', array($this, 'loop_product_link_open'), 30);
    // add_action('woocommerce_before_shop_loop_item_title', array($this, 'loop_product_hover_image'), 40);
    add_action('woocommerce_before_shop_loop_item_title', array($this, 'loop_product_image'), 50);
    add_action('woocommerce_before_shop_loop_item_title', array($this, 'loop_product_image_wrapper_close'), 90);
    add_action('woocommerce_before_shop_loop_item_title', array($this, 'loop_product_content_open'), 100);

    add_action('woocommerce_after_shop_loop_item_title', array($this, 'loop_product_rating'), 2);
    add_action('woocommerce_after_shop_loop_item_title', array($this, 'loop_product_meta_open'), 5);
    add_action('woocommerce_after_shop_loop_item_title', array($this, 'loop_product_add_to_cart_button'), 15);

    add_action('woocommerce_before_shop_loop_item_title', array($this, 'loop_product_link_close'), 60);

    add_action('woocommerce_shop_loop_item_title', array($this, 'add_template_loop_product_title'), 10);

    add_action('woocommerce_after_shop_loop_item', array($this, 'loop_product_meta_close'), 20);
    add_action('woocommerce_after_shop_loop_item', array($this, 'loop_product_content_close'), 50);
  }

  public function archive_title()
  {
    if (is_shop() || is_product_category()) {
      $current_object = get_queried_object();

      if (is_shop()) {
        $title = $current_object->label;
      } else {
        $title = $current_object->name;
      }

      the_block('page-header', array(
        'class' => 'page-header--no-container page-header--archive',
        'title' => $title
      ));
    }
  }

  public function display_product_category_content($field_name, $class) {
    $obj = get_queried_object();
    $sidebar_layout = 'no-sidebar';

    if ( is_shop() ) :
      $sidebar_layout = get_global_option('codetot_shop_layout') ?? 'sidebar-left';
    elseif( is_product_category() ) :
      $sidebar_layout = get_global_option('codetot_product_category_layout') ?? 'sidebar-left';
    endif;

    $content = get_field($field_name, 'product_cat_' . esc_attr($obj->term_id));

    if (!empty($content)) {
      ob_start();
      echo '<div class="wysiwyg message-block__content">';
      echo $content;
      echo '</div>';
      $html = ob_get_clean();

      $_class = $class;

      if ($sidebar_layout !== 'no-sidebar') {
        $_class .= ' message-block--no-container';
      }

      the_block('message-block', array(
        'class' => $_class,
        'content' => $html
      ));
    }
  }

  public function top_product_category_content() {
    if (is_product_category()) {
      $this->display_product_category_content('top_content', 'message-block--archive-top-content');
    }
  }

  public function bottom_product_category_content() {
    if (is_product_category()) {
      $this->display_product_category_content('bottom_content', 'message-block--archive-top-content');
    }
  }

  public function sorting_open()
  {
    echo '<div class="page-block__sorting">';
    echo '<div class="page-block__sorting-grid">';
  }

  public function sorting_close()
  {
    echo '</div>';
    echo '</div>';
  }

  public function loop_product_image_wrapper_open()
  {
    echo '<div class="product__inner">';
    echo '<div class="product__image-wrapper">';
  }

  public function loop_product_image_wrapper_close()
  {
    echo '</div>';
  }

  public function breadcrumbs() {
    if (is_shop() || is_product_category()) {
      the_block('breadcrumbs');
    }
  }

  public function page_block_open() {
    $class = 'page-block';

    if ( is_shop() ) :
      $class .= ' page-block--shop';
      $sidebar_layout = get_global_option('codetot_shop_layout') ?? 'sidebar-left';
    elseif( is_product_category() ) :
      $class .= ' page-block--product-category';
      $sidebar_layout = get_global_option('codetot_product_category_layout') ?? 'sidebar-left';
    endif;

    $class .= !empty($sidebar_layout) ? ' ' . esc_attr($sidebar_layout) : '';

    do_action('codetot_product_archive_before_page_block');

    if (is_shop() || is_product_category()) :
      echo '<div class="' . esc_attr($class) . '" data-block="page-block">';
      echo '<div class="container page-block__container">';
      the_block('page-block-mobile-trigger', array(
        'class' => 'has-icon',
        'button_icon' => codetot_svg('menu', false),
        'button_text' => esc_html__('Filter', 'woocommerce')
      ));
      echo '<div class="grid page-block__grid">';
      echo '<div class="grid__col page-block__col page-block__col--main">';
      do_action('codetot_product_archive_after_page_block_main');
    endif;
  }

  public function page_block_between() {
    if (is_shop() || is_product_category()) :
      echo '</div>'; // Close .page-block__col--main
      echo '<div class="grid__col page-block__col page-block__col--sidebar">';
    endif;
  }

  public function page_block_close() {
    if (is_shop() || is_product_category()) :
      echo '</div>'; // close .page-block__col--sidebar
      echo '</div>'; // close .page-block__grid
      echo '</div>'; // close .page-block__container
      echo '</div>'; // close .page-block--product-category
    endif;
  }

  public function product_grid_open()
  {
    echo '<div class="product-grid product-grid--archive">';
  }

  public function product_grid_close()
  {
    echo '</div>'; // close .product-grid
  }

  public function long_description_category()
  {
    $curent_obj_id = get_queried_object_id();
    $long_description = get_field('description_product', 'product_cat_' . $curent_obj_id);

    if (!empty($long_description)) {
      echo '<div class="page-block__footer">' . $long_description . '</div>';
    }
  }

  public function change_woocommerce_arrow_pagination($args)
  {
    $args['prev_text'] = esc_attr__('Previous');
    $args['next_text'] = esc_attr__('Next');
    return $args;
  }

  public function loop_product_link_open()
  {
    // open tag <a>.
    woocommerce_template_loop_product_link_open();
  }

  public function loop_product_link_close()
  {
    // close tag </a>.
    woocommerce_template_loop_product_link_close();
  }

  public function loop_product_content_open()
  {
    echo '<div class="product__content">';
  }

  public function loop_product_content_close()
  {
    echo '</div>';
    echo '</div>';
  }

  public function loop_product_rating()
  {
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

  public function loop_product_meta_open()
  {
    echo '<div class="product__meta">';
    echo '<div class="product__meta-inner">';
  }

  public function loop_product_meta_close()
  {
    echo '</div>';
    echo '</div>';
  }

  public function loop_product_add_to_cart_button_text($button) {
    $product_card_style = get_global_option('codetot_woocommerce_product_card_style');

    if (!in_array($product_card_style, array('2', '3'))) {
      return $button;
    }

    global $product;

    ob_start();
    printf('<a class="add-to-cart-icon button" href="%1$s">%2$s</a>',
      $product->get_permalink(),
      $product_card_style === 2 ? codetot_svg('cart', false) : apply_filters('woocommerce_product_add_to_cart_text', esc_html__('Add to cart', 'woocommerce'))
    );
    return ob_get_clean();
  }

  public function loop_product_add_to_cart_button()
  {
    global $product;
    $out_of_stock = codetot_is_product_out_of_stock($product);


    if (!$out_of_stock) {
      ob_start();
      woocommerce_template_loop_add_to_cart();
      $button = ob_get_clean();

      echo apply_filters('codetot_woocommerce_archive_loop_button', $button);

    } else {
      echo '<span class="button product__button button--disabled">' . __('Out of stock', 'woocommerce') . '</span>';
    }
  }

  public function loop_product_hover_image()
  {
    global $product;
    $gallery = $product->get_gallery_image_ids();
    // Hover image.
    if (!empty($gallery) && apply_filters('codetot_product_card_display_hover_image', true)) : ?>
      <noscript>
        <?php
        ob_start();
        echo wp_get_attachment_image($gallery[0], 'medium', false, array(
          'class' => 'product__image-hover lazyload'
        ));
        $image_html = ob_get_clean();
        $image_html = str_replace('srcset="', 'data-sizes="auto" data-srcset="', $image_html);
        echo $image_html;
        ?>
      </noscript>
      <div class="product__image-hover-wrapper js-image-hover"></div>
    <?php
    endif;
  }

  public function loop_product_image()
  {
    global $product;

    if (!$product) {
      return '';
    }

    $size = 'medium';
    $img_id = $product->get_image_id();
    $img_alt = codetot_image_alt($img_id, esc_attr__('Product Image', 'ct-bones'));
    $img_origin = wp_get_attachment_image_src($img_id, $size);
    $img_srcset = wp_get_attachment_image_srcset($img_id, $size);

    if (!$img_origin) {
      $img_ori = '';
    } else {
      $img_ori = $img_origin[0];
    }

    $image_attr = array(
      'alt' => $img_alt,
      'data-src' => $img_ori,
      'data-srcset' => $img_srcset,
      'data-sizes' => 'auto',
      'class' => 'wp-post-image attachment-' . $size . ' size-' . $size . ' product__image lazyload',
    );

    echo $product->get_image($size, $image_attr);
  }

  public function add_template_loop_product_title()
  {
    if (is_product_category() || is_archive()) {
      $title_tag_open = '<h2 class="woocommerce-loop-product__title product__title">';
      $title_tag_close = '</h2>';
    } else {
      $title_tag_open = '<h3 class="woocommerce-loop-product__title product__title">';
      $title_tag_close = '</h3>';
    }
    echo $title_tag_open;
    woocommerce_template_loop_product_link_open();
    echo wp_trim_words(get_the_title(), 12, '...');
    woocommerce_template_loop_product_link_close();
    echo $title_tag_close;
  }

  public function change_sale_flash()
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
    $sale_text = __('On Sale', 'woocommerce');
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

  public function print_out_of_stock_label()
  {
    global $product;
    $out_of_stock = codetot_is_product_out_of_stock($product);

    if (!$out_of_stock || $product->backorders_allowed()) {
      return;
    }
    ?>
    <span class="product__tag product__tag--out-of-stock"><?php _e('Out of stock', 'woocommerce'); ?></span>
<?php
  }

  public function update_product_card_style()
  {
    $product_card_style = get_global_option('codetot_woocommerce_product_card_style') ?? '';

    switch ($product_card_style):

      case '2':
      case '3':
        add_action('woocommerce_before_shop_loop_item_title', array($this, 'loop_product_add_to_cart_button'), 24);
        remove_action('woocommerce_after_shop_loop_item_title', array($this, 'change_sale_flash'), 11);
        remove_action('woocommerce_after_shop_loop_item_title', array($this, 'loop_product_add_to_cart_button'), 15);
        break;

    endswitch;
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

Codetot_Woocommerce_Layout_Archive::instance();
