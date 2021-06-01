<div class="image-placeholder<?php if (!empty($class)) : echo ' ' . $class; endif; ?>">
  <?php
  if (has_custom_logo()) :
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    the_block('image', array(
      'image' => $custom_logo_id,
      'class' => 'image-placeholder__image',
      'size' => 'full'
    ));
  else :
    echo '<div class="image-placeholder__image">';
    printf('<img class="image__img lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="%1$s" alt="">', get_template_directory_uri() . '/assets/img/no-image.png');
    echo '</div>';
  endif;
  ?>
</div>
