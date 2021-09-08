<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_Multiple_Headers
{
	/**
	 * Singleton instance
	 *
	 * @var Codetot_Multiple_Headers
	 */
	private static $instance;

	/**
	 * @var string
	 */
	protected $layout;

	/**
	 * Get singleton instance.
	 *
	 * @return Codetot_Multiple_Headers
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
	public function __construct()
	{
		$this->layout = codetot_get_theme_mod('header_layout') ?? 'header-1';
		$this->layout = str_replace('header-', '', $this->layout);

		$this->switch_header_layout();
	}

	public function switch_header_layout()
	{
		if (empty($this->layout)) {
			return;
		}

		switch ($this->layout):
			case '1':
			case '2':
				add_action('codetot_header', function () {
					the_block_part('header-style-' . $this->layout);
				}, 1);

				break;

			case '3':
				add_action('codetot_header', function () {
					the_block_part('header-style-' . $this->layout);
				}, 1);

				break;

			case '4':
				add_action('codetot_header_navigation_after', function () {
					the_block_part('header/mobile-menu-button');
				}, 10);

				add_action('codetot_header', function () {
					the_block_part('header-style-' . $this->layout);
				});

				break;

			case '5':
				add_action('codetot_header_navigation_after', function () {
					the_block_part('header/mobile-menu-button');
				}, 10);

				add_action('codetot_header', function () {
					the_block_part('header-style-' . $this->layout);
				});

				break;

			case '6':
				add_action('codetot_header_icon_blocks', function () {
					the_block_part('header/search-icon');
				}, 1);

				add_action('codetot_header', function () {
					the_block_part('header-style-' . $this->layout);
				});

				break;

			case '7':
				add_action('codetot_header_navigation_after', function () {
					the_block_part('header/mobile-menu-button');
				}, 10);

				add_action('codetot_header_icon_blocks', function () {
					the_block_part('header/search-icon');
				}, 1);

				add_action('codetot_header', function () {
					the_block_part('header-style-' . $this->layout);
				});

				break;

		endswitch;
	}
}

Codetot_Multiple_Headers::instance();
