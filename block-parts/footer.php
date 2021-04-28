<?php
// Default values.
$container_class = codetot_site_container();
$columns = codetot_get_footer_columns();
$footer_background = codetot_get_footer_background_color();
$remove_footer_copyright = codetot_remove_footer_copyright();
$footer_copyright = codetot_get_footer_copyright();
$hide_social_links = codetot_footer_hide_social_links();

$footer_class = 'footer';
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
  <?php if (!empty($widgets)) : ?>
    <div class="footer__top">
      <div class="<?php echo $container_class; ?> footer__container">
        <div class="grid footer__grid">
          <?php
          echo implode('', $widgets);
          ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
  <?php if (!$remove_footer_copyright && !empty($footer_copyright) ): ?>
    <div class="footer__bottom">
      <div class="<?php echo $container_class; ?> footer__container">
        <div class="grid footer__bottom-grid">
          <div class="grid__col footer__bottom-col footer__bottom-col--left">
            <div class="footer__copyright-text"><?php echo $footer_copyright; ?></div>
          </div>
          <?php if ($hide_social_links === true && !empty($social_links_html)) : ?>
            <div class="grid__col footer__bottom-col footer__bottom-col--right">
              <?php
              echo $social_links_html;
              ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
</footer>
