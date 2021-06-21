<?php
$has_mega = get_global_option('codetot_enable_mega_menu');

if (!empty($has_mega)):
  if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array(
      'key' => 'group_60d082c3b524e',
      'title' => 'Mega Menu Option',
      'fields' => array(
        array(
          'key' => 'field_60d082d353798',
          'label' => 'Display Mega menu',
          'name' => 'display_mega_menu',
          'type' => 'true_false',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'message' => '',
          'default_value' => 0,
          'ui' => 0,
          'ui_on_text' => '',
          'ui_off_text' => '',
        ),
        array(
          'key' => 'field_60d082fd53799',
          'label' => 'Column',
          'name' => 'column',
          'type' => 'select',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => array(
            array(
              array(
                'field' => 'field_60d082d353798',
                'operator' => '==',
                'value' => '1',
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
    $is_enable = get_field('display_mega_menu', $item);
    $column = get_field('column', $item);

    if ($is_enable == true && $item->menu_item_parent == 0) {
      $item->classes[] = 'has-mega-menu';
      $item->classes[] = 'mega-'.$column['value'];
      $items_has_mega[] = $item;
    }

	}

	return $items;
}
