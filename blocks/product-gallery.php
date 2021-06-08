<?php
global $product;
$featured_image_id = $product->get_image_id();
$featured_image_url = wp_get_attachment_url($featured_image_id);
$gallery_image_ids = $product->get_gallery_image_ids();

$slider_args = array(
  'cellAlign' => 'left',
  'contain' => true,
  'pageDots' => false,
  'prevNextButtons' => false,
  'sync' => '.js-slider-nav',
  'adaptiveHeight' => true,
);

$nav_slider_args = array(
  'cellAlign' => 'left',
  'contain' => true,
  'pageDots' => false,
  'prevNextButtons' => true,
  'percentagePosition' => true
);

?>

<div class="images product-gallery<?php if (!empty($class)) : echo ' ' . $class; endif; ?>" data-woocommerce-block="product-gallery">
  <div class="product-gallery__inner">
    <?php if (!empty($gallery_image_ids)) : ?>
      <div class="product-gallery__slider js-slider" data-carousel='<?php echo json_encode($slider_args); ?>'>
        <!-- Display featured image when first load -->
        <a class="d-block w100 product-gallery__slider-item" data-fancybox="img" href="<?php echo wp_get_attachment_url($featured_image_id); ?>" title="<?php _e('View a product image in the popup', 'ct-bones'); ?>">
          <figure class="w100 woocommerce-product-gallery__image product-gallery__slider-image">
            <img src="<?php echo $featured_image_url; ?>" class="mw-100 wp-post-image" alt="">
          </figure>
        </a>
        <?php if (!empty($gallery_image_ids)) :
          foreach ($gallery_image_ids as $gallery_image_id) :
            $image_url = wp_get_attachment_url($gallery_image_id); ?>
            <a class="d-block w100 product-gallery__slider-item" data-fancybox="img" href="<?php echo $image_url; ?>" title="<?php _e('View product thumbnail', 'ct-bones'); ?>">
              <figure class="woocommerce-product-gallery__image product-gallery__slider-image">
                <img src="<?php echo $image_url; ?>" class="mw-100 wp-post-image" alt="">
              </figure>
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <?php if (!empty($gallery_image_ids)) : ?>
        <ul class="flex-control-nav mt-1 product-gallery__slider-nav js-nav-slider" data-carousel='<?php echo json_encode($nav_slider_args); ?>'>
          <div class="product-gallery__slider-nav-item">
            <figure class="image image--cover image--loaded product-gallery__slider-nav-image">
              <img src="<?php echo $featured_image_url; ?>" class="image__img mw-100" alt="">
            </figure>
          </div>
          <?php foreach ($gallery_image_ids as $gallery_image_id) : ?>
            <div class="product-gallery__slider-nav-item">
              <figure class="image image--cover image--loaded product-gallery__slider-nav-image">
                <?php echo wp_get_attachment_image($gallery_image_id, 'thumbnail', null, array(
                  'class' => 'image__img mw-100'
                )); ?>
              </figure>
            </div>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    <?php else : ?>
      <a class="d-block w100 product-gallery__single-image" data-fancybox="img" href="<?php echo $image_link = wp_get_attachment_url($featured_image_id); ?>" title="<?php _e('View a product image in the popup', 'namquang'); ?>">
        <figure class="w100 woocommerce-product-gallery__image">
          <img src="<?php echo $featured_image_url; ?>" class="mw-100 wp-post-image" alt="">
        </figure>
      </a>
    <?php endif; ?>
  </div>
</div>
