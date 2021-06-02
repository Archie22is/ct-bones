<div class="slideout-menu d-none" tabindex="0" data-block="slideout-menu">
  <div class="slideout-menu__overlay js-mobile-menu-close">
    <button class="slideout-menu__close-button js-mobile-menu-close" aria-label="<?php _e('Close a mobile menu', 'ct-bones'); ?>">
      <?php codetot_svg('close', true); ?>
    </button>
  </div>
  <div class="slideout-menu__wrapper">
    <div class="slideout-menu__inner">
      <?php
      echo get_search_form(array('id' => 'slideout-menu'));
      if ( function_exists('icl_object_id') ) {
        echo do_shortcode( '[wpml_language_switcher type="footer" flags=1 native=0 translated=0][/wpml_language_switcher]' );
      }
      ?>
      <?php if (has_nav_menu('primary')) :
        wp_nav_menu(array(
          'theme_location' => 'primary',
          'container' => 'nav',
          'container_class' => 'slideout-menu__nav',
          'menu_class' => 'slideout-menu__menu'
        ));
      endif; ?>
    </div>
  </div>
</div>
