<?php
function cptui_register_my_cpts_store() {

	/**
	 * Post Type: Store
	 */

	$labels = [
		"name" => __( "Store", "ct-bones" ),
		"singular_name" => __( "Store", "ct-bones" ),
	];

	$args = [
		"label" => __( "Store", "ct-bones" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "store", "with_front" => true ],
		"query_var" => true,
		"menu_icon" => "dashicons-admin-multisite",
		"supports" => [ "title" ],
		"taxonomies" => [ "store_locator" ],
	];

	register_post_type( "store", $args );
}

add_action( 'init', 'cptui_register_my_cpts_store' );

function cptui_register_my_taxes_store_locator() {

	/**
	 * Taxonomy: Store Locator.
	 */

	$labels = [
		"name" => __( "Store Locator", "ct-bones" ),
		"singular_name" => __( "Store Locator", "ct-bones" ),
	];

	$args = [
		"label" => __( "Store Locator", "ct-bones" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'store_locator', 'with_front' => true,  'hierarchical' => true, ],
		"show_admin_column" => true,
		"show_in_rest" => true,
		"rest_base" => "store_locator",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => true,
			];
	register_taxonomy( "store_locator", [ "store" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes_store_locator' );

if( function_exists('acf_add_local_field_group') ):

  acf_add_local_field_group(array(
    'key' => 'group_606597e9b0587',
    'title' => 'Store Locator data',
    'fields' => array(
      array(
        'key' => 'field_606597f93c5ed',
        'label' => 'Address',
        'name' => 'address',
        'type' => 'google_map',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'center_lat' => '',
        'center_lng' => '',
        'zoom' => '',
        'height' => '',
      ),
      array(
        'key' => 'field_606598bc3c5ee',
        'label' => 'Hotline',
        'name' => 'hotline',
        'type' => 'text',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'maxlength' => '',
      ),
      array(
        'key' => 'field_5fd33b339yyy7',
        'label' => 'Button Text',
        'name' => 'button_text',
        'type' => 'text',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => ''
        ),
        'default_value' => '',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'maxlength' => ''
      ),
      array(
        'key' => 'field_5fd33b444054b',
        'label' => 'Button URL',
        'name' => 'button_url',
        'type' => 'text',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => ''
        ),
        'post_type' => '',
        'taxonomy' => '',
        'allow_null' => 0,
        'allow_archives' => 1,
        'multiple' => 0
      ),
      array(
        'key' => 'field_5fcf39a73335',
        'label' => 'Target',
        'name' => 'target',
        'type' => 'select',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => ''
        ),
        'acfe_permissions' => '',
        'choices' => array(
          '_self' => 'Same window',
          '_blank' => 'New Tab'
        ),
        'default_value' => '_self',
        'allow_null' => 0,
        'multiple' => 0,
        'ui' => 0,
        'return_format' => 'value',
        'ajax' => 0,
        'placeholder' => ''
      ),
      array(
        'key' => 'field_5fd33b34545rf',
        'label' => 'Button Style',
        'name' => 'button_style',
        'type' => 'select',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => ''
        ),
        'choices' => array(),
        'default_value' => false,
        'allow_null' => 0,
        'multiple' => 0,
        'ui' => 0,
        'return_format' => 'value',
        'ajax' => 0,
        'placeholder' => ''
      )
    ),
    'location' => array(
      array(
        array(
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'store',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
  ));

  endif;
