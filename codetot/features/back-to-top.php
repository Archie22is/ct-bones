<?php
// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Codetot_Back_To_Top {

	/**
	 * Singleton instance
	 *
	 * @var Codetot_Back_To_Top
	 */
	private static $instance;

	/**
	 * Get singleton instance.
	 *
	 * @return Codetot_Back_To_Top
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
		 $enable = codetot_get_theme_mod( 'enable_back_to_top', 'pro' ) ?? true;

		if ( $enable ) {
			add_action( 'wp_footer', 'codetot_render_back_to_top_section' );
		}
	}
}

function codetot_render_back_to_top_section() {
	 the_block( 'back-to-top' );
}

Codetot_Back_To_Top::instance();
