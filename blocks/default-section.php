<?php
$_attrs = '';
$_attrs .= !empty($id) ? sprintf(' id="%s"', esc_attr($id)) : '';
$_attrs .= !empty($attributes) ? ' ' . $attributes : '';
$container = codetot_site_container();
$_class = 'default-section';
$_class .= !empty($class) ? ' ' . $class : '';

if (!empty($content)) {
  if (!is_array($content)) {
    $_content = $content;
  } else {
    $_content = codetot_build_grid_columns($content, 'default-section');
  }
} else {
  $_content = '';
}

if (!empty($content)) : ?>
  <section class="<?php echo $_class; ?>"<?php if (!empty($_attrs)) : echo ' ' . $_attrs; endif; ?>>
    <?php if (!empty($before_header)) : echo $before_header; endif; ?>
    <?php if (!empty($header)) : ?>
      <div class="default-section__header">
        <div class="<?php echo $container; ?> default-section__container default-section__container--header">
          <div class="default-section__inner default-section__inner--header">
            <?php echo $header; ?>
          </div>
        </div>
      </div>
    <?php endif; ?>
    <?php if (!empty($before_main)) : echo $before_main; endif; ?>
    <div class="default-section__main">
      <div class="<?php echo $container; ?> default-section__container default-section__container--main">
        <div class="default-section__inner default-section__inner--main">
          <?php echo $_content; ?>
        </div>
      </div>
    </div>
    <?php if (!empty($after_main)) : echo $after_main; endif; ?>
    <?php if (!empty($footer)) : ?>
      <div class="default-section__footer">
        <div class="<?php echo $container; ?> default-section__container default-section__container--footer">
          <div class="default-section__inner default-section__inner--footer">
            <?php echo $footer; ?>
          </div>
        </div>
      </div>
    <?php endif; ?>
    <?php if (!empty($after_footer)) : echo $after_footer; endif; ?>
  </section>
<?php endif; ?>
