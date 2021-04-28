<?php
$default_attr = ' data-block="header"';
$header_attr = apply_filters('codetot_header_attributes', $default_attr);
$enable_sticky = get_global_option('codetot_header_enable_sticky') ?? false;
$header_attr .= $enable_sticky ? ' data-sticky-header': '';
$header_class = function_exists('codetot_header_class') ? codetot_header_class() : 'header';
?>

<header id="masthead" class="<?php echo $header_class; ?>" <?php echo $header_attr; ?>>
  <?php do_action('codetot_header'); ?>
</header>
