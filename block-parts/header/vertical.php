<?php
$menu_location = 'vertical_menu';
$locations     = get_nav_menu_locations();
$menu          = ! empty( $locations[ $menu_location ] ) ? wp_get_nav_menu_object( $locations[ $menu_location ] ) : null;
if ( has_nav_menu( 'vertical_menu' ) ) : ?>
  <div class="grid__col header__col header__col--vertical">
  <div class="header__vertical-wrapper">
	<div class="header__vertical-header">
	  <span class="header__vertical-icon"><?php codetot_svg( 'menu', true ); ?></span>
	  <span class="header__vertical-title"><?php echo ! empty( $menu ) && is_object( $menu ) ? $menu->name : ''; ?></span>
	</div>
	<div class="header__vertical-list">
	  <?php 
		if ( has_nav_menu( $menu_location ) ) :
			wp_nav_menu(
				array(
					'theme_location' => $menu_location,
					'container'      => false,
					'menu_class'     => 'header__vertical_menu',
				)
			);
	  endif; 
		?>
	</div>
	</div>
  </div>
<?php endif; ?>
