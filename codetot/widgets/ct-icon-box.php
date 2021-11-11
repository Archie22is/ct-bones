<?php

class codetot_widget_icon extends WP_Widget {

	function __construct() {
		parent::__construct(
			'codetot_widget_icon',
			sprintf( __( '%s Icon Box', 'ct-bones' ), '[CT]' ),
			array(
				'description' => esc_html__( 'Display the guarantee list widget.', 'ct-bones' ),
			)
		);
	}

	function form( $instance ) {
		$default  = array();
		$instance = wp_parse_args( (array) $instance, $default );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		return $instance;
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title  = get_field( 'title', 'widget_' . $args['widget_id'] );
		$items  = get_field( 'items', 'widget_' . $args['widget_id'] );
		$layout = get_field( 'layout', 'widget_' . $args['widget_id'] );

		echo '<div class="widget widget--custom widget--ct_icon_box">';
		if ( ! empty( $title ) ) :
			printf( '<p class="widget__title">%s</p>', $title );
		endif;
		if ( ! empty( $items ) ) {
			echo '<div class="widget--ct_icon_box_main">';
			foreach ( $items as $item ) {
				the_block(
					'ct-widget-icon',
					array(
						'style'       => $layout,
						'icon'        => $item['icon_image'],
						'title'       => $item['title'],
						'description' => $item['description'],
					)
				);
			}
			echo '</div>';
		}
		echo '</div>';
	}
}

add_action( 'widgets_init', 'create_codetot_widget_icon' );
function create_codetot_widget_icon() {
	 register_widget( 'codetot_widget_icon' );
}


if ( function_exists( 'acf_add_local_field_group' ) ) :
	acf_add_local_field_group(
		array(
			'key'                   => 'group_60110eae81354',
			'title'                 => '[CT] Icon Box',
			'fields'                => array(
				array(
					'key'               => 'field_60110f0734a5e',
					'label'             => 'Title',
					'name'              => 'title',
					'type'              => 'text',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => '',
					'prepend'           => '',
					'append'            => '',
					'maxlength'         => '',
				),
				array(
					'key'               => 'field_60823801d34b6',
					'label'             => 'Main Layout',
					'name'              => 'layout',
					'type'              => 'select',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'choices'           => array(
						'left'   => 'Left',
						'center' => 'Center',
						'right'  => 'Right',
					),
					'default_value'     => 'left',
					'allow_null'        => 0,
					'multiple'          => 0,
					'ui'                => 0,
					'return_format'     => 'value',
					'ajax'              => 0,
					'placeholder'       => '',
				),
				array(
					'key'               => 'field_60110f1034a5f',
					'label'             => 'Items',
					'name'              => 'items',
					'type'              => 'repeater',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'collapsed'         => '',
					'min'               => 0,
					'max'               => 0,
					'layout'            => 'block',
					'button_label'      => esc_html__( 'Add Item', 'ct-bones' ),
					'sub_fields'        => array(
						array(
							'key'               => 'field_60110fc034a63',
							'label'             => 'Icon',
							'name'              => 'icon_image',
							'type'              => 'image',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'return_format'     => 'array',
							'preview_size'      => 'medium',
							'library'           => 'all',
							'min_width'         => '',
							'min_height'        => '',
							'min_size'          => '',
							'max_width'         => '',
							'max_height'        => '',
							'max_size'          => '',
							'mime_types'        => '',
						),
						array(
							'key'               => 'field_60110f1c34a60',
							'label'             => 'Title',
							'name'              => 'title',
							'type'              => 'text',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'maxlength'         => '',
						),
						array(
							'key'               => 'field_60110dsa34a60',
							'label'             => 'Description',
							'name'              => 'description',
							'type'              => 'text',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'maxlength'         => '',
						),
					),
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'widget',
						'operator' => '==',
						'value'    => 'codetot_widget_icon',
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
