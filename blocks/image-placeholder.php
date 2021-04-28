<div class="image-placeholder<?php if (!empty($class)) : echo ' ' . $class; endif; ?>">
  <?php
  if (!empty($logo)) :
    the_block('image', array(
      'image' => $logo['ID'],
      'class' => 'image-placeholder__image',
      'size' => 'full'
    ));
  else :
    echo '<div class="image-placeholder__image">';
    printf('<img class="image__img lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="%1$s" alt="">', get_template_directory_uri() . '/assets/img/default-logo.png');
    echo '</div>';
  endif;
  ?>
</div>
