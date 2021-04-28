<?php
$_attrs = '';
$_attrs .= !empty($id) ? sprintf(' id="%s"', $id) : '';
$_attrs .= !empty($attributes) ? ' ' . $attributes : '';

$_class = 'sidebar-section';
$_class .= !empty($class) ? ' ' . $class : '';

if (!empty($sidebar) && !empty($content)) : ?>
  <section class="<?php echo $_class; ?>"<?php if (!empty($_attrs)) : echo ' ' . $_attrs; endif; ?>>
    <div class="container sidebar-section__container">
      <div class="grid sidebar-section__block-grid">
        <div class="grid__col sidebar-section__block sidebar-section__block--sidebar">
          <div class="sidebar-section__inner">
            <?php echo $sidebar; ?>
          </div>
        </div>
        <div class="grid__col sidebar-section__block sidebar-section__block--content">
          <div class="sidebar-section__inner">
            <?php echo $content; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>
