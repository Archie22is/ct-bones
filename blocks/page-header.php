<?php
$container = 'container';
$_class = 'page-header section';
$_class .= !empty($alignment) ? ' ' . esc_attr($alignment) : '';
$_class .= !empty($class) ? ' ' . esc_attr($class) : '';
do_action('codetot_page_header_before'); ?>
<div class="<?php echo $_class; ?>">
  <div class="<?php echo $container; ?> page-header__container">
    <h1 class="page-header__title"><?php echo $title; ?></h1>
    <?php if (!empty($description)) : ?>
      <div class="page-header__description"><?php echo $description; ?></div>
    <?php endif; ?>
  </div>
</div>
<?php do_action('codetot_page_header_after'); ?>
