<?php
/**
 * Search form markup
 *
 * @package Codetot
 * @author CODE TOT JSC <dev@codetot.com>
 * @since 0.0.1
 */

$ct_bones_unique_id   = ! empty( $args['id'] ) ? 'search-form-' . $args['id'] : wp_unique_id( 'search-form-' );
$ct_bones_extra_class = ! empty( $args['id'] ) ? ' search-form--' . esc_html( $args['id'] ) : '';
$ct_bones_button_html = apply_filters( 'codetot_search_button', sprintf( '<input type="submit" class="search-submit" value="%1$s">', esc_attr_x( 'Search', 'submit button', 'ct-bones' ) ) );
$ct_bones_placeholder = ! empty( $args['placeholder'] ) ? $ct_bones_placeholder : '';
if ( empty( $ct_bones_placeholder ) ) {
	if ( class_exists( 'WooCommerce' ) ) {
		$ct_bones_placeholder = __( 'Search products&hellip;', 'ct-bones' );
	} else {
		$ct_bones_placeholder = __( 'Search&hellip;', 'ct-bones' );
	}
}
?>
<form role="search" method="get" class="search-form<?php echo esc_html( $ct_bones_extra_class ); ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $ct_bones_unique_id ); ?>"><?php _e( 'Search&hellip;', 'ct-bones' ); // phpcs:ignore: WordPress.Security.EscapeOutput.UnsafePrintingFunction -- core trusts translations ?></label>
	<input type="search" id="<?php echo esc_attr( $ct_bones_unique_id ); ?>" class="search-field" value="<?php echo get_search_query(); ?>" name="s" placeholder="<?php echo esc_html( $ct_bones_placeholder ); ?>" />
	<?php echo $ct_bones_button_html; // phpcs:ignore: WordPress.Security.EscapeOutput.OutputNotEscaped -- ?>
</form>
