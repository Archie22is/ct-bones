<?php
/**
 * Sidebar markup
 *
 * @package codetot
 * @since 5.0.0
 * @author codetot
 */

$ct_bones_sidebar_id          = codetot_sidebar_id();
$ct_bones_sidebar_filter_name = ! empty( $sidebar ) ? str_replace( '-', '_', sanitize_key( $ct_bones_sidebar_id ) ) : '';
$ct_bones_display_sidebar     = ! empty( $ct_bones_sidebar_id ) && is_active_sidebar( $ct_bones_sidebar_id ) && apply_filters( 'codetot_sidebar_display_' . sanitize_key( $ct_bones_sidebar_filter_name ), true );

if ( $ct_bones_display_sidebar ) : ?>

	<?php do_action( 'codetot_before_sidebar' ); ?>

  <aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( $ct_bones_sidebar_id ); ?>
  </aside><!-- #secondary -->

	<?php do_action( 'codetot_after_sidebar' ); ?>

<?php endif; ?>
