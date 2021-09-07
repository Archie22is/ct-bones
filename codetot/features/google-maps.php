<?php

/**
 * Display header layout based on selection.
 *
 * @package Codetot
 * @subpackage Codetot_Google_Maps
 * @since 0.0.1
 */

// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_Google_Maps
{
	/**
	 * Singleton instance
	 *
	 * @var Codetot_Google_Maps
	 */
	private static $instance;
	/**
	 * @var string
	 */
	private $google_maps_api_key;

	/**
	 * Get singleton instance.
	 *
	 * @return Codetot_Google_Maps
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
		$this->google_maps_api_key = get_codetot_data('codetot_google_maps_api_key') ?? null;

		if (!empty($this->google_maps_api_key)) {
			add_filter('acf/init', array($this, 'load_google_maps_api_key'));
			add_action('wp_head', array($this, 'load_google_maps_key_header'));
		}
	}

	public function load_google_maps_api_key()
	{
		acf_update_setting('google_api_key', $this->google_maps_api_key);
	}

	public function load_google_maps_key_header()
	{
		echo '<script>';
		echo 'var GOOGLE_MAPS_API_KEY = "' . $this->google_maps_api_key . '"';
		echo '</script>';
	}
}

Codetot_Google_Maps::instance();
