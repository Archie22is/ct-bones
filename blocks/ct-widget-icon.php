<?php

$_class = 'ct-widget-icon';
$_class .= !empty($style) ? ' ct-widget-icon--' . $style: '';

if(!empty($title) || !empty($description) || !empty($icon)) :
  ?>
<div class="<?php echo $_class; ?>">
<?php if(!empty($icon)) : ?>
  <div class="ct-widget-icon__icon">
    <?php
      the_block('image', array(
        'image' => $icon,
        'class' => 'ct-widget-icon__image'
      ));
    ?>
  </div>
  <?php endif;
    if(!empty($title) || !empty($description)) :
  ?>
  <div class="ct-widget-icon__content">
  <?php if(!empty($title)) :
  ?>
    <p class="label-text bold-text uppercase-text ct-widget-icon__title"><?php echo $title; ?></p>
    <?php
      endif;
      if(!empty($description)) :
    ?>
      <div class="small-text ct-widget-icon__description"><?php echo $description; ?></div>
    <?php endif; ?>
  </div>
  <?php endif; ?>
</div>
<?php endif; ?>
