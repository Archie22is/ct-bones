<?php
$container_class = codetot_site_container();
$_class = !empty($class) ? ' ' . esc_attr($class) : '';

?>
<div class="breadcrumbs<?php echo $_class; ?>">
  <div class="<?php echo $container_class; ?> breadcrumbs__container">
    <?php codetot_breadcrumbs(); ?>
  </div>
</div>
