<?php
$items = codetot_get_contact_info();

if(!empty($items)) : ?>
  <ul class="contact-shortcode<?php if (!empty($class)) : echo ' ' . $class; endif; ?>">
    <?php foreach ( $items as $item ) :
        // Format url
        switch($item['type']) :
          case 'hotline':
            $url = sprintf('tel:%s', $item['url']);
            break;

          case 'email':
            $url = sprintf('mailto:%s', $item['url']);
            break;

          default:
            $url = '';
        endswitch;

        // Get content
        ob_start(); ?>
        <span class="contact-shortcode__icon" aria-hidden="true"><?php codetot_svg($item['type']); ?></span>
        <span class="contact-shortcode__content"><?php echo $item['url']; ?></span>
        <?php $content = ob_get_clean();
      ?>
      <li class="contact-shortcode__item">
        <?php if (!empty($url)) :
          printf('<a class="contact-shortcode__link" href="%1$s" target="%2$s">%3$s</a>',
            $url,
            '_blank',
            $content
          );
        else :
          echo $content;
        endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
