<div class="slideout-menu" tabindex="0" data-block="slideout-menu" data-mobile-endpoint="<?php echo rest_url('codetot/v1/get_menu_html'); ?>">
  <div class="slideout-menu__overlay js-mobile-menu-close">
    <button class="slideout-menu__close-button js-mobile-menu-close" aria-label="<?php _e('Close a mobile menu', 'ct-bones'); ?>">
      <?php codetot_svg('close', true); ?>
    </button>
  </div>
  <div class="slideout-menu__wrapper">
    <div class="slideout-menu__inner">
    <?php echo get_search_form(); ?>
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

    </div>
    <div class="slideout-menu__loader">
      <?php the_block('loader', array(
        'class' => 'loader--dark'
      )); ?>
    </div>
  </div>
</div>
