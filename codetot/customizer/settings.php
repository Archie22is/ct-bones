<?php
if (!defined('ABSPATH')) exit;

class Codetot_Customizer_Settings
{
	/**
	 * Singleton instance
	 *
	 * @var Codetot_Customizer_Settings
	 */
	private static $instance;

	/**
	 * Get singleton instance.
	 *
	 * @return Codetot_Customizer_Settings
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
		add_action('customize_register', array($this, 'register_panels'));

		// Global Theme Options
		add_action('customize_register', array($this, 'register_color_schemas_settings'));
		add_action('customize_register', array($this, 'register_typography_settings'));
		add_action('customize_register', array($this, 'register_layout_settings'));
		add_action('customize_register', array($this, 'register_topbar_settings'));
		add_action('customize_register', array($this, 'register_header_settings'));
		add_action('customize_register', array($this, 'register_footer_settings'));
		add_action('customize_register', array($this, 'register_single_post_settings'));
	}

	public function register_panels($wp_customize)
	{
		$wp_customize->add_panel(
			'codetot_theme_options',
			array(
				'priority' => 50,
				'title'    => esc_html__('[CT] Theme Settings', 'ct-bones'),
			)
		);

		return $wp_customize;
	}

	public function register_color_schemas_settings($wp_customize)
	{
		$section_settings_id = 'codetot_theme_color_settings';

		codetot_customizer_register_section(array(
			'id' => $section_settings_id,
			'label' => esc_html__('Color Schemas', 'ct-bones'),
			'priority' => 10
		), $wp_customize);

		// Register color schemas
		$color_options = codetot_get_color_options();
		foreach ($color_options as $color) {
			$color['id'] = str_replace('codetot_', '', $color['id']);

			codetot_customizer_register_color_control($color, $section_settings_id, $wp_customize);
		}

		return $wp_customize;
	}

	public function register_typography_settings($wp_customize)
	{
		$section_settings_id = 'codetot_theme_typography_settings';

		codetot_customizer_register_section(array(
			'id' => $section_settings_id,
			'label' => esc_html__('Typography', 'ct-bones'),
			'priority' => 20
		), $wp_customize);

		$font_family_options = codetot_get_font_family_options();
		$font_types = array(
			'body_font' => esc_html__('Body Font Family', 'ct-bones'),
			'heading_font' => esc_html__('Heading Font Family', 'ct-bones')
		);

		foreach ($font_types as $font_id => $font_type) {
			codetot_customizer_register_control(array(
				'id' => $font_id,
				'label' => $font_type,
				'setting_args' => array('default' => 'Averta'),
				'section_settings_id' => $section_settings_id,
				'control_args' => array(
					'type' => 'select',
					'choices' => $font_family_options
				)

			), $wp_customize);
		}

		$font_sizes = apply_filters('codetot_theme_font_size_scale_options', array(
			'1067' => esc_html__('1.067 - Minor Second', 'ct-bones'),
			'1125' => esc_html__('1.125 - Major Second', 'ct-bones'),
			'1200' => esc_html__('1.200 - Minor Third', 'ct-bones'),
			'1250' => esc_html__('1.250 - Major Third', 'ct-bones'),
			'1333' => esc_html__('1.333  Perfect Fourth', 'ct-bones'),
			'1444' => esc_html__('1.444 - Augmented Fourth', 'ct-bones'),
			'1500' => esc_html__('1.500 - Perfect Fifth', 'ct-bones'),
			'1618' => esc_html__('1.618 - Golden Ratio', 'ct-bones')
		));

		codetot_customizer_register_control(array(
			'id' => 'font_scale',
			'label' => esc_html__('Font Size Scale Size', 'ct-bones'),
			'setting_args' => array('default' => '1125'),
			'section_settings_id' => $section_settings_id,
			'control_args' => array(
				'type' => 'select',
				'choices' => $font_sizes
			)
		), $wp_customize);

		return $wp_customize;
	}

	public function register_layout_settings($wp_customize)
	{
		$section_settings_id = 'codetot_theme_layout_settings';

		codetot_customizer_register_section(array(
			'id' => $section_settings_id,
			'label' => esc_html__('Global Layout', 'ct-bones'),
			'priority' => 30
		), $wp_customize);

		$layout_options = apply_filters('codetot_theme_layout_options', array(
			'category' => esc_html__('Category', 'ct-bones'),
			'post' => esc_html__('Post', 'ct-bones'),
			'page' => esc_html__('Page', 'ct-bones')
		));
		foreach ($layout_options as $layout_id => $layout_label) :
			$settings_id = $layout_id . '_layout';

			codetot_customizer_register_control(array(
				'id' => $settings_id,
				'label' => sprintf(__('%s Layout', 'ct-bones'), $layout_label),
				'setting_args' => array('default' => 'no-sidebar'),
				'section_settings_id' => $section_settings_id,
				'control_args' => array(
					'type'     => 'select',
					'choices'  => codetot_customizer_get_sidebar_options()
				)
			), $wp_customize);
		endforeach;

		// Global Container width
		codetot_customizer_register_control(array(
			'id' => 'container_width',
			'label' => esc_html__('Container Width', 'ct-bones') . ' (pixel)',
			'setting_args' => array('default' => '1280'),
			'section_settings_id' => $section_settings_id,
			'control_args' => array(
				'type'     => 'number',
				'sanitize_callback' => 'absint',
				'input_attrs' => array(
					'min' => 900,
					'max' => 1400
				)
			)
		), $wp_customize);

		// Archive Layout
		codetot_customizer_register_control(array(
			'id' => 'archive_post_layout',
			'label' => esc_html__('Archive Post Layout', 'ct-bones'),
			'setting_args' => array('default' => 'list'),
			'section_settings_id' => $section_settings_id,
			'control_args' => array(
				'type' => 'select',
				'choices' => array(
					'list' => esc_html__('Post List', 'ct-bones'),
					'grid' => esc_html__('Post Grid', 'ct-bones')
				)
			)
		), $wp_customize);

		codetot_customizer_register_control(array(
			'id' => 'archive_post_column',
			'label' => esc_html__('Archive Post Column', 'ct-bones'),
			'setting_args' => array('default' => 3),
			'section_settings_id' => $section_settings_id,
			'control_args' => array(
				'type' => 'select',
				'description' => sprintf(esc_html__('Only if %1$s has a selection is %2$s.', 'ct-bones'), esc_html__('Archive Post Layout', 'ct-bones'), esc_html__('Post Grid', 'ct-bones')),
				'choices' => apply_filters('archive_post_column_options', array(
					2 => esc_html__('2 Columns', 'ct-bones'),
					3 => esc_html__('3 Columns', 'ct-bones'),
					4 => esc_html__('4 Columns', 'ct-bones')
				))
			)
		), $wp_customize);

		codetot_customizer_register_control(array(
			'id' => 'post_card_style',
			'label' => esc_html__('Post Card Style', 'ct-bones'),
			'setting_args' => array('default' => 'style-1'),
			'section_settings_id' => $section_settings_id,
			'control_args' => array(
				'type' => 'select',
				'choices' => apply_filters('codetot_theme_post_card_style_options', array(
					'style-1' => esc_html__('Style 1', 'ct-bones'),
					'style-2' => esc_html__('Style 2', 'ct-bones'),
					'style-3' => esc_html__('Style 3', 'ct-bones'),
					'style-4' => esc_html__('Style 4', 'ct-bones'),
					'style-5' => esc_html__('Style 5', 'ct-bones'),
					'style-theme' => esc_html__('Theme Style', 'ct-bones')
				))
			)
		), $wp_customize);

		return $wp_customize;
	}

	public function register_header_settings($wp_customize)
	{
		$section_settings_id = 'codetot_theme_header_settings';

		codetot_customizer_register_section(array(
			'id' => $section_settings_id,
			'label' => esc_html__('Header', 'ct-bones'),
			'priority' => 50
		), $wp_customize);

		$header_layout_options = apply_filters('codetot_theme_header_layout_options', array(
			'header-1' => esc_html__('Header 1', 'ct-bones'),
			'header-2' => esc_html__('Header 2', 'ct-bones'),
			'header-3' => esc_html__('Header 3', 'ct-bones'),
			'header-4' => esc_html__('Header 4', 'ct-bones'),
			'header-5' => esc_html__('Header 5', 'ct-bones'),
			'header-6' => esc_html__('Header 6', 'ct-bones'),
			'header-theme' => esc_html__('Custom Theme Header', 'ct-bones')
		));

		// Header layout
		codetot_customizer_register_control(array(
			'id' => 'header_layout',
			'label' => esc_html__('Header Layout', 'ct-bones'),
			'section_settings_id' => $section_settings_id,
			'setting_args' => array('default' => 'header-1'),
			'control_args' => array(
				'type' => 'select',
				'choices' => $header_layout_options
			)
		), $wp_customize);

		// Header Background Color
		codetot_customizer_register_control(array(
			'id' => 'header_background_color',
			'label' => esc_html__('Header Background Color', 'ct-bones'),
			'section_settings_id' => $section_settings_id,
			'setting_args' => array('default' => 'transparent'),
			'control_args' => array(
				'type' => 'select',
				'choices' => $this->get_background_color_options()
			)
		), $wp_customize);

		// Header Text Contract
		codetot_customizer_register_control(array(
			'id' => 'header_text_contract',
			'label' => esc_html__('Header Text Contract', 'ct-bones'),
			'section_settings_id' => $section_settings_id,
			'setting_args' => array('default' => 'light'),
			'control_args' => array(
				'type' => 'select',
				'choices' => $this->get_background_text_contract_options()
			)
		), $wp_customize);

		$sticky_header_options = apply_filters('codetot_theme_header_sticky_options', array(
			'none' => esc_html__('No Sticky Header', 'ct-bones'),
			'jump-down' => esc_html__('Jump Down', 'ct-bones'),
			'visible-scroll-up' => esc_html__('Visible when Scrolling up', 'ct-bones')
		));

		codetot_customizer_register_control(array(
			'id' => 'header_sticky_type',
			'label' => esc_html__('Sticky Header Type', 'ct-bones'),
			'section_settings_id' => $section_settings_id,
			'setting_args' => array('default' => 'none'),
			'control_args' => array(
				'type' => 'select',
				'choices' => $sticky_header_options
			)
		), $wp_customize);

		$hide_elements = apply_filters('codetot_theme_header_hide_elements_options', array(
			'header_hide_account_icon' => esc_html__('Hide account icon', 'ct-bones'),
			'header_hide_search_icon'  => esc_html__('Hide search icon', 'ct-bones')
		));

		foreach ($hide_elements as $id => $label) {
			codetot_customizer_register_control(array(
				'id' => $id,
				'label' => $label,
				'section_settings_id' => $section_settings_id,
				'control_args' => array(
					'type' => 'checkbox'
				)
			), $wp_customize);
		}

		codetot_customizer_register_control(array(
			'id' => 'header_display_phone_number',
			'label' => esc_html__('Display Phone Number', 'ct-bones'),
			'section_settings_id' => $section_settings_id,
			'control_args' => array(
				'type' => 'checkbox'
			)
		), $wp_customize);

		codetot_customizer_register_control(array(
			'id' => 'header_menu_home_icon',
			'label' => esc_html__('Display Home icon in Primary menu', 'ct-bones'),
			'section_settings_id' => $section_settings_id,
			'control_args' => array(
				'type' => 'checkbox'
			)
		), $wp_customize);

		return $wp_customize;
	}

	public function register_topbar_settings($wp_customize)
	{
		$section_settings_id = 'codetot_theme_topbar_settings';

		codetot_customizer_register_section(array(
			'id' => $section_settings_id,
			'label' => esc_html__('Topbar', 'ct-bones'),
			'priority' => 45
		), $wp_customize);

		// Enable Topbar
		codetot_customizer_register_control(array(
			'id' => 'enable_topbar_widget',
			'label' => esc_html__('Enable Topbar', 'ct-bones'),
			'section_settings_id' => $section_settings_id,
			'control_args' => array(
				'type' => 'checkbox'
			)
		), $wp_customize);

		// Topbar Columns
		codetot_customizer_register_control(array(
			'id' => 'topbar_widget_column',
			'label' => esc_html__('Topbar Column', 'ct-bones'),
			'setting_args' => array('default' => '1-col'),
			'section_settings_id' => $section_settings_id,
			'control_args' => array(
				'type' => 'select',
				'choices' => apply_filters('codetot_theme_topbar_column_options', array(
					'1-col' => esc_html__('1 Column', 'ct-bones'),
					'2-col' => esc_html__('2 Columns', 'ct-bones')
				))
			)
		), $wp_customize);

		// Topbar Background Color
		codetot_customizer_register_control(array(
			'id' => 'topbar_background_color',
			'label' => esc_html__('Topbar Background Color', 'ct-bones'),
			'setting_args' => array('default' => 'transparent'),
			'section_settings_id' => $section_settings_id,
			'control_args' => array(
				'type' => 'select',
				'choices' => $this->get_background_color_options()
			)
		), $wp_customize);

		// Topbar Text Contract
		codetot_customizer_register_control(array(
			'id' => 'topbar_text_contract',
			'label' => esc_html__('Topbar Text Contract', 'ct-bones'),
			'setting_args' => array('default' => 'light'),
			'section_settings_id' => $section_settings_id,
			'control_args' => array(
				'type' => 'select',
				'choices' => $this->get_background_text_contract_options()
			)
		), $wp_customize);

		return $wp_customize;
	}

	public function register_footer_settings($wp_customize)
	{
		$parent_theme = wp_get_theme()->parent();
		$main_theme = !empty($parent_theme) ? $parent_theme : wp_get_theme();
		$theme_version = !empty($parent_theme) ? $parent_theme->Version : wp_get_theme()->Get('Version');
		$section_settings_id = 'codetot_theme_footer_settings';

		codetot_customizer_register_section(array(
			'id' => $section_settings_id,
			'label' => esc_html__('Footer', 'ct-bones'),
			'priority' => 100
		), $wp_customize);

		// Display Copyright text
		codetot_customizer_register_control(array(
			'id' => 'hide_footer_copyright',
			'label' => esc_html__('Hide Footer Copyright Text', 'ct-bones'),
			'section_settings_id' => $section_settings_id,
			'control_args' => array(
				'type' => 'checkbox'
			)
		), $wp_customize);

		// Customize Copyright text
		codetot_customizer_register_control(array(
			'id' => 'footer_copyright_text',
			'label' => esc_html__('Footer Copyright Text', 'ct-bones'),
			'section_settings_id' => $section_settings_id,
			'setting_args' => array(
				'default' => sprintf(
					esc_html__('Copyright &copy; by %1$s. Build with %2$s (version %3$s).', 'ct-bones'),
					get_bloginfo('name'),
					sprintf('<a href="%1$s" rel="sponsored" target="_blank">%2$s</a>', $main_theme->Get('AuthorURI'), $main_theme->Get('Author')),
					$theme_version
				)
			),
			'control_args' => array(
				'type' => 'textarea'
			)
		), $wp_customize);

		// Hide footer widget
		codetot_customizer_register_control(array(
			'id' => 'hide_footer_widgets',
			'label' => esc_html__('Hide Footer Widgets', 'ct-bones'),
			'section_settings_id' => $section_settings_id,
			'control_args' => array(
				'type' => 'checkbox'
			)
		), $wp_customize);

		// Hide footer bottom social links
		codetot_customizer_register_control(array(
			'id' => 'footer_hide_social_links',
			'label' => esc_html__('Hide Footer Social Links', 'ct-bones'),
			'section_settings_id' => $section_settings_id,
			'control_args' => array(
				'type' => 'checkbox'
			)
		), $wp_customize);

		// Footer columns
		codetot_customizer_register_control(array(
			'id' => 'footer_widget_column',
			'label' => esc_html__('Footer Widget Column', 'ct-bones'),
			'setting_args' => array('default' => '3-col'),
			'section_settings_id' => $section_settings_id,
			'control_args' => array(
				'type' => 'select',
				'choices' => $this->get_sidebar_column_options()
			)
		), $wp_customize);

		// Footer Background Color
		codetot_customizer_register_control(array(
			'id' => 'footer_background_color',
			'label' => esc_html__('Footer Background Color', 'ct-bones'),
			'setting_args' => array('default' => 'transparent'),
			'section_settings_id' => $section_settings_id,
			'control_args' => array(
				'type' => 'select',
				'choices' => $this->get_background_color_options()
			)
		), $wp_customize);

		// Footer Text Contract
		codetot_customizer_register_control(array(
			'id' => 'footer_text_contract',
			'label' => esc_html__('Footer Text Contract', 'ct-bones'),
			'setting_args' => array('default' => 'light'),
			'section_settings_id' => $section_settings_id,
			'control_args' => array(
				'type' => 'select',
				'choices' => $this->get_background_text_contract_options()
			)
		), $wp_customize);

		return $wp_customize;
	}

	public function register_single_post_settings($wp_customize)
	{
		$section_settings_id = 'codetot_theme_single_post_settings';

		codetot_customizer_register_section(array(
			'id' => $section_settings_id,
			'label' => esc_html__('Single Post', 'ct-bones'),
			'priority' => 110
		), $wp_customize);

		$hide_options = array(
			'hide_post_meta' => esc_html__('Hide post meta', 'ct-bones'),
			'hide_social_share' => esc_html__('Hide social share', 'ct-bones'),
			'hide_comments' => esc_html__('Hide comments', 'ct-bones'),
			'hide_featured_image' => esc_html__('Hide featured image', 'ct-bones')
		);

		foreach ($hide_options as $settings_id => $label) {
			codetot_customizer_register_control(array(
				'id' => $settings_id,
				'label' => $label,
				'section_settings_id' => $section_settings_id,
				'control_args' => array(
					'type' => 'checkbox'
				)
			), $wp_customize);
		}

		// Enable Facebook comment
		codetot_customizer_register_control(array(
			'id' => 'single_post_enable_facebook_comment',
			'label' => esc_html__('Enable Facebook comment', 'ct-bones'),
			'section_settings_id' => $section_settings_id,
			'control_args' => array(
				'type' => 'checkbox'
			)
		), $wp_customize);

		return $wp_customize;
	}

	public function get_sidebar_column_options()
	{
		return array(
			0 => esc_html__('Disable Sidebar', 'ct-bones'),
			'1-col' => esc_html__('1 Column', 'ct-bones'),
			'2-col' => esc_html__('2 Columns', 'ct-bones'),
			'3-col' => esc_html__('3 Columns', 'ct-bones'),
			'4-col' => esc_html__('4 Columns', 'ct-bones')
		);
	}

	public function get_background_color_options()
	{
		return array(
			'transparent' => esc_html__('Transparent (No Background Color)', 'ct-bones'),
			'primary'     => esc_html__('Primary', 'ct-bones'),
			'secondary'   => esc_html__('Secondary', 'ct-bones'),
			'white'       => esc_html__('White', 'ct-bones'),
			'dark'        => esc_html__('Dark', 'ct-bones'),
			'gray'        => esc_html__('Gray', 'ct-bones'),
			'light'       => esc_html__('Light', 'ct-bones')
		);
	}

	public function get_background_text_contract_options()
	{
		return array(
			'light' => esc_html__('Light Background - Dark Text', 'ct-bones'),
			'dark' => esc_html__('Dark Background - White Text', 'ct-bones')
		);
	}
}

Codetot_Customizer_Settings::instance();
