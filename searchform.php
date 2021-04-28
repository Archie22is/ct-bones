<?php
$unique_id = !empty($args['id']) ? 'search-form-' . $args['id'] : wp_unique_id('search-form-');
$extra_class = !empty($args['id']) ? ' search-form--' . $args['id'] : '';
$aria_label = __('Search form', 'ct-theme');
$button = apply_filters('codetot_search_button', sprintf('<input type="submit" class="search-submit" value="%1$s">', esc_attr_x( 'Search', 'submit button', 'ct-theme' )));
$placeholder = !empty($args['placeholder']) ? $placeholder : '';
if (empty($placeholder)) {
  if (class_exists('WooCommerce')) {
    $placeholder = esc_html__('Search products...', 'ct-theme');
  } else {
    $placeholder = esc_html__('Search...', 'ct-theme');
  }
}
?>
<form role="search" <?php echo $aria_label; ?> method="get" class="search-form<?php echo $extra_class; ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <label class="screen-reader-text" for="<?php echo esc_attr( $unique_id ); ?>"><?php _e( 'Search&hellip;', 'ct-theme' ); // phpcs:ignore: WordPress.Security.EscapeOutput.UnsafePrintingFunction -- core trusts translations ?></label>
  <input type="search" id="<?php echo esc_attr( $unique_id ); ?>" class="search-field" value="<?php echo get_search_query(); ?>" name="s" placeholder="<?php echo $placeholder; ?>" />
  <?php echo $button; ?>
</form>
