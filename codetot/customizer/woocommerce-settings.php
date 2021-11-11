<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Codetot_Customizer_Woocommerce_Settings {

	/**
	 * Singleton instance
	 *
	 * @var Codetot_Customizer_Woocommerce_Settings
	 */
	private static $instance;

	/**
	 * Get singleton instance.
	 *
	 * @return Codetot_Customizer_Woocommerce_Settings
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
		$this->settings_id = 'codetot_woocommerce_settings';
		$this->panel_id    = 'codetot_woocommerce_options';

		add_filter( 'codetot_theme_header_hide_elements_options', array( $this, 'header_hide_elements_options' ) );

		add_action( 'customize_register', array( $this, 'register_panel_settings' ) );
		add_action( 'customize_register', array( $this, 'register_addon_settings' ) );
		add_action( 'customize_register', array( $this, 'register_global_layout_settings' ) );
		add_action( 'customize_register', array( $this, 'register_single_product_settings' ) );
		add_action( 'customize_register', array( $this, 'register_other_settings' ) );
	}

	public function header_hide_elements_options( $options ) {
		$options['header_hide_cart_icon'] = esc_html__( 'Hide cart icon', 'ct-bones' );

		return $options;
	}

	public function register_panel_settings( $wp_customize ) {
		$wp_customize->add_panel(
			$this->panel_id,
			array(
				'priority' => 70,
				'title'    => esc_html__( '[CT] WooCommerce Settings', 'ct-bones' ),
			)
		);

		return $wp_customize;
	}

	public function register_addon_settings( $wp_customize ) {
		$section_settings_id = 'codetot_woocommerce_addon_settings';
		$enable_features     = apply_filters(
			'codetot_woocommerce_feature_options',
			array(
				'enable_quick_view'               => esc_html__( 'Enable Quick view Product', 'ct-bones' ),
				'enable_product_video'            => esc_html__( 'Enable Product Video', 'ct-bones' ),
				'enable_mini_cart'                => esc_html__( 'Enable Mini Cart', 'ct-bones' ),
				'enable_login_popup'              => esc_html__( 'Enable Login Popup', 'ct-bones' ),
				'single_product_enable_countdown' => esc_html__( 'Enable Countdown on product page', 'ct-bones' ),
			)
		);

		$enable_features_count_text = sprintf( _n( '%d feature', '%d features', count( $enable_features ), 'ct-bones' ), count( $enable_features ) );

		$this->register_section(
			array(
				'id'       => $section_settings_id,
				'label'    => sprintf( esc_html__( 'Addons (%s)', 'ct-bones' ), $enable_features_count_text ),
				'priority' => 10,
			),
			$wp_customize
		);

		foreach ( $enable_features as $id => $label ) {
			$this->register_control(
				array(
					'id'                  => $id,
					'label'               => $label,
					'section_settings_id' => $section_settings_id,
					'control_args'        => array(
						'type' => 'checkbox',
					),
				),
				$wp_customize
			);
		}

		return $wp_customize;
	}

	public function register_global_layout_settings( $wp_customize ) {
		$section_settings_id = 'codetot_woocommerce_layout_settings';

		$this->register_section(
			array(
				'id'       => $section_settings_id,
				'label'    => esc_html__( 'Global Layout', 'ct-bones' ),
				'priority' => 20,
			),
			$wp_customize
		);

		$layout_options = apply_filters(
			'codetot_woocommerce_layout_options',
			array(
				'shop'             => esc_html__( 'Shop Page', 'ct-bones' ),
				'product'          => esc_html__( 'Product Page', 'ct-bones' ),
				'product_category' => esc_html__( 'Product Category', 'ct-bones' ),
			)
		);

		foreach ( $layout_options as $layout_id => $layout_label ) :
			$settings_id = $layout_id . '_layout';
			$options     = codetot_customizer_get_sidebar_options();

			if ( in_array( $layout_id, array( 'shop', 'product_category' ) ) ) {
				$options = wp_parse_args(
					array(
						'top-sidebar' => esc_html__( 'Top Sidebar', 'ct-bones' ),
					),
					$options
				);
			}

			$this->register_control(
				array(
					'id'                  => $settings_id,
					'label'               => sprintf( __( '%s Layout', 'ct-bones' ), $layout_label ),
					'setting_args'        => array( 'default' => 'no-sidebar' ),
					'section_settings_id' => $section_settings_id,
					'control_args'        => array(
						'type'    => 'select',
						'choices' => $options,
					),
				),
				$wp_customize
			);
		endforeach;

		$this->register_control(
			array(
				'id'                  => 'quick_view_short_description',
				'label'               => esc_html__( 'Display Short Description on Quick View popup', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'control_args'        => array(
					'type' => 'checkbox',
				),
			),
			$wp_customize
		);
	}

	public function register_single_product_settings( $wp_customize ) {
		$section_settings_id = 'codetot_woocommerce_single_product_settings';

		$this->register_section(
			array(
				'id'       => $section_settings_id,
				'label'    => esc_html__( 'Single Product', 'ct-bones' ),
				'priority' => 30,
			),
			$wp_customize
		);

		$this->register_control(
			array(
				'id'                  => 'single_product_gallery_thumbnail_column',
				'label'               => esc_html__( 'Product Thumbnail Columns', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'setting_args'        => array( 'default' => '4-col' ),
				'control_args'        => array(
					'type'    => 'select',
					'choices' => apply_filters(
						'single_product_gallery_thumbnail_column_options',
						array(
							'3-col' => esc_html__( '3 Thumbnails', 'ct-bones' ),
							'4-col' => esc_html__( '4 Thumbnails (Default)', 'ct-bones' ),
							'5-col' => esc_html__( '5 Thumbnails', 'ct-bones' ),
							'6-col' => esc_html__( '6 Thumbnails', 'ct-bones' ),
						)
					),
				),
			),
			$wp_customize
		);

		$this->register_control(
			array(
				'id'                  => 'single_product_gallery_thumbnail_style',
				'label'               => esc_html__( 'Product Thumbnail Style', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'setting_args'        => array( 'default' => 'default' ),
				'control_args'        => array(
					'type'    => 'select',
					'choices' => apply_filters(
						'single_product_gallery_thumbnail_style_options',
						array(
							'default' => esc_html__( 'View all thumbnails', 'ct-bones' ),
							'popup'   => esc_html__( 'Open more images in popup', 'ct-bones' ),
						)
					),
				),
			),
			$wp_customize
		);

		$this->register_control(
			array(
				'id'                  => 'single_product_sections_enable_container',
				'label'               => esc_html__( 'Sections: Enable Container', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'setting_args'        => array( 'default' => 1 ),
				'control_args'        => array(
					'type' => 'checkbox',
				),
			),
			$wp_customize
		);

		$this->register_control(
			array(
				'id'                  => 'single_product_enable_top_widget',
				'label'               => esc_html__( 'Enable Top Widget', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'setting_args'        => array( 'default' => 1 ),
				'control_args'        => array(
					'type' => 'checkbox',
				),
			),
			$wp_customize
		);

		$this->register_control(
			array(
				'id'                  => 'single_product_enable_bottom_widget',
				'label'               => esc_html__( 'Enable Bottom Widget', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'setting_args'        => array( 'default' => 1 ),
				'control_args'        => array(
					'type' => 'checkbox',
				),
			),
			$wp_customize
		);

		$this->register_control(
			array(
				'id'                  => 'single_product_cross_sell_column',
				'label'               => esc_html__( 'Cross Sell Products: Column', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'setting_args'        => array( 'default' => '4-col' ),
				'control_args'        => array(
					'type'    => 'select',
					'choices' => wp_parse_args(
						array(
							'hide' => esc_html__( 'Hide this block', 'ct-bones' ),
						),
						codetot_customizer_get_column_options()
					),
				),
			),
			$wp_customize
		);

		$this->register_control(
			array(
				'id'                  => 'single_product_upsell_column',
				'label'               => esc_html__( 'Upsell Products: Column', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'setting_args'        => array( 'default' => '4-col' ),
				'control_args'        => array(
					'type'    => 'select',
					'choices' => wp_parse_args(
						array(
							'hide' => esc_html__( 'Hide this block', 'ct-bones' ),
						),
						codetot_customizer_get_column_options()
					),
				),
			),
			$wp_customize
		);

		$this->register_control(
			array(
				'id'                  => 'single_product_viewed_products_column',
				'label'               => esc_html__( 'Viewed Products: Column', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'setting_args'        => array( 'default' => '4-col' ),
				'control_args'        => array(
					'type'    => 'select',
					'choices' => wp_parse_args(
						array(
							'hide' => esc_html__( 'Hide this block', 'ct-bones' ),
						),
						codetot_customizer_get_column_options()
					),
				),
			),
			$wp_customize
		);

		$this->register_control(
			array(
				'id'                  => 'single_product_related_products_column',
				'label'               => esc_html__( 'Related Products: Column', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'setting_args'        => array( 'default' => '4-col' ),
				'control_args'        => array(
					'type'    => 'select',
					'choices' => wp_parse_args(
						array(
							'hide' => esc_html__( 'Hide this block', 'ct-bones' ),
						),
						codetot_customizer_get_column_options()
					),
				),
			),
			$wp_customize
		);

		$this->register_control(
			array(
				'id'                  => 'single_product_cross_sell_enable_slider',
				'label'               => esc_html__( 'Cross Sell Products: Enable Slider', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'setting_args'        => array( 'default' => 0 ),
				'control_args'        => array(
					'type' => 'checkbox',
				),
			),
			$wp_customize
		);

		$this->register_control(
			array(
				'id'                  => 'single_product_upsell_enable_slider',
				'label'               => esc_html__( 'Upsell Products: Enable Slider', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'setting_args'        => array( 'default' => 0 ),
				'control_args'        => array(
					'type' => 'checkbox',
				),
			),
			$wp_customize
		);

		$this->register_control(
			array(
				'id'                  => 'single_product_viewed_products_enable_slider',
				'label'               => esc_html__( 'Viewed Products: Enable Slider', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'setting_args'        => array( 'default' => 0 ),
				'control_args'        => array(
					'type' => 'checkbox',
				),
			),
			$wp_customize
		);

		$this->register_control(
			array(
				'id'                  => 'single_product_related_products_enable_slider',
				'label'               => esc_html__( 'Related Products: Enable Slider', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'setting_args'        => array( 'default' => 0 ),
				'control_args'        => array(
					'type' => 'checkbox',
				),
			),
			$wp_customize
		);

		$this->register_control(
			array(
				'id'                  => 'single_product_enable_facebook_comment',
				'label'               => esc_html__( 'Enable Facebook Comment', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'control_args'        => array(
					'type' => 'checkbox',
				),
			),
			$wp_customize
		);

		$this->register_control(
			array(
				'id'                  => 'single_product_facebook_comment_app_id',
				'label'               => esc_html__( 'Facebook Comment App ID', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'control_args'        => array(
					'type'        => 'text',
					'placeholder' => esc_html__( 'Enter your Facebook app ID.', 'ct-bones' ),
				),
			),
			$wp_customize
		);

		$this->register_control(
			array(
				'id'                  => 'single_product_hide_product_stock_status',
				'label'               => esc_html__( 'Hide Product Stock Status', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'control_args'        => array(
					'type' => 'checkbox',
				),
			),
			$wp_customize
		);

		$this->register_control(
			array(
				'id'                  => 'single_product_countdown_style',
				'label'               => esc_html__( 'Countdown Style', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'setting_args'        => array( 'default' => 'default' ),
				'control_args'        => array(
					'type'        => 'select',
					'description' => esc_html__( 'You must enable Countdown feature to apply those settings.', 'ct-bones' ),
					'choices'     => array(
						'default'   => esc_html__( 'Gradient', 'ct-bones' ),
						'primary'   => esc_html__( 'Primary', 'ct-bones' ),
						'secondary' => esc_html__( 'Secondary', 'ct-bones' ),
						'dark'      => esc_html__( 'Dark', 'ct-bones' ),
						'custom'    => esc_html__( 'Custom Theme', 'ct-bones' ),
					),
				),
			),
			$wp_customize
		);

		return $wp_customize;
	}

	public function register_other_settings( $wp_customize ) {
		$section_settings_id = 'codetot_woocommerce_other_settings';

		codetot_customizer_register_section(
			array(
				'id'       => $section_settings_id,
				'label'    => esc_html__( 'Other Settings', 'ct-bones' ),
				'panel'    => 'codetot_woocommerce_options',
				'priority' => 100,
			),
			$wp_customize
		);

		$this->register_control(
			array(
				'id'                  => 'hide_sticky_bar_editing_products',
				'label'               => esc_html__( 'Admin: Hide sticky bar when editing products', 'ct-bones' ),
				'section_settings_id' => $section_settings_id,
				'control_args'        => array(
					'type' => 'checkbox',
				),
			),
			$wp_customize
		);

		return $wp_customize;
	}

	public function register_section( $args, $wp_customize ) {
		codetot_customizer_register_section(
			array(
				'id'       => $args['id'],
				'label'    => $args['label'],
				'panel'    => $this->panel_id,
				'priority' => $args['priority'],
			),
			$wp_customize
		);

		return $wp_customize;
	}

	public function register_control( $args, $wp_customize ) {
		$final_args = wp_parse_args(
			array(
				'option_type' => $this->settings_id,
			),
			$args
		);

		codetot_customizer_register_control( $final_args, $wp_customize );

		return $wp_customize;
	}
}

Codetot_Customizer_Woocommerce_Settings::instance();
