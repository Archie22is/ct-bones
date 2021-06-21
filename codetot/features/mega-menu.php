<?php
$has_mega = get_global_option('codetot_enable_mega_menu');

if (!empty($has_mega)):
  if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
      'key' => 'group_60d0038bcb6fc',
      'title' => 'Mega Menu Option',
      'fields' => array(
        array(
          'key' => 'field_60d0039c21447',
          'label' => 'Show Mega Menu',
          'name' => 'show_mega',
          'type' => 'radio',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'choices' => array(
            'yes' => 'Yes',
            'no' => 'No',
          ),
          'allow_null' => 0,
          'other_choice' => 0,
          'default_value' => 'no',
          'layout' => 'horizontal',
          'return_format' => 'array',
          'save_other_choice' => 0,
        ),
        array(
          'key' => 'field_60d0083734b32',
          'label' => 'Column',
          'name' => 'column',
          'type' => 'select',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => array(
            array(
              array(
                'field' => 'field_60d0039c21447',
                'operator' => '==',
                'value' => 'Yes',
              ),
            ),
          ),
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'choices' => array(
            'col-2' => '2 Column',
            'col-3' => '3 Column',
            'col-4' => '4 Column',
            'col-5' => '5 Column',
            'col-6' => '6 Column',
          ),
          'default_value' => false,
          'allow_null' => 0,
          'multiple' => 0,
          'ui' => 0,
          'return_format' => 'array',
          'ajax' => 0,
          'placeholder' => '',
        ),
      ),
      'location' => array(
        array(
          array(
            'param' => 'nav_menu_item',
            'operator' => '==',
            'value' => 'location/primary',
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
endif;


add_filter('wp_nav_menu_objects', 'codetot_wp_nav_menu_objects', 10, 2);
function codetot_wp_nav_menu_objects( $items, $args ) {
	foreach( $items as &$item ) {
    $is_enable = get_field('show_mega_menu', $item);
    $column = get_field('column', $item);

    if ($is_enable == 'Yes' && $item->menu_item_parent == 0) {
      $item->classes[] = 'has-mega-menu';
      $item->classes[] = 'mega-'.$column['value'];
      $items_has_mega[] = $item;
    }

	}

	return $items;
}
