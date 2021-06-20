<?php
$container = 'container';
$_class = !empty($class) ? ' ' . esc_attr($class) : '';

?>
<div class="breadcrumbs<?php echo $_class; ?>">
  <div class="<?php echo $container; ?> breadcrumbs__container">
    <?php codetot_breadcrumbs(); ?>
  </div>
</div>
