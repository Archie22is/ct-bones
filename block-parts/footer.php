<?php
// Default values.
$footer_widget_column = codetot_get_theme_mod('footer_widget_column') ?? '3-col';
$footer_widget_column = str_replace('-col', '', $footer_widget_column);
$footer_text_contract = codetot_get_theme_mod('footer_text_contract') ?? 'light';
$footer_background = codetot_get_theme_mod('footer_background_color') ?? 'transparent';

$footer_class = 'footer';
$footer_class .= !empty($footer_background) ? ' bg-' . esc_attr($footer_background) : ' bg-transparent';
$footer_class .= !empty($footer_widget_column) ? ' footer--' . esc_html($footer_widget_column) . '-columns' : ' footer--3-columns';

$widgets = array();
for ($widget_index = 1; $widget_index <= $footer_widget_column; $widget_index++) :
  ob_start();
  dynamic_sidebar('footer-column-' . $widget_index);
  $widget_html = ob_get_clean();

  if (!empty($widget_html)) {
    $widgets[] = '<div class="grid__col footer__col footer__col--widget">';
    $widgets[] = $widget_html;
    $widgets[] = '</div>';
  }
endfor;

?>
<footer class="<?php echo $footer_class; ?>">
  <?php do_action('codetot_footer_row_top'); ?>
  <?php if (!empty($widgets)) : ?>
    <div class="footer__top">
      <div class="container footer__container">
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
