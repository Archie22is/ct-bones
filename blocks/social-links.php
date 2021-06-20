<?php
if (empty($items)) {
  $items = codetot_get_social_links();
}

/**
 * Available classes
 * - social-links--dark-contract (white icon in dark background)
 * - social-links--footer-bottom (display in footer)
 * - social-links--sticky
 */

if (!empty($items)) :
?>
  <div class="social-links<?php if (!empty($class)) : echo ' ' . $class; endif; ?>">
    <p class="social-links__list">
      <?php if (!empty($label)) : ?>
        <span class="label-text social-links__label"><?php echo $label; ?></span>
      <?php endif; ?>
      <?php foreach ($items as $item) : ?>
        <a
        class="social-links__item"
        data-type="<?php echo $item['type']; ?>"
        href="<?php echo !empty($item['url']) ? $item['url'] : '#' ?>"
        target="_blank"
        rel="noreferrer"
        title="<?php printf(__('Visit %s', 'ct-bones'), $item['type']); ?>"
        >
          <span class="social-links__svg"><?php codetot_svg('social-' . $item['type'], true); ?></span>
          <span class="small-text d-none social-links__text"><?php printf(__('Visit %s', 'ct-bones'), $item['type']); ?></span>
        </a>
      <?php endforeach; ?>
    </p>
  </div>
<?php endif; ?>
