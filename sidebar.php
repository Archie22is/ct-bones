<?php
/**
 * @package codetot
 * @since 5.0.0
 * @author codetot
 */
$sidebar = codetot_sidebar_id();

if (is_active_sidebar($sidebar)) : ?>

  <?php do_action('codetot_before_sidebar'); ?>

  <aside id="secondary" class="widget-area">
    <?php dynamic_sidebar( $sidebar ); ?>
  </aside><!-- #secondary -->

  <?php do_action('codetot_after_sidebar'); ?>

<?php endif; ?>
