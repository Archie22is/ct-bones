<div class="slideout-menu" tabindex="0" data-block="slideout-menu" data-mobile-endpoint="<?php echo rest_url('codetot/v1/get_menu_html'); ?>">
  <div class="slideout-menu__overlay js-mobile-menu-close">
    <button class="slideout-menu__close-button js-mobile-menu-close" aria-label="<?php _e('Close a mobile menu', 'ct-theme'); ?>">
      <?php codetot_svg('close', true); ?>
    </button>
  </div>
  <div class="slideout-menu__wrapper">
    <div class="slideout-menu__inner js-menu-wrapper">
      <?php // Ajax load ?>
    </div>
    <div class="slideout-menu__loader">
      <?php the_block('loader', array(
        'class' => 'loader--dark'
      )); ?>
    </div>
  </div>
</div>
