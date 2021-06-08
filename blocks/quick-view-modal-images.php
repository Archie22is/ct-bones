<?php
$nav_slider_args = array(
  'cellAlign' => 'left',
  'contain' => true,
  'pageDots' => false,
  'prevNextButtons' => true,
  'percentagePosition' => true,
  'asNavFor' => '.js-slider'
);
if (!empty($product_id)) :
  $product = wc_get_product($product_id);
  $featured_image_id = $product->get_image_id();
  $gallery_image_ids = $product->get_gallery_image_ids();
  if ($gallery_image_ids) array_unshift($gallery_image_ids, $featured_image_id);
  ?>
  <?php if (!empty($gallery_image_ids)) : ?>
  <div class="modal-quick-view__slider-nav js-nav-slider" data-carousel='<?php echo json_encode($nav_slider_args); ?>'>
    <?php foreach ($gallery_image_ids as $index => $image_id) : ?>
      <figure class="modal-quick-view__slider-nav-item">
        <?php echo wp_get_attachment_image($image_id, 'thumbnail', null, array(
          'class' => 'quick-view-modal__thumbnail-image'
        )); ?>
      </figure>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
<?php endif; ?>
