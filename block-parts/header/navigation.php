<div class="grid__col header__col header__col--navigation">
  <?php do_action('codetot_header_navigation_before'); ?>
  <?php
  if (has_nav_menu('primary')) :
    ob_start();
    wp_nav_menu(array(
      'theme_location' => 'primary',
      'container' => 'nav',
      'container_class' => 'header__nav',
      'menu_class' => 'header__menu'
    ));
    $primary_nav_html = ob_get_clean();

    echo $primary_nav_html;

  elseif ( is_user_logged_in() ) :
    printf('<a class="button button--primary" href="%1$s" target="_blank">%2$s</a>', admin_url() . 'menus.php', '<span class="button__text">' . __('Configure Primary Menu', 'ct-theme') . '</span>');
  endif;
  ?>
  <?php do_action('codetot_header_navigation_after'); ?>
</div>
