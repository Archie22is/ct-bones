<?php
$unique_id = !empty($args['id']) ? 'search-form-' . $args['id'] : wp_unique_id('search-form-');
$extra_class = !empty($args['id']) ? ' search-form--' . $args['id'] : '';
$aria_label = __('Search form', 'ct-bones');
$button = apply_filters('codetot_search_button', sprintf('<input type="submit" class="search-submit" value="%1$s">', esc_attr_x( 'Search', 'submit button', 'ct-bones' )));
$placeholder = !empty($args['placeholder']) ? $placeholder : '';
if (empty($placeholder)) {
  if (class_exists('WooCommerce')) {
    $placeholder = __('Search products&hellip;', 'ct-bones');
  } else {
    $placeholder = __('Search&hellip;', 'ct-bones');
  }
}
?>
<form role="search" <?php echo $aria_label; ?> method="get" class="search-form<?php echo $extra_class; ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <label class="screen-reader-text" for="<?php echo esc_attr( $unique_id ); ?>"><?php _e( 'Search&hellip;', 'ct-bones' ); // phpcs:ignore: WordPress.Security.EscapeOutput.UnsafePrintingFunction -- core trusts translations ?></label>
  <input type="search" id="<?php echo esc_attr( $unique_id ); ?>" class="search-field" value="<?php echo get_search_query(); ?>" name="s" placeholder="<?php echo $placeholder; ?>" />
  <?php echo $button; ?>
</form>
