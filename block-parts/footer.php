<?php
// Default values.
$container = 'container';
$columns = get_global_option('codetot_footer_columns') ? str_replace('-columns', '', get_global_option('codetot_footer_columns')) : 3;
$footer_background = get_global_option('codetot_footer_background_color') ?? 'dark';
$remove_footer_copyright = get_global_option('codetot_settings_remove_theme_copyright') ?? false;
$footer_copyright = codetot_get_footer_copyright();
$hide_social_links = get_global_option('codetot_settings_footer_hide_social_links') ?? false;

$footer_class = 'mt-2 footer';
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

$social_links_html = get_block('social-links', array(
  'class' => 'social-links--dark-contract social-links--footer-bottom'
));

?>
<footer class="<?php echo $footer_class; ?>" role="contentinfo">
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
  <?php if (!$remove_footer_copyright && !empty($footer_copyright) ): ?>
    <div class="footer__bottom">
      <div class="<?php echo $container; ?> footer__container">
        <div class="grid footer__bottom-grid">
          <div class="grid__col footer__bottom-col footer__bottom-col--left">
            <div class="footer__copyright-text"><?php echo $footer_copyright; ?></div>
          </div>
          <?php if (!$hide_social_links && !empty(strip_tags($social_links_html))) : ?>
            <div class="grid__col footer__bottom-col footer__bottom-col--right">
              <?php echo $social_links_html; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
  <?php do_action('codetot_footer_row_bottom'); ?>
</footer>
