<?php
$_lazyload = (isset($lazyload) && $lazyload) || !isset($lazyload);
$_size = !empty($size) ? $size : 'full';

if (!empty($image) && !empty($class)) :
  $image_id = false;


  if (!empty($image['ID'])) {
    $image_id = $image['ID'];
  } elseif (is_int( (int) $image) ) {
    $image_id = $image;
  } else {
    echo '<-- Undefined image -->';

  }
  ?>
  <picture class="image <?php echo $class; ?>">
    <?php

    if ($_lazyload) :
      $image_html = wp_get_attachment_image($image_id, $_size, false, array(
        'loading' => false,
        'class' => 'wp-post-image image__img lazyload',
      ));

      $image_html = str_replace('srcset="', 'srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-srcset="', $image_html);
      $image_html = str_replace(' data-srcset="', ' data-sizes="auto" data-srcset="', $image_html);
    else :
      $image_html = wp_get_attachment_image($image_id, $_size, false, array(
        'loading' => false,
        'class' => 'wp-post-image image__img',
      ));
    endif;

    echo $image_html;
    ?>
  </picture>
<?php endif; ?>
