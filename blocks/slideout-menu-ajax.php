<?php echo get_search_form(array('id' => 'slideout-menu-ajax')); ?>
<?php if (has_nav_menu('primary')) :
  ob_start();
  wp_nav_menu(array(
    'theme_location' => 'primary',
    'container' => 'nav',
    'container_class' => 'slideout-menu__nav',
    'menu_class' => 'slideout-menu__menu'
  ));
  $primary_nav = ob_get_clean();

  echo $primary_nav;
endif; ?>
