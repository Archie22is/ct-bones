<?php

/**
 * Global settings applies to all options
 */

/**
 * Class Codetot_Acf
 */
class Codetot_Acf {
	/**
	 * Singleton instance
	 *
	 * @var Codetot_Acf
	 */
	private static $instance;

	/**
	 * Get singleton instance.
	 *
	 * @return Codetot_Acf
	 */
	final public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		// Button settings
		add_filter( 'acf/load_field/name=button_style', array( $this, 'load_button_styles' ) );
		add_filter( 'acf/load_field/name=button_target', array( $this, 'load_button_targets' ) );
		add_filter( 'acf/load_field/name=button_size', array( $this, 'load_button_sizes' ) );

		// Contact Form Select Settings
		add_filter( 'acf/load_field/name=contact_form', array( $this, 'load_contact_form_options' ) );
		add_filter( 'acf/load_field/name=select_form', array( $this, 'load_contact_form_options' ) );

		// Global Text Alignment
		add_filter( 'acf/load_field/name=content_position', array( $this, 'load_alignments' ) );
		add_filter( 'acf/load_field/name=header_alignment', array( $this, 'load_alignments' ) );
		add_filter( 'acf/load_field/name=tabs_alignment', array( $this, 'load_alignments' ) );
		add_filter( 'acf/load_field/name=form_alignment', array( $this, 'load_alignments' ) );
		add_filter( 'acf/load_field/name=footer_alignment', array( $this, 'load_alignments' ) );
		add_filter( 'acf/load_field/name=content_alignment', array( $this, 'load_alignments' ) );
		add_filter( 'acf/load_field/name=cell_alignment', array( $this, 'load_alignments' ) );

		// Columns
		add_filter( 'acf/load_field/name=columns_count', array( $this, 'load_columns' ) );

		// Background Contract: Light/Dark
		add_filter( 'acf/load_field/name=background_contract', array( $this, 'load_background_contract' ) );
		// Background Color
		add_filter( 'acf/load_field/name=background_type', array( $this, 'load_background_types' ) );
		add_filter( 'acf/load_field/name=style_color', array( $this, 'load_background_types' ) );
		add_filter( 'acf/load_field/name=background_type_item', array( $this, 'load_background_types' ) );

		// Section title tag
		add_filter( 'acf/load_field/name=section_title_tag', array( $this, 'load_section_title_tag' ) );

		// Block Presets
		add_filter( 'acf/load_field/name=block_preset', array( $this, 'load_block_presets' ) );
		add_filter( 'acf/load_field/name=block_spacing', array( $this, 'load_block_spacing' ) );
		add_filter( 'acf/load_field/name=block_container', array( $this, 'load_block_container' ) );

		// Contact Section - Layout Settings
		add_filter( 'acf/load_field/name=contact_primary_layout', array( $this, 'load_primary_layouts' ) );
		add_filter( 'acf/load_field/name=contact_secondary_layout', array( $this, 'load_secondary_layouts' ) );

		// Image Type
		add_filter( 'acf/load_field/name=image_size', array( $this, 'load_image_types' ) );
	}

	public function load_button_styles( $field ) {
		$field['choices'] = apply_filters(
			'codetot_button_styles',
			array(
				'primary'         => __( 'Primary: White Text - Primary Background', 'ct-bones' ),
				'secondary'       => __( 'Secondary: White Text - Secondary Background', 'ct-bones' ),
				'dark'            => __( 'Dark: White Text - Dark Background', 'ct-bones' ),
				'white'           => __( 'White: Dark Text - White Background', 'ct-bones' ),
				'outline'         => __( 'Outline: Dark Border and Text - No Background', 'ct-bones' ),
				'outline-primary' => __( 'Outline Primary: Primary Border and Text - No Background' ),
				'outline-white'   => __( 'Outline White: White Border and Text - No Background', 'ct-bones' ),
				'link'            => __( 'Link: Dark Text and Line', 'ct-bones' ),
				'link-white'      => __( 'Link White: White Text and Line', 'ct-bones' ),
			)
		);

		return $field;
	}

	public function load_button_targets( $field ) {
		$field['choices'] = array(
			'_self'  => __( 'Same Window/Tab', 'ct-bones' ),
			'_blank' => __( 'New Window/Tab', 'ct-bones' ),
		);

		return $field;
	}

	public function load_button_sizes( $field ) {
		$field['choices'] = apply_filters(
			'codetot_button_sizes',
			array(
				'normal' => __( 'Normal', 'ct-bones' ),
				'small'  => __( 'Small', 'ct-bones' ),
				'large'  => __( 'Large', 'ct-bones' ),
			)
		);

		return $field;
	}

	public function load_contact_form_options( $field ) {
		if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
			$field['choices'] = array();

			$form_args = array(
				'post_type'      => 'wpcf7_contact_form',
				'posts_per_page' => -1,
			);

			$forms = get_posts( $form_args );

			foreach ( $forms as $form ) {
				$field['choices'][ $form->ID ] = $form->post_title;
			}

			$field['class'] = 'contact-form-7';
		}

		if ( class_exists( 'GFFormsModel' ) ) {
			$choices = array();

			foreach ( \GFFormsModel::get_forms() as $form ) {
				$choices[ $form->id ] = $form->title;
			}

			if ( empty( $choices ) ) {
				$choices[''] = __( 'No available forms.', 'ct-bones' );
			}

			$field['choices'] = $choices;
			$field['class']   = 'gravity-forms';
		}

		return $field;
	}

	public function load_alignments( $field ) {
		$field['choices'] = array(
			'left'   => __( 'Left', 'ct-bones' ),
			'center' => __( 'Center', 'ct-bones' ),
			'right'  => __( 'Right', 'ct-bones' ),
		);

		return $field;
	}

	public function load_background_contract( $field ) {
		$field['choices'] = array(
			'light' => __( 'Light Background - Dark Text', 'ct-bones' ),
			'dark'  => __( 'Dark Background - White Text', 'ct-bones' ),
		);

		return $field;
	}

	public function load_background_types( $field ) {
		$field['choices'] = apply_filters(
			'codetot_background_types',
			array(
				'white'     => __( 'White', 'ct-bones' ),
				'light'     => __( 'Light', 'ct-bones' ),
				'gray'      => __( 'Gray', 'ct-bones' ),
				'dark'      => __( 'Dark', 'ct-bones' ),
				'black'     => __( 'Black', 'ct-bones' ),
				'primary'   => __( 'Primary', 'ct-bones' ),
				'secondary' => __( 'Secondary', 'ct-bones' ),
			)
		);

		return $field;
	}

	public function load_section_title_tag( $field ) {
		$field['choices'] = apply_filters(
			'codetot_section_title_tag',
			array(
				'h1' => __( 'Heading 1', 'ct-bones' ),
				'h2' => __( 'Heading 2', 'ct-bones' ),
				'h3' => __( 'Heading 3', 'ct-bones' ),
				'p'  => __( 'Paragraph', 'ct-bones' ),
			)
		);

		return $field;
	}

	public function load_block_presets( $field ) {
		$preset_number = 7; // = 6
		$options       = array(
			''      => __( 'Default', 'ct-bones' ),
			'theme' => __( 'Theme Preset', 'ct-bones' ),
		);
		for ( $i = 1; $i < $preset_number; $i++ ) {
			$options[ $i ] = sprintf( __( 'Preset %s', 'ct-bones' ), $i );
		}

		$field['choices'] = apply_filters( 'codetot_block_presets', $options );

		return $field;
	}

	public function load_block_spacing( $field ) {
		$default_spacing = codetot_block_vertical_spaces();

		$field['choices'] = apply_filters( 'codetot_block_spacing', $default_spacing );

		return $field;
	}

	public function load_block_container( $field ) {
		$default_container_options = codetot_container_layout_options();

		$field['choices'] = apply_filters( 'codetot_block_container_choices', $default_container_options );

		return $field;
	}

	public function load_primary_layouts( $field ) {
		$field['choices'] = array(
			'default' => __( 'Left Map - Right Content', 'ct-bones' ),
			'switch'  => __( 'Right Map - Left Content', 'ct-bones' ),
			'top'     => __( 'Top Map - Bottom Content', 'ct-bones' ),
			'bottom'  => __( 'Top Content - Bottom Map', 'ct-bones' ),
		);

		return $field;
	}

	public function load_secondary_layouts( $field ) {
		$field['choices'] = array(
			'default' => __( 'Top Content - Bottom Form', 'ct-bones' ),
			'switch'  => __( 'Top Form - Bottom Content', 'ct-bones' ),
			'left'    => __( 'Left Content - Right Form', 'ct-bones' ),
			'right'   => __( 'Left Form - Right Content', 'ct-bones' ),
		);

		return $field;
	}

	public function load_image_types( $field ) {
		$field['choices'] = array(
			'default' => __( 'Default Image Size', 'ct-bones' ),
			'cover'   => __( 'Cover Image', 'ct-bones' ),
			'contain' => __( 'Contain Image', 'ct-bones' ),
		);

		$field['default_value'] = 'default';

		return $field;
	}

	public function load_columns( $field ) {
		$field['choices'] = array(
			''     => __( 'Default', 'ct-bones' ),
			'auto' => __( 'Auto', 'ct-bones' ),
		);

		$available_columns = range( 2, 6, 1 );
		foreach ( $available_columns as $column ) {
			$field['choices'][ $column ] = sprintf( __( '%s Columns', 'ct-bones' ), $column );
		}

		return $field;
	}
}

Codetot_Acf::instance();
