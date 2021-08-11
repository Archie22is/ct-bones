<?php if ( !empty($items) ) :

  $_class = 'menu-horizontal';
  $_class .= !empty($class) ? ' ' . $class : '';

  $_item_type = !empty($item_type) ? $item_type : '';

  ob_start();

  echo '<ul class="list-reset f fw menu-horizontal__list">';
  foreach ($items as $item) :
    $item_class = 'menu-horizontal__item';
    $item_class .= isset($item['is_active']) && $item['is_active'] ? ' is-active' : '';
    $item_class .= !empty($item['class']) ? ' ' . $item['class'] : '';

    printf('<li class="%s">', $item_class);
    switch ($_item_type) :
      case 'button':
        if (empty($item['button_text'])) {
          $error = new \WP_Error(400, __('Missing item button text.', 'ct-bones'));

          echo $error->get_error_message();
        }

        the_block('button', array(
          'button' => !empty($item['button_text']) ? $item['button_text'] : '',
          'url' => !empty($item['button_url']) ? $item['button_url'] : '',
          'type' => !empty($item_button_type) ? $item_button_type : '',
          'class' => sprintf('menu-horizontal__button %s', $item_class)
        ));
        break;

      default:
        if (empty($item['name'])) {
          $error = new \WP_Error(400, __('Missing item name.', 'ct-bones'));

          echo $error->get_error_message();
        }

        printf('<a href="%1$s" target="%2$s" rel="%3$s" class="%4$s">%5$s</a>',
          !empty($item['url']) ? esc_url($item['url']) : '',
          !empty($item['target']) ? $item['target'] : '_self',
          !empty($item['rel']) ? $item['rel'] : '',
          'menu-horizontal__link',
          !empty($item['name']) ? $item['name'] : ''
        );

    endswitch;
    echo '</li>';
  endforeach;
  echo '</ul>';

  $content = ob_get_clean();

  the_block('default-section', array(
    'class' => $_class,
    'content' => $content
  ));

endif;
