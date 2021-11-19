<?php
// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Codetot_Mega_Menu {

	/**
	 * Singleton instance
	 *
	 * @var Codetot_Mega_Menu
	 */
	private static $instance;

	/**
	 * Get singleton instance.
	 *
	 * @return Codetot_Mega_Menu
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
	public function __construct() {
		 $enable_mega_menu = codetot_get_theme_mod( 'enable_mega_menu', 'pro' ) ?? true;

		if ( $enable_mega_menu ) :
			$this->register_acf_fields();
			add_filter( 'wp_nav_menu_objects', array( $this, 'wp_nav_menu_objects' ), 10, 2 );
		endif;
	}

	public function register_acf_fields() {
		if ( function_exists( 'acf_add_local_field_group' ) ) :

			acf_add_local_field_group(
				array(
					'key'                   => 'group_60d082c3b524e',
					'title'                 => esc_html__( 'Mega Menu Settings', 'ct-bones' ),
					'fields'                => array(
						array(
							'key'               => 'field_60d082d353798',
							'label'             => esc_html__( 'Display Mega Menu', 'ct-bones' ),
							'name'              => 'display_mega_menu',
							'type'              => 'true_false',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'message'           => '',
							'default_value'     => 0,
							'ui'                => 0,
							'ui_on_text'        => '',
							'ui_off_text'       => '',
						),
						array(
							'key'               => 'field_60d082fd53799',
							'label'             => 'Column',
							'name'              => 'column',
							'type'              => 'select',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_60d082d353798',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'choices'           => array(
								'col-2' => '2 Column',
								'col-3' => '3 Column',
								'col-4' => '4 Column',
								'col-5' => '5 Column',
								'col-6' => '6 Column',
							),
							'default_value'     => false,
							'allow_null'        => 0,
							'multiple'          => 0,
							'ui'                => 0,
							'return_format'     => 'array',
							'ajax'              => 0,
							'placeholder'       => '',
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'nav_menu_item',
								'operator' => '==',
								'value'    => 'location/primary',
							),
						),
					),
					'menu_order'            => 0,
					'position'              => 'normal',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);

		endif;
	}

	public function wp_nav_menu_objects( $items, $args ) {
		foreach ( $items as &$item ) {
			$is_enable = function_exists( 'get_field' ) && get_field( 'display_mega_menu', $item );
			$column    = function_exists( 'get_field' ) && get_field( 'column', $item );
			if ( $is_enable == true && $item->menu_item_parent == 0 ) {
				$item->classes[] = sprintf( 'has-mega-menu mega-%s', $column['value'] );
			}
		}
		return $items;
	}
}

Codetot_Mega_Menu::instance();
