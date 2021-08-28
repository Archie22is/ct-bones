<?php
$enable_home_icon = codetot_get_theme_mod('header_menu_home_icon') ?? false;
?>

<div class="grid__col header__col header__col--navigation">
  <?php do_action('codetot_header_navigation_before'); ?>
  <?php
  if (has_nav_menu('primary')) :
    ob_start();
    echo '<nav class="header__nav"><ul id="menu-main-menu" class="header__menu">';
    if( $enable_home_icon ) {
      echo '<li class="menu-item"><a class ="menu-item__home-url" href="'. esc_url( home_url() ) .'">';
      codetot_svg('home', true);
      echo '</a></li>';
    }
      wp_nav_menu( array(
        'theme_location'  => 'primary',
        'container'       => '__return_false',
        'fallback_cb'     => '__return_false',
        'items_wrap'      => '%3$s',
      ) );
    echo '</ul></nav>';
    $primary_nav_html = ob_get_clean();

    echo $primary_nav_html;

  elseif ( is_user_logged_in() ) :
    printf('<a class="button button--primary" href="%1$s" target="_blank">%2$s</a>', admin_url() . 'menus.php', '<span class="button__text">' . __('Configure Primary Menu', 'ct-bones') . '</span>');
  endif;
  ?>
  <?php do_action('codetot_header_navigation_after'); ?>
</div>
