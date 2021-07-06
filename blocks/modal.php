<?php

/**
 * We can't print out modal, so take out the
 */
if (empty($id)) {
  $error = new WP_Error('404', 'Missing modal id');

  echo $error->get_error_message();
  die();
}

if (empty($content)) {
  $error = new WP_Error('404', 'Missing modal content');

  echo $error->get_error_message();
  die();
}

$_class = 'modal';
$_class .= !empty($class) ? ' ' . $class: '';

$_close_button_class = !empty($close_button_class) ? $close_button_class : 'js-close-button';

$_attributes = sprintf('id="%s" role="dialog"', $id);
$_attributes .= !empty($attributes) ? ' ' . $attributes : '';

if (!empty($id) && !empty($content)) :
?>
  <div class="<?php echo $_class; ?>" <?php echo $_attributes; ?>>
    <div class="modal__wrapper">
      <?php if (!empty($header)) : ?>
        <div class="modal__header">
          <?php echo $header; ?>
        </div>
      <?php endif; ?>
      <div class="modal__content js-content<?php if (isset($lazyload)) : echo ' is-not-loaded'; endif; ?>">
        <?php echo $content; ?>
      </div>
      <?php if (!isset($hide_close_button)) : ?>
        <button class="modal__close-button <?php echo $_close_button_class; ?>" data-close-modal="<?php echo $id; ?>" aria-label="<?php _e('Close a modal', 'ct-blocks'); ?>">
          <?php codetot_svg('close', true); ?>
        </button>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>
