<?php
$hide = get_global_option('codetot_header_hide_search_icon') ?? false;
if (!$hide) : ?>
  <button class="header__menu-icons__item header__menu-icons__item--search header__menu-icons__button" data-open-modal="modal-search-form">
    <span class="header__menu-icons__icon" aria-hidden="true"><?php codetot_svg('search', true); ?></span>
    <span class="screen-reader-text"><?php _e('Search', 'ct-bones'); ?></span>
  </button>
<?php endif; ?>
