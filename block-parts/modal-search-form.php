<div class="hidden" data-theme-component="modal-search-form">
	<noscript id ="modal-search-form-title"><span class="modal__title"><?php esc_html_e('Type to search', 'ct-bones'); ?></span></noscript>
	<noscript id="modal-search-form">
		<?php
		get_search_form(array(
			'id' => 'modal-search-form',
			'echo' => true
		));
		?>
	</noscript>
	<noscript id="modal-search-form-close-button"><?php codetot_svg('close', true); ?></noscript>
</div>
