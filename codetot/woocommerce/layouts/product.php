<?php
// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Codetot_Woocommerce_Layout_Product {

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
	final public static function instance() {
		if ( is_null( self::$instance ) ) {
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
	private function __construct() {
		$this->sidebar_layout        = codetot_get_theme_mod( 'product_layout', 'woocommerce' ) ?? 'no-sidebar';
		$this->enable_sidebar        = $this->sidebar_layout !== 'no-sidebar';
		$this->enable_container      = codetot_get_theme_mod( 'single_product_sections_enable_container', 'woocommerce' ) ?? true;
		$this->enable_top_sidebar    = codetot_get_theme_mod( 'single_product_enable_top_widget', 'woocommerce' ) ?? true;
		$this->enable_bottom_sidebar = codetot_get_theme_mod( 'single_product_enable_bottom_widget', 'woocommerce' ) ?? true;

		$this->generate_wrapper();

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_single_product_assets' ) );

		// Swap position price and rating star.
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );

		add_action( 'woocommerce_before_main_content', array( $this, 'print_errors' ), 11 );

		add_filter( 'woocommerce_get_stock_html', array( $this, 'update_stock_text' ), 10, 2 );

		remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

		// Remove default sections
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );

		remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

		add_action( 'woocommerce_before_single_product_summary', array( $this, 'print_errors' ), 5 );
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'single_product_top_open' ), 12 ); // .grid

		// Product Gallery column
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'single_product_column_open' ), 15 ); // .grid__col
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'change_sale_flash_in_gallery' ), 16 );

		add_action( 'woocommerce_product_thumbnails', 'codetot_render_single_product_gallery_nav', 20 );
		add_action( 'woocommerce_before_single_product_summary', 'codetot_render_bottom_product_gallery', 40 );
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'single_product_column_close' ), 50 ); // /.grid__col

		// Column: Top Sidebar Widget
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'single_product_column_open_secondary' ), 60 ); // .grid__col

		// Product Title
		add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_title_open' ), 1 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_title_close' ), 15 );

		add_action( 'woocommerce_after_single_product_summary', array( $this, 'single_product_column_close' ), 4 ); // .grid__col
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'single_product_top_close' ), 5 ); // ./grid

		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
		add_action( 'woocommerce_single_product_summary', 'codetot_woocommerce_single_meta', 35 );

		// Product meta
		add_action( 'woocommerce_product_meta_start', 'codetot_render_product_sku_meta', 5 );
		add_action( 'woocommerce_product_meta_start', 'codetot_render_product_weight_meta', 10 );
		add_action( 'woocommerce_product_meta_start', 'codetot_render_product_stock_meta', 15 );
		add_action( 'woocommerce_product_meta_start', 'codetot_render_product_dimesion_meta', 20 );
		add_action( 'woocommerce_product_meta_start', 'codetot_render_product_categories_meta', 25 );

		add_filter( 'woocommerce_product_tabs', array( $this, 'woo_custom_description_tab' ), 98 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

		// Output bottom sections
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'after_single_product_container_open' ), 6 );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'after_single_product_container_grid_open' ), 40 );
		add_action( 'codetot_single_product_left_bottom_sections', 'woocommerce_output_product_data_tabs', 5 );
		add_action( 'codetot_single_product_sections', 'codetot_render_related_products', 10 );
		add_action( 'codetot_single_product_sections', 'codetot_render_cross_sell_products', 20 );
		add_action( 'codetot_single_product_sections', 'codetot_render_upsell_sections', 30 );

		add_action( 'woocommerce_after_single_product_summary', array( $this, 'after_single_product_container_grid_close' ), 100 );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'after_single_product_container_close' ), 110 );

		add_filter(
			'woocommerce_product_thumbnails_columns',
			function() {
				$columns = codetot_get_theme_mod( 'single_product_gallery_thumbnail_column', 'woocommerce' ) ?? '4-col';

				return (int) str_replace( '-col', '', $columns );
			}
		);
	}

	public function generate_wrapper() {
		if ( ! is_singular( 'product' ) ) {
			return;
		}

		add_action( 'woocommerce_before_single_product', array( $this, 'breadcrumbs' ), 5 );

		if ( $this->sidebar_layout !== 'no-sidebar' ) {
			add_action( 'woocommerce_before_single_product', array( $this, 'page_block_open' ), 10 );
			add_action( 'codetot_before_sidebar', array( $this, 'page_block_between' ), 1 );
			add_action( 'codetot_footer', array( $this, 'page_block_close' ), 100 );
		}

		if ( $this->enable_top_sidebar ) {
			add_action( 'woocommerce_before_single_product_summary', array( $this, 'top_product_sidebar_open' ), 75 );
			add_action( 'woocommerce_after_single_product_summary', array( $this, 'top_product_sidebar_close' ), 2 );
		}
	}

	public function breadcrumbs() {
		if ( is_singular( 'product' ) ) {
			woocommerce_breadcrumb();
		}
	}

	public function page_block_open() {
		$class  = 'page-block page-block--product';
		$class .= ' ' . esc_attr( $this->sidebar_layout );

		echo '<div class="' . esc_attr( $class ) . '">';
		echo '<div class="container page-block__container">';
		if ( $this->sidebar_layout !== 'no-sidebar' ) :
			echo '<div class="grid page-block__grid">';
			echo '<div class="grid__col page-block__col page-block__col--main">';
	  endif;
	}

	/**
	 * Render between columns markup
	 *
	 * @return void
	 */
	public function page_block_between() {
		if ( is_singular( 'product' ) && $this->sidebar_layout !== 'no-sidebar' ) :
			// echo '</div>'; // Close .page-block__col--main
			echo '<div class="grid__col page-block__col page-block__col--sidebar">';
	  endif;
	}

	/**
	 * Render close tags
	 *
	 * @return void
	 */
	public function page_block_close() {
		if ( is_singular( 'product' ) ) :
			if ( $this->sidebar_layout !== 'no-sidebar' ) :
				echo '</div>'; // close .page-block__col--sidebar
				echo '</div>'; // close .page-block__grid
		  endif;
			echo '</div>'; // close .page-block__container
			echo '</div>'; // close .page-block--product-category
	  endif;
	}

	/**
	 * Render optn content single product
	 *
	 * @return void
	 */
	public function open_content_single_product() {
		echo '<div class="single-product-main">';
		if ( ! $this->enable_sidebar ) :
			echo '<div class="container single-product-main__container">';
	  endif;
	}

	/**
	 * Render close content single product
	 *
	 * @return void
	 */
	public function close_content_single_product() {
		if ( ! $this->enable_sidebar ) :
			echo '</div>';
	  endif;
		echo '</div>';
	}

	/**
	 * Update product tabs
	 *
	 * @param array $tabs
	 * @return void
	 */
	public function woo_custom_description_tab( $tabs ) {
		if ( ! empty( $tabs['description'] ) ) {
			$tabs['description']['callback'] = array( $this, 'woo_custom_description_tab_content' );
		}

		if ( ! empty( $tabs['additional_information'] ) ) {
			unset( $tabs['additional_information'] );
		}

		return $tabs;
	}

	/**
	 * Render custom description tab content
	 *
	 * @return void
	 */
	public function woo_custom_description_tab_content() {
		the_block(
			'product-description',
			array(
				'content' => apply_filters( 'the_content', get_the_content() ),
			)
		);
	}

	/**
	 * Render single product css and js
	 *
	 * @return void
	 */
	public function enqueue_single_product_assets() {
		if ( is_singular( 'product' ) ) {
			wp_enqueue_style( 'fancybox-style', '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css', null, '3.5.7', 'all' );
			wp_enqueue_script( 'fancybox-script', '//cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js', array( 'jquery' ), '3.5.7', true );
		}
	}

	/**
	 * Render single product open tag
	 *
	 * @return void
	 */
	public function single_product_top_open() {
		echo '<div class="single-product-top">';
		if ( ! $this->enable_sidebar ) :
			echo '<div class="container single-product-top__container">';
	  endif;
		echo '<div class="single-product-top__grid">';
	}

	/**
	 * Render single product close tag
	 *
	 * @return void
	 */
	public function single_product_top_close() {
		echo '</div>'; // Close .single-product-top__grid
		if ( ! $this->enable_sidebar ) :
			echo '</div>'; // Close .single-product-top__container
	  endif;
		echo '</div>'; // Close .single-product-top
	}

	/**
	 * Render single product open column
	 *
	 * @return void
	 */
	public function single_product_column_open() {
		echo '<div class="single-product-top__col">';
		echo '<div class="single-product-top__inner">';
	}

	/**
	 * Render single product close column
	 *
	 * @return void
	 */
	public function single_product_column_close() {
		echo '</div>'; // Close .single-product-top__col
		echo '</div>'; // Close .single-product-top__inner
	}

	/**
	 * Render single product secondary open column
	 *
	 * @return void
	 */
	public function single_product_column_open_secondary() {
		echo '<div class="single-product-top__col single-product-top__col--sidebar">';
	}

	/**
	 * Render single product secondary close column
	 *
	 * @return void
	 */
	public function single_product_title_open() {
		echo '<div class="single-product-top__header">';
	}

	/**
	 * Close product title tag
	 *
	 * @return void
	 */
	public function single_product_title_close() {
		echo '</div>';
	}

	/**
	 * Render top block single product - open column
	 *
	 * @return void
	 */
	public function top_product_sidebar_open() {
		echo '<div class="single-product-top__main">';
		if ( $this->enable_top_sidebar ) :
			echo '<div class="grid single-product-top__main-grid">';
			echo '<div class="grid__col single-product-top__main-col single-product-top__main-col--left">';
	  endif;
	}

	/**
	 * Render top block single product - close column
	 *
	 * @return void
	 */
	public function top_product_sidebar_close() {
		if ( $this->enable_top_sidebar ) :
			echo '</div>'; // Close .single-product-top__main-col--left

			echo '<div class="grid__col single-product-top__main-col single-product-top__main-col--right">';
			dynamic_sidebar( 'top-product-sidebar' );
			echo '</div>'; // Close .single-product-top__main-col--right

			echo '</div>'; // Close .single-product-top__main-grid
	  endif;
		echo '</div>'; // Close .single-product-top__main
	}

	public function update_stock_text( $html, $product ) {
		$availability = $product->get_availability();

		if ( isset( $availability['class'] ) && 'in-stock' === $availability['class'] ) {
			return '';
		}

		return $html;
	}

	/**
	 * Update product sale tag in gallery block
	 *
	 * @return void
	 */
	public function change_sale_flash_in_gallery() {
		global $product;

		$final_price = codetot_get_price_discount_percentage( $product, 'percentage' );
		$classes     = array( 'product__tag', 'single-product-top__sale-tag' );

		if ( ! empty( $final_price ) ) : ?>
			<span class="<?php echo esc_attr( implode( ' ', array_filter( $classes ) ) ); ?>">
					<?php echo esc_html( $final_price ); ?>
			</span>
		<?php endif;
	}

	/**
	 * Print errors
	 *
	 * @return void
	 */
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

	/**
	 * After single product open column
	 *
	 * @return void
	 */
	public function after_single_product_container_open() {
		echo '<div class="single-product-bottom">';

		if ( ! $this->enable_sidebar && $this->enable_container ) :
			echo '<div class="container single-product-bottom__container">';
	  endif;

		if ( $this->enable_bottom_sidebar ) :
			echo '<div class="grid single-product-bottom__grid">';
	  endif;
	}

	/**
	 * After single product grid column open
	 *
	 * @return void
	 */
	public function after_single_product_container_grid_open() {
		if ( $this->enable_bottom_sidebar ) :
			echo '<div class="grid__col single-product-bottom__col single-product-bottom__col--left">';
	  endif;

		do_action( 'codetot_single_product_left_bottom_sections' );
	}

	/**
	 * After single product grid column close
	 *
	 * @return void
	 */
	public function after_single_product_container_grid_close() {
		if ( $this->enable_bottom_sidebar ) :
			echo '</div>'; // Close .single-product-bottom__col--left
			echo '<div class="grid__col single-product-bottom__col single-product-bottom__col--right">';
			dynamic_sidebar( 'bottom-product-sidebar' );
			echo '</div>'; // Close .single-product-bottom__col--right
	  endif;
	}

	/**
	 * Render single product container if has sidebar
	 *
	 * @return void
	 */
	public function after_single_product_container_close() {
		if ( $this->enable_bottom_sidebar ) : ?>
			</div><!-- Close .single-product-bottom__grid -->
	  	<?php endif; ?>
		<?php if ( ! $this->enable_sidebar ) : ?>
			</div><!-- Close .single-product-bottom__container -->
	  	<?php endif; ?>

		</div><!-- Close .single-product-bottom -->

		<?php do_action( 'codetot_single_product_sections' );
	}

	// Remove default <a></a> link in product gallery
	public function product_image_thumbnail_html( $html ) {
		return preg_replace( '!<(a|/a).*?>!', '', $html );
	}
}

/**
 * Render bottom product gallery
 *
 * @return void
 */
function codetot_render_bottom_product_gallery() {
	global $product;

	$thumbnail_type          = codetot_get_theme_mod( 'single_product_gallery_thumbnail_style', 'woocommerce' ) ?? 'all';
	$enable_view_more_button = $thumbnail_type === 'popup';
	$columns                 = (int) apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
	$attachment_ids          = $product->get_gallery_image_ids();
	$image_size              = 'full';

	if ( count( $attachment_ids ) > $columns && $enable_view_more_button ) {
		$more_count = count( $attachment_ids ) - (int) $columns;

		$attachment_ids  = array_slice( $attachment_ids, $columns );
		$first_image     = ! empty( $attachment_ids[0] ) ? wp_get_attachment_image_src( $attachment_ids[0], $image_size ) : '';
		$first_image_url = ! empty( $first_image ) ? $first_image[0] : null;

		if ( ! empty( $first_image_url ) ) :

			$icon_svg = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>';

			echo '<div class="align-c mt-05 mb-05 product-gallery__bottom">';
			the_block(
				'button',
				array(
					'button'    => sprintf( _n( 'View more %s images', 'View more %s images', 'ct-bones', $more_count ), $more_count ),
					'type'      => 'outline-primary',
					'icon_html' => $icon_svg,
					'class'     => 'product-gallery__button',
					'attr'      => ' data-fancybox="gallery"',
					'url'       => $first_image_url,
				)
			);
			echo '</div>';

			$attachment_ids = array_slice( $attachment_ids, 1 );
			foreach ( $attachment_ids as $attachment_id ) {
				  $attachment_image = wp_get_attachment_image_src( $attachment_id, $image_size );

				printf(
					'<a class="product-gallery__item" data-fancybox="gallery" href="%1$s"></a>',
					$attachment_image[0]
				);
			}

	  endif;
	}
}

/**
 * Render product gallery nav
 *
 * @return void
 */
function codetot_render_single_product_gallery_nav() {
	the_block( 'product-gallery-nav' );
}

/**
 * Render related products section
 *
 * @param string $class
 * @return void
 */
function codetot_render_related_products( $class = '' ) {
	if ( ! is_singular( 'product' ) ) {
		return;
	}

	global $product;
	$columns             = codetot_get_theme_mod( 'single_product_related_products_column', 'woocommerce' ) ?? '4-col';
	$columns             = str_replace( '-col', '', $columns );
	$enable_slider       = codetot_get_theme_mod( 'single_product_related_products_enable_slider', 'woocommerce' ) ?? true;
	$enable_container    = codetot_get_theme_mod( 'single_product_sections_enable_container', 'woocommerce' ) ?? true;
	$related_product_ids = wc_get_related_products( $product->get_id() );

	if ( empty( $related_product_ids ) ) {
		return '';
	}

	$post_args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => apply_filters( 'codetot_related_products_number', $columns ),
		'post__in'       => $related_product_ids,
	);

	$_class  = 'section product-grid--related-products';
	$_class .= ! $enable_container ? ' default-section--no-container' : '';
	$_class .= ! empty( $class ) ? ' ' . esc_html( $class ) : '';

	$post_query = new WP_Query( $post_args );

	if ( $columns !== 'hide' ) :

		the_block(
			'product-grid',
			array(
				'loop_args'     => array(
					'name' => 'related_products',
				),
				'class'         => $_class,
				'enable_slider' => $enable_slider,
				'title'         => apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'woocommerce' ) ),
				'query'         => $post_query,
				'columns'       => $columns,
			)
		);

  endif;
}

/**
 * Render cross-sell products section
 *
 * @param string $class
 * @return void
 */
function codetot_render_cross_sell_products( $class = '' ) {
	if ( ! is_singular( 'product' ) ) {
		return;
	}

	global $post;

	$cross_sell_product_ids = get_post_meta( $post->ID, '_crosssell_ids', true );
	$columns                = codetot_get_theme_mod( 'single_product_cross_sell_column ', 'woocommerce' ) ?? '4-col';
	$columns                = str_replace( '-col', '', $columns );
	$enable_slider          = codetot_get_theme_mod( 'single_product_cross_sell_enable_slider', 'woocommerce' ) ?? true;
	$enable_container       = codetot_get_theme_mod( 'single_product_sections_enable_container', 'woocommerce' ) ?? true;

	if ( empty( $cross_sell_product_ids ) ) {
		return '';
	}

	$post_args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => apply_filters( 'codetot_cross_products_number', $columns ),
		'post__in'       => $cross_sell_product_ids,
	);

	$_class  = 'section product-grid--cross-sell-products';
	$_class .= ! $enable_container ? ' default-section--no-container' : '';
	$_class .= ! empty( $class ) ? ' ' . esc_html( $class ) : '';

	$post_query = new WP_Query( $post_args );

	if ( $columns !== 'hide' ) :
		the_block(
			'product-grid',
			array(
				'loop_args'     => array(
					'name' => 'cross_sell_products',
				),
				'class'         => $_class,
				'enable_slider' => $enable_slider,
				'title'         => apply_filters( 'woocommerce_product_cross_sells_products_heading', __( 'You may be interested in&hellip;', 'woocommerce' ) ),
				'query'         => $post_query,
				'columns'       => $columns,
			)
		);
  endif;
}

/**
 * Render upsell products section
 *
 * @param string $class
 * @return void
 */
function codetot_render_upsell_sections( $class = '' ) {
	if ( ! is_singular( 'product' ) ) {
		return;
	}

	$columns          = codetot_get_theme_mod( 'single_product_upsell_column', 'woocommerce' ) ?? '4-col';
	$columns          = str_replace( '-col', '', $columns );
	$enable_slider    = codetot_get_theme_mod( 'single_product_upsell_enable_slider', 'woocommerce' ) ?? true;
	$enable_container = codetot_get_theme_mod( 'single_product_sections_enable_container', 'woocommerce' ) ?? true;

	$upsell_products = codetot_get_upsell_products( $columns, $columns );

	if ( empty( $upsell_products ) ) {
		return;
	}

	$_class  = 'section product-grid--upsells';
	$_class .= ! $enable_container ? ' default-section--no-container' : '';
	$_class .= ! empty( $class ) ? ' ' . esc_html( $class ) : '';

	if ( $columns !== 'hide' ) :
		the_block(
			'product-grid',
			array(
				'loop_args'     => array(
					'name' => 'upsell_products',
				),
				'class'         => $_class,
				'enable_slider' => $enable_slider,
				'title'         => apply_filters( 'woocommerce_product_upsells_products_heading', __( 'You may also like&hellip;', 'woocommerce' ) ),
				'list'          => $upsell_products,
				'columns'       => $columns,
			)
		);
  endif;
}

/**
 * Render product meta
 *
 * @return void
 */
function codetot_woocommerce_single_meta() {
	echo '<div class="mt-05 single-product-meta">';
	global $product;
	do_action( 'woocommerce_product_meta_start' );
	echo wc_get_product_tag_list( $product->get_id(), ', ', '<div class="single-product-tag tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</div>' );
	do_action( 'woocommerce_product_meta_end' );
	echo '</div>';
}

/**
 * Render sku product meta
 *
 * @return void
 */
function codetot_render_product_sku_meta() {
	global $product;

	if ( ( function_exists( 'wc_product_sku_enabled' ) && wc_product_sku_enabled() ) && ! empty( $product->get_sku() ) ) :
		printf(
			'<p class="product-meta product-meta--sku"><span class="product-meta__label">%s:</span> <span class="product-meta__value">%s</span></p>',
			str_replace( ':', '', esc_html__( 'SKU: ', 'woocommerce' ) ),
			$product->get_sku()
		);
  endif;
}

/**
 * Render product weight meta
 *
 * @return void
 */
function codetot_render_product_weight_meta() {
	global $product;

	if ( $product->has_weight() ) {
		$weight_unit = get_option( 'woocommerce_weight_unit' );

		printf(
			'<p class="product-meta product-meta--weight"><span class="product-meta__label">%s:</span> <span class="product-meta__value">%s</span></p>',
			esc_html__( 'Weight', 'woocommerce' ),
			esc_html($product->get_weight() . $weight_unit)
		);
	}
}

/**
 * Render product stock meta
 *
 * @return void
 */
function codetot_render_product_stock_meta() {
	global $product;

	$availability      = $product->get_availability();
	$hide_stock_status = codetot_get_theme_mod( 'hide_product_stock_status', 'woocommerce' ) ?? false;

	if ( ! $hide_stock_status ) :

		printf(
			'<p class="product-meta product-meta--stock"><span class="product-meta__label">%s:</span> <span class="product-meta__value">%s</span></p>',
			esc_html__( 'Stock', 'woocommerce' ),
			$availability['class'] !== 'in-stock' ? esc_html( $availability['availability'] ) : esc_html__( 'In stock', 'woocommerce' )
		);

  endif;
}

/**
 * Render product dimesion meta
 *
 * @return void
 */
function codetot_render_product_dimesion_meta() {
	global $product;

	if ( ! empty( $product->get_height() ) || ! empty( $product->get_width() ) || ! empty( $product->get_length() ) ) {
		$space = ' x ';

		ob_start();
		if ( ! empty( $product->get_height() ) ) :
			echo '<span class="height">';
			echo esc_html( $product->get_height() . get_option( 'woocommerce_dimension_unit' ) );
			echo '</span>';
			echo esc_html( $space );
	  endif;

		if ( ! empty( $product->get_width() ) && ! empty( $product->get_height() ) ) :
			echo '<span class="width">';
			echo esc_html( $product->get_width() . get_option( 'woocommerce_dimension_unit' ) );
			echo '</span>';
			echo esc_html( $space );
	  endif;

		if ( ! empty( $product->get_length() ) && ! empty( $product->get_width() ) ) :
			echo '<span class="length">';
			echo esc_html( $product->get_length() . get_option( 'woocommerce_dimension_unit' ) );
			echo '</span>';
	  endif;
		$dimesions_html = ob_get_clean();

		printf(
			'<p class="product-meta product-meta--dimesions"><span class="product-meta__label">%s:</span> <span class="product-meta__value">%s</span></p>',
			esc_html__( 'Size', 'woocommerce' ),
			$dimesions_html
		);
	}
}

/**
 * Render product categories meta
 *
 * @return void
 */
function codetot_render_product_categories_meta() {
	global $product;

	$product_categories = get_the_terms( $product->get_id(), 'product_cat' );
	if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
		$product_category_label = _n( 'Category', 'Categories', count( $product_categories ), 'woocommerce' );

		printf(
			'<p class="product-meta product-meta--categories"><span class="product-meta__label">%s:</span> <span class="product-meta__value">%s</span></p>',
			$product_category_label,
			wc_get_product_category_list( $product->get_id(), ', ' )
		);
	}
}

Codetot_Woocommerce_Layout_Product::instance();
