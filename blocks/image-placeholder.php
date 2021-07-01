<?php

$custom_logo_id = get_theme_mod('custom_logo');

$_class = 'image-placeholder';
$_class .= !empty($class) ? ' ' . $class : '';

$image_html = '';

if (has_custom_logo() && !empty($custom_logo_id)) :
  $_class .= ' image-placeholder--icon';

  ob_start();
  echo wp_get_attachment_image($custom_logo_id, 'medium', false, array(
    'class' => 'lazyload image-placeholder__image-logo',
    'loading' => false
  ));
  $logo_html = ob_get_clean();

  $logo_html = str_replace(' src="', ' src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="', $logo_html);
  $logo_html = str_replace(' srcset="', ' data-srcset="', $logo_html);

  $image_html = sprintf('<div class="image-placeholder__logo">%s</div>', $logo_html);

else :
  ob_start();
  printf('<figure class="%s %s">', 'image image--cover image-placeholder__image', !empty($image_class) ? ' ' . $image_class : '');
  printf('<img class="image__img lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="%1$s" alt="">', get_template_directory_uri() . '/assets/img/no-image.jpg');
  echo '</figure>';

  $image_html = ob_get_clean();
endif;
?>

<div class="<?php echo $_class; ?>">
  <?php echo $image_html; ?>
</div>
