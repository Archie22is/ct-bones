<?php
/**
 * @package codetot
 * @since 5.0.0
 * @author codetot
 */
$sidebar = codetot_sidebar_id();
$sidebar_filter_name = !empty($sidebar) ? str_replace('-', '_', $sidebar) : '';
// Example sidebar filter: codetot_sidebar_display_post_category_sidebar, codetot_sidebar_display_shop_sidebar
$display_sidebar = !empty($sidebar) && is_active_sidebar($sidebar) && apply_filters('codetot_sidebar_display_' . sanitize_key($sidebar_filter_name), true);

if ($display_sidebar) : ?>

  <?php do_action('codetot_before_sidebar'); ?>

  <aside id="secondary" class="widget-area">
    <?php dynamic_sidebar($sidebar); ?>
  </aside><!-- #secondary -->

  <?php do_action('codetot_after_sidebar'); ?>

<?php endif; ?>
