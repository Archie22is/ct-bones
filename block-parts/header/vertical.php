<?php
$locations = get_nav_menu_locations();
$menu = wp_get_nav_menu_object($locations['vertical_menu']);
  if (has_nav_menu('vertical_menu')) : ?>
  <div class="grid__col header__col header__col--vertical">
  <div class="header__vertical-wrapper">
    <div class="header__vertical-header">
      <span class="header__vertical-icon"><?php codetot_svg('menu', true); ?></span>
      <span class="header__vertical-title"><?php echo $menu->name; ?></span>
    </div>
    <div class="header__vertical-list">
    <?php if (has_nav_menu('primary')) :
      ob_start();
      wp_nav_menu(array(
        'theme_location' => 'vertical_menu',
        'container' => false,
        'menu_class' => 'header__vertical_menu'
      ));
      $primary_nav_html = ob_get_clean();

      echo $primary_nav_html;
    endif; ?>
    </div>
    </div>
  </div>
<?php endif; ?>
