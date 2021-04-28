<?php



ob_start();
?>
<span class="modal__title"><?php esc_html_e('Type to search', 'ct-theme'); ?></span>
<button class="modal__close-button" data-close-modal="modal-search-form" aria-label="<?php esc_html_e('Close a search form modal', 'ct-theme'); ?>">
  <?php codetot_svg('close', true); ?>
</button>
<?php
$header = ob_get_clean();

ob_start();
get_search_form(array(
  'id' => 'modal-search-form',
  'echo' => true
));
$content = ob_get_clean();

the_block('modal', array(
  'id' => 'modal-search',
  'class' => 'modal--search-form',
  'hide_close_button' => true,
  'attributes' => 'data-block="modal-search-form"',
  'header' => $header,
  'content' => $content
));
