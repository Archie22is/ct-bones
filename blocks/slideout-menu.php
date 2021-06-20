<div class="slideout-menu d-none" tabindex="0" data-block="slideout-menu">
  <div class="slideout-menu__overlay js-mobile-menu-close">
    <button class="slideout-menu__close-button js-mobile-menu-close" aria-label="<?php _e('Close a mobile menu', 'ct-bones'); ?>">
      <?php codetot_svg('close', true); ?>
    </button>
  </div>
  <div class="slideout-menu__wrapper">
    <div class="slideout-menu__inner">
      <?php
      echo apply_filters('codetot_slideout_menu_search', get_search_form(array('id' => 'slideout-menu')));
      if ( function_exists('icl_object_id') ) {
        echo apply_filters('codetot_slideout_menu_wpml_flags', do_shortcode( '[wpml_language_switcher type="footer" flags=1 native=0 translated=0][/wpml_language_switcher]' ));
      }
      ?>
      <?php
      $default_menu_location = !empty($menu) ? $menu : 'primary';
      $menu_location = apply_filters('codetot_slideout_menu_location', $default_menu_location);

      ob_start();
      if (has_nav_menu($menu_location)) :
        wp_nav_menu(array(
          'theme_location' => $menu_location,
          'container' => 'nav',
          'container_class' => 'slideout-menu__nav',
          'menu_class' => 'slideout-menu__menu'
        ));
      else :
        if (is_user_logged_in()) :
          printf('<p>%s</p>', __('You need to configure menu.', 'ct-bones'));
        endif;
      endif;
      $menu_html = ob_get_clean();

      echo apply_filters('codetot_slideout_menu_html', $menu_html);
      ?>
    </div>
  </div>
</div>
