<?php
$hide_icon = codetot_get_theme_mod( 'header_hide_search_icon' ) ?? false;
if ( ! $hide_icon ) :
	echo apply_filters(
		'codetot_header_search_button',
		do_shortcode(
			'[search-icon
    button_class="header__menu-icons__item header__menu-icons__item--search header__menu-icons__button"
    button_attributes="data-open-modal="modal-search-form"
    span_class="header__menu-icons__icon"
  ]'
		)
	);
endif; 
