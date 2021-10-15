<?php
$default_menu_location = !empty($menu) ? $menu : 'primary';
$menu_location = apply_filters('codetot_slideout_menu_location', $default_menu_location);
?>

<div class="hidden" data-theme-component="slideout-menu">
	<noscript id="slideout-menu-close-button"><?php codetot_svg('close', true); ?></noscript>
	<noscript id="slideout-menu-search-form"><?php echo apply_filters('codetot_slideout_menu_search', get_search_form(array('id' => 'slideout-menu'))); ?></noscript>
	<?php if ( function_exists('icl_object_id') ) : ?>
		<noscript id="slideout-menu-wpml-language-flags"><?php echo apply_filters('codetot_slideout_menu_wpml_flags', do_shortcode( '[wpml_language_switcher type="footer" flags=1 native=0 translated=0][/wpml_language_switcher]' )); ?></noscript>
	<?php endif; ?>
	<?php if (has_nav_menu($menu_location)) : ?>
		<noscript id="slideout-menu-menu">
			<?php
			ob_start();
			if (has_nav_menu($menu_location)) :
				wp_nav_menu(array(
					'theme_location' => $menu_location,
					'container' => 'nav',
					'container_class' => 'slideout-menu__nav',
					'menu_class' => 'slideout-menu__menu'
				));
			endif;
			$menu_html = ob_get_clean();

			echo apply_filters('codetot_slideout_menu_html', $menu_html);
			?>
		</noscript>
	<?php endif; ?>
</div>
