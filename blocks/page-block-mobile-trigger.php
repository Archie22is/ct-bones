<?php if (!empty($button_text)) : ?>
  <button class="page-block__mobile-trigger js-mobile-trigger">
    <?php if (!empty($button_icon)) : ?>
      <span class="button__icon" aria-hidden="true"><?php echo $button_icon; ?></span>
    <?php endif; ?>
    <span class="button__text"><?php echo $button_text; ?></span>
  </button>
<?php endif; ?>
