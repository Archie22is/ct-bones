<?php
$_no_lazyload = isset($lazyload) && $lazyload ? $lazyload : false;
$_size = !empty($size) ? $size : 'full';

if (!empty($image) && !empty($class)) :
  $image_id = false;


  if (!empty($image['ID'])) {
    $image_id = $image['ID'];
  } elseif (is_int( (int) $image_id)) {
    $image_id = $image;
  } else {
    echo '<-- Undefined image -->';
  }
  ?>
  <picture class="image <?php echo $class; ?>">
    <?php
    if (!$_no_lazyload) {
      ob_start();
      echo wp_get_attachment_image($image_id, $_size, null, array(
        'class' => 'image__img lazyload'
      ));
      $image_html = ob_get_clean();
      $image_html = str_replace('srcset="', 'srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-sizes="auto" data-srcset="', $image_html);
      echo $image_html;
    } else {
      $mobile_image = wp_get_attachment_image_src($image_id, 'medium', null);
      $mobile_image_srcset = wp_get_attachment_image_srcset($image_id, ' codetot-small');
      $large_image = wp_get_attachment_image_src($image_id, 'large', null);
      $desktop_image = wp_get_attachment_image_src($image_id, 'full', null);

      if (!empty($large_image)) {
        printf('<source data-srcset="%1$s" media="(min-width: 768px)">', $large_image[0]);
      }

      if (!empty($desktop_image)) {
        printf('<source data-srcset="%s" media="(min-width: 1280px)">', $desktop_image[0]);
      }

      printf('<img class="image__img lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="%1$s" width="%2$s" height="%3$s" alt="%4$s">',
        $mobile_image[0],
        $mobile_image_srcset,
        !empty($mobile_image[1]) ? esc_attr($mobile_image[1]) : 360,
        !empty($mobile_image[2]) ? esc_attr($mobile_image[2]) : 180,
        !empty($mobile_image[3]) ? esc_attr($mobile_image[3]) : ''
      );
    }
    ?>
  </picture>
<?php endif; ?>
