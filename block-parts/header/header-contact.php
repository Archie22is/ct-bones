<?php
$items = codetot_get_contact_info();

if(!empty($items)) : ?>
  <ul class="header-contact">
    <?php foreach ( $items as $item ) : ?>
      <li class="header-contact__item">
        <?php if($item['type'] === 'hotline') : ?>
          <a href="tel:<?php echo $item['url']; ?>">
        <?php elseif($item['type'] === 'email') : ?>
          <a href="mailto:<?php echo $item['url']; ?>">
        <?php endif; ?>
            <span class="header-contact__icon"><?php codetot_svg($item['type']); ?></span>
            <span class="header-contact__content"><?php echo $item['url']; ?></span>
        <?php if($item['type'] === 'hotline' || $item['type'] === 'email') : ?>
          </a>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
