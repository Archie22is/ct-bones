<div class="quick-view-modal" data-woocommerce-block="quick-view-modal">
  <div class="quick-view-modal__wrapper" role="document">
    <button class="quick-view-modal__close-button js-close-modal" aria-label="<?php _e('Close', 'codetot-woocommerce'); ?>">
      <?php codetot_svg('close', true); ?>
    </button>
    <div class="quick-view-modal__inner">
      <div class="f fw quick-view-modal__grid">
        <div class="rel quick-view-modal__col quick-view-modal__col--left">
          <div class="quick-view-modal__inner-slider">
            <div class="w1 quick-view-modal__slider-wrapper js-slider-wrapper"></div>
            <?php the_block('loader', array(
              'class' => 'loader--dark'
            )); ?>
          </div>
        </div>
        <div class="f fdc quick-view-modal__col quick-view-modal__col--right">
          <div class="quick-view-modal__box">
            <div class="rel w1 quick-view-modal__main">
              <div class="quick-view-modal__content-wrapper js-content"></div>
              <?php the_block('loader', array(
                'class' => 'loader--dark'
              )); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
