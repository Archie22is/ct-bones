<?php
// Default values.
$container = 'container';
$columns = get_global_option('codetot_footer_columns') ? str_replace('-columns', '', get_global_option('codetot_footer_columns')) : 3;
$footer_background = get_global_option('codetot_footer_background_color') ?? 'dark';
$disable_top_footer_spacing = is_page() && rwmb_meta('codetot_disable_footer_top_spacing') ?? false;

$footer_class = 'footer';
$footer_class .= !$disable_top_footer_spacing ? ' mt-2' : '';
$footer_class .= !empty($footer_background) ? ' bg-' . esc_attr($footer_background) : ' bg-dark';
$footer_class .= !empty($columns) ? ' footer--' . $columns . '-columns' : '';

$widgets = array();
for ($widget_index = 1; $widget_index <= $columns; $widget_index++) :
  ob_start();
  dynamic_sidebar('footer-column-' . $widget_index);
  $widget_html = ob_get_clean();

  if (!empty($widget_html)) {
    $widgets[] = '<div class="grid__col footer__col">';
    $widgets[] = $widget_html;
    $widgets[] = '</div>';
  }
endfor;

?>
<footer class="<?php echo $footer_class; ?>">
  <?php do_action('codetot_footer_row_top'); ?>
  <?php if (!empty($widgets)) : ?>
    <div class="footer__top">
      <div class="<?php echo $container; ?> footer__container">
        <div class="grid footer__grid">
          <?php
          // Two hooks to add more columns or rows to this grid
          do_action('codetot_footer_before_footer_col_widgets');
          echo implode('', $widgets);
          do_action('codetot_footer_after_footer_col_widgets');
          ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
  <?php do_action('codetot_footer_row_middle'); ?>
  <?php do_action('codetot_footer_row_bottom'); ?>
</footer>
