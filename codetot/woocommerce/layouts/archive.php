<?php
// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @link       https://codetot.com
 * @since      1.0.0
 * @package    Codetot_Woocommerce
 * @subpackage Codetot_Woocommerce/includes/layout
 * @author     CODE TOT JSC <khoi@codetot.com>
 */
class Codetot_Woocommerce_Layout_Archive {

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
	final public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Class constructor
	 */
	private function __construct() {
		$this->remove_default_hooks();
		add_filter( 'woocommerce_pagination_args', array( $this, 'change_woocommerce_arrow_pagination' ) );

		if ( is_shop() || is_product_category() || is_product_tag() ) :
			$this->build_wrapper();
	  endif;

		$this->shop_sidebar             = codetot_get_theme_mod( 'shop_layout', 'woocommerce' ) ?? 'sidebar-left';
		$this->product_category_sidebar = codetot_get_theme_mod( 'product_category_layout', 'woocommerce' ) ?? 'sidebar-left';

		add_action( 'woocommerce_before_shop_loop', array( $this, 'sorting_open' ), 12 );
		add_action( 'woocommerce_before_shop_loop', array( $this, 'sorting_close' ), 31 );
		add_action( 'woocommerce_before_shop_loop', array( $this, 'product_grid_open' ), 32 );
		add_action( 'woocommerce_after_shop_loop', array( $this, 'product_grid_close' ), 10 );

		add_action( 'wp', array( $this, 'build_wrapper' ) );

		$this->build_product_column();
	}

	public function remove_default_hooks() {
		// Move out header to outside of .main
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	}

	public function build_wrapper() {
		add_action( 'codetot_product_archive_after_page_block_main', array( $this, 'archive_title' ), 10 );
		add_action( 'codetot_product_archive_after_page_block_main', array( $this, 'top_product_category_content' ), 20 );
		add_action( 'codetot_product_archive_after_page_block_main', 'codetot_archive_product_top_widget', 25 );
		add_action( 'codetot_after_header', array( $this, 'breadcrumbs' ), 10 );
		add_action( 'codetot_after_header', array( $this, 'page_block_open' ), 50 );
		add_action( 'woocommerce_after_shop_loop', array( $this, 'bottom_product_category_content' ), 10 );
		add_action( 'codetot_before_sidebar', array( $this, 'page_block_between' ), 20 );
		add_action( 'codetot_footer', array( $this, 'page_block_close' ), 90 );
	}

	public function build_product_column() {
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'loop_product_image_wrapper_open' ), 20 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'print_out_of_stock_label' ), 22 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'loop_product_link_open' ), 30 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'loop_product_hover_image' ), 40 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'loop_product_image' ), 50 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'loop_product_image_wrapper_close' ), 90 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'loop_product_content_open' ), 100 );
		add_action( 'woocommerce_after_shop_loop_item_title', 'codetot_archive_product_price_html', 10 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'loop_product_link_close' ), 60 );

		add_action( 'woocommerce_shop_loop_item_title', array( $this, 'add_template_loop_product_title' ), 10 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'loop_product_content_close' ), 50 );

		add_action( 'codetot_archive_product_after_container', 'codetot_archive_product_mobile_filter_button', 5 );
	}

	public function archive_title() {
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			$current_object = get_queried_object();

			if ( is_shop() ) {
				$title = $current_object->label;
			} else {
				$title = $current_object->name;
			}

			the_block(
				'page-header',
				array(
					'class' => 'page-header--no-container page-header--archive',
					'title' => $title,
				)
			);
		}
	}

	public function display_product_category_content( $field_name, $class ) {
		$obj            = get_queried_object();
		$sidebar_layout = 'no-sidebar';

		if ( is_shop() ) :
			$sidebar_layout = $this->shop_sidebar;
	  elseif ( is_product_category() || is_product_tag() ) :
		  $sidebar_layout = $this->product_category_sidebar;
	  endif;

	  $content = function_exists( 'get_field' ) && get_field( $field_name, 'product_cat_' . esc_attr( $obj->term_id ) );

	  if ( ! empty( $content ) ) {
		  ob_start();
		  echo '<div class="wysiwyg message-block__content">';
		  echo $content;
		  echo '</div>';
		  $html = ob_get_clean();

		  $_class = $class;

		  if ( $sidebar_layout !== 'no-sidebar' ) {
			  $_class .= ' message-block--no-container';
		  }

		  the_block(
			'message-block',
			array(
				'class'   => $_class,
				'content' => $html,
			)
        );
	  }
	}

	public function top_product_category_content() {
		if ( is_product_category() ) {
			$this->display_product_category_content( 'top_content', 'message-block--archive-top-content' );
		}
	}

	public function bottom_product_category_content() {
		if ( is_product_category() ) {
			$this->display_product_category_content( 'bottom_content', 'message-block--archive-bottom-content' );
		}
	}

	public function sorting_open() {
		echo '<div class="page-block__sorting">';
		echo '<div class="page-block__sorting-grid">';
	}

	public function sorting_close() {
		echo '</div>';
		echo '</div>';
	}

	public function loop_product_image_wrapper_open() {
		echo '<div class="product__inner js-product-inner">';
		echo '<div class="product__image-wrapper">';
	}

	public function loop_product_image_wrapper_close() {
		echo '</div>';
	}

	public function breadcrumbs() {
		if ( is_shop() || is_product_category() || is_product_tag() ) {
			woocommerce_breadcrumb();
		}
	}

	public function page_block_open() {
		$class          = 'page-block';
		$sidebar_layout = '';

		if ( is_shop() ) :
			$class         .= ' page-block--shop';
			$sidebar_layout = $this->shop_sidebar;
	  elseif ( is_product_category() || is_product_tag() ) :
		  $class         .= ' page-block--product-category';
		  $sidebar_layout = $this->product_category_sidebar;
	  endif;

	  $class .= ! empty( $sidebar_layout ) ? ' ' . esc_attr( $sidebar_layout ) : '';

	  do_action( 'codetot_product_archive_before_page_block' );

	  if ( is_shop() || is_product_category() || is_product_tag() ) :
		  echo '<div class="' . esc_attr( $class ) . '" data-block="page-block">';
		  echo '<div class="container page-block__container">';
		  do_action( 'codetot_archive_product_after_container' );

		  if ( in_array( $sidebar_layout, array( 'sidebar-left', 'sidebar-right' ) ) ) :
			  echo '<div class="grid page-block__grid">';
			  echo '<div class="grid__col page-block__col page-block__col--main">';
		endif;

		  do_action( 'codetot_product_archive_after_page_block_main' );
	  endif;
	}

	public function page_block_between() {
		if ( is_shop() ) :
			$sidebar_layout = $this->shop_sidebar;
	  elseif ( is_product_category() || is_product_tag() ) :
		  $sidebar_layout = $this->product_category_sidebar;
	  endif;

	  if ( is_shop() || is_product_category() || is_product_tag() ) :
		  if ( in_array( $sidebar_layout, array( 'sidebar-left', 'sidebar-right' ) ) ) :
			  echo '</div>'; // Close .page-block__col--main
			  echo '<div class="grid__col page-block__col page-block__col--sidebar">';
		endif;
	  endif;
	}

	public function page_block_close() {
		if ( is_shop() ) :
			$sidebar_layout = $this->shop_sidebar;
	  elseif ( is_product_category() || is_product_tag() ) :
		  $sidebar_layout = $this->product_category_sidebar;
	  endif;

	  if ( is_shop() || is_product_category() || is_product_tag() ) :
		  if ( in_array( $sidebar_layout, array( 'sidebar-left', 'sidebar-right' ) ) ) :
			  echo '</div>'; // close .page-block__col--sidebar
			  echo '</div>'; // close .page-block__grid
		endif;
		  echo '</div>'; // close .page-block__container
		  echo '</div>'; // close .page-block--product-category
	  endif;
	}

	public function product_grid_open() {
		echo '<div class="product-grid product-grid--archive">';
	}

	public function product_grid_close() {
		echo '</div>'; // close .product-grid
	}

	public function change_woocommerce_arrow_pagination( $args ) {
		$args['prev_text'] = esc_attr__( 'Previous' );
		$args['next_text'] = esc_attr__( 'Next' );
		return $args;
	}

	public function loop_product_link_open() {
		// open tag <a>.
		woocommerce_template_loop_product_link_open();
	}

	public function loop_product_link_close() {
		// close tag </a>.
		woocommerce_template_loop_product_link_close();
	}

	public function loop_product_content_open() {
		echo '<div class="product__content">';
	}

	public function loop_product_content_close() {
		echo '</div>';
		echo '</div>';
	}

	public function loop_product_meta_open() {
		echo '<div class="product__meta">';
		echo '<div class="product__meta-inner">';
	}

	public function loop_product_meta_close() {
		echo '</div>';
		echo '</div>';
	}

	public function loop_product_add_to_cart_button() {
		global $product;
		$out_of_stock = codetot_is_product_out_of_stock( $product );


		if ( ! $out_of_stock ) {
			ob_start();
			woocommerce_template_loop_add_to_cart();
			$button = ob_get_clean();

			echo apply_filters( 'codetot_woocommerce_archive_loop_button', $button );

		} else {
			echo '<span class="button product__button button--disabled">' . __( 'Out of stock', 'woocommerce' ) . '</span>';
		}
	}

	public function loop_product_hover_image() {
		global $product;
		$thumbnail_id = (int) $product->get_image_id();
		$gallery      = $product->get_gallery_image_ids();
		// Hover image.
		if ( ! empty( $gallery ) && apply_filters( 'codetot_product_card_display_hover_image', true ) ) :
			$hover_image_id = (int) $gallery[0];

			// If first image from gallery match default image, using second image
			if ( $gallery[0] === $thumbnail_id && count( $gallery ) > 1 ) {
				$hover_image_id = $gallery[1];
			}

			$hover_image_url = wp_get_attachment_image_url( $hover_image_id, 'large', false );
			if ( ! empty( $hover_image_url ) ) :
				?>
	  <div class="product__image-hover js-hover-image" data-image-url="<?php echo $hover_image_url; ?>"></div>
		  <?php endif; ?>
			<?php
	  endif;
	}

	public function loop_product_image() {
		global $product;

		if ( ! $product ) {
			return '';
		}

		$img_id = ! empty( $product->get_image_id() ) ? $product->get_image_id() : get_option( 'woocommerce_placeholder_image', 0 );

		ob_start();
		echo wp_get_attachment_image(
			$img_id,
			'medium_large',
			false,
			array(
				'class' => 'wp-post-image lazyload image__img',
				'alt'   => '',
			)
		);
		$image_html = ob_get_clean();
		$image_html = str_replace( ' srcset="', ' srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-sizes="auto" data-srcset="', $image_html );
		$image_html = str_replace( ' loading="lazy"', '', $image_html );

		echo '<figure class="product__image">';
		echo $image_html;
		echo '</figure>';
	}

	public function add_template_loop_product_title() {
		if ( is_product_category() || is_archive() || is_product_tag() ) {
			$title_tag_open  = '<h2 class="woocommerce-loop-product__title product__title">';
			$title_tag_close = '</h2>';
		} else {
			$title_tag_open  = '<h3 class="woocommerce-loop-product__title product__title">';
			$title_tag_close = '</h3>';
		}
		echo $title_tag_open;
		woocommerce_template_loop_product_link_open();
		echo wp_trim_words( get_the_title(), 12, '...' );
		woocommerce_template_loop_product_link_close();
		echo $title_tag_close;
	}

	public function print_out_of_stock_label() {
		global $product;
		$out_of_stock = codetot_is_product_out_of_stock( $product );

		if ( ! $out_of_stock || $product->backorders_allowed() ) {
			return;
		}
		?>
	<span class="product__tag product__tag--out-of-stock"><?php _e( 'Out of stock', 'woocommerce' ); ?></span>
		<?php
	}

	public function print_errors() {
		if ( is_singular( 'product' ) ) {
			the_block(
				'message-block',
				array(
					'content' => wc_print_notices( true ),
				)
			);
		}
	}
}

function codetot_archive_product_top_widget() {
	$shop_sidebar_layout             = codetot_get_theme_mod( 'shop_layout', 'woocommerce' ) ?? 'sidebar-left';
	$product_category_sidebar_layout = codetot_get_theme_mod( 'product_category_layout', 'woocommerce' ) ?? 'sidebar-left';
	$sidebar_id                      = is_shop() ? 'shop-sidebar' : '';
	$sidebar_id                      = is_product_category() || is_product_tag() ? 'product-category-sidebar' : '';

	ob_start();
	echo '<div class="page-block__top-sidebar">';
	dynamic_sidebar( $sidebar_id );
	echo '</div>';
	$sidebar_html = ob_get_clean();

	if ( is_shop() && $shop_sidebar_layout === 'top-sidebar' ) :
		if ( ! empty( $sidebar_html ) ) :
			echo $sidebar_html;
		  endif;
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
  endif;

	if ( ( is_product_category() || is_product_tag() ) && $product_category_sidebar_layout === 'top-sidebar' ) :
		if ( ! empty( $sidebar_html ) ) :
			echo $sidebar_html;
		  endif;
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
  endif;
}

function codetot_archive_product_mobile_filter_button() {
	the_block(
		'page-block-mobile-trigger',
		array(
			'class'       => 'has-icon',
			'button_icon' => codetot_svg( 'menu', false ),
			'button_text' => esc_html__( 'Filter', 'woocommerce' ),
		)
	);
}

Codetot_Woocommerce_Layout_Archive::instance();
