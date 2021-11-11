<?php
// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Codetot_CT_Settings_WooCommerce_Settings {

	/**
	 * Singleton instance
	 *
	 * @var Codetot_CT_Settings_WooCommerce_Settings
	 */
	private static $instance;

	/**
	 * Get singleton instance.
	 *
	 * @return Codetot_CT_Settings_WooCommerce_Settings
	 */
	final public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		add_filter( 'codetot_settings_fields', array( $this, 'register_woocommerce_fields' ) );
	}

	public function register_woocommerce_fields( $fields ) {
		return array_merge( $fields, apply_filters( 'codetot_woocommerce_settings_fields', array() ) );
	}
}

Codetot_CT_Settings_WooCommerce_Settings::instance();
