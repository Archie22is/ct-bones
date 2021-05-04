<?php
/**
 * @package codetot
 * @since 5.0.0
 * @author codetot
 */
do_action('codetot_before_sidebar');

$sidebar = codetot_sidebar_id();

?>

<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( $sidebar ); ?>
</aside><!-- #secondary -->

<?php do_action('codetot_after_sidebar'); ?>
