<?php
$slider_args = array(
  'cellAlign' => 'left',
  'contain' => true,
  'pageDots' => false,
  'prevNextButtons' => true,
  'adaptiveHeight' => true,
);

if (!empty($product_id)) :
  the_block('product-gallery', array(
    'class' => 'product-gallery--quick-view-modal'
  ));
endif; ?>
