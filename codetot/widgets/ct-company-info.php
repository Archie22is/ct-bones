<?php

class codetot_widget_company_info extends WP_Widget
{
  function __construct()
  {
    parent::__construct(
      'codetot_widget_company_info',
      '[CT] Company Info',
      array(
        'classname' => 'widget_codetot_widget_company_info'
      )
    );
  }
  function form($instance)
  {
    $default = array();
    $instance = wp_parse_args((array) $instance, $default);
  }
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;

    return $instance;
  }
  function widget($args, $instance)
  {
    // var_dump($args);

    extract($args);

    $address = get_field('address', 'widget_' . $widget_id);
    $phone = get_field('phone', 'widget_' . $widget_id);
    $email = get_field('email', 'widget_' . $widget_id);
    $zalo = get_field('zalo', 'widget_' . $widget_id);
    $messeger = get_field('messeger', 'widget_' . $widget_id);

    $theme = get_field('theme', 'widget_' . $widget_id) ?? 'default';
    $title = get_field('title', 'widget_' . $widget_id);
    $about = get_field('about', 'widget_' . $widget_id);

    $links = [];
    if (!empty($phone)) {
      $links[] = array(
        'type' => 'phone',
        'icon' => 'hotline',
        'title' => sprintf('Call %s', $phone),
        'url' => sprintf('tel:%s', trim($phone)),
        'content' => esc_html($phone)
      );
    }

    if (!empty($address)) {
      $links[] = array(
        'type' => 'address',
        'icon' => 'address',
        'content' => $address
      );
    }

    if (!empty($email)) {
      $links[] = array(
        'type' => 'email',
        'icon' => 'email',
        'url' => sprintf('mailto:%s', $email),
        'title' => sprintf('Send email to %s', $email),
        'content' => $email
      );
    }

    if (!empty($zalo)) {
      $links[] = array(
        'type' => 'zalo',
        'icon' => 'social-zalo',
        'url' => sprintf('https://zalo.me/%s', $zalo),
        'title' => sprintf('Send messenger via Zalo %s', $zalo),
        'content' => $zalo
      );
    }

    if (!empty($messenger)) {
      $links[] = array(
        'type' => 'messenger',
        'icon' => 'social-messenger',
        'url' => sprintf('https://m.me/%s', $messenger),
        'title' => sprintf('Chat via Messenger %s', $messeger),
        'content' => $messeger
      );
    }

    if (!empty($theme) && $theme !== 'default') {
      $before_widget = str_replace('class="', 'class="widget--dark-theme ', $before_widget);
    }

?>
    <?php echo $before_widget; ?>
      <?php if (!empty($title)) : ?>
        <?php echo $before_title; ?><?php echo $title; ?><?php echo $after_title; ?>
      <?php endif; ?>
      <?php if (!empty($about)) :
        printf('<div class="textwidget mt-1 mb-1 wysiwyg widget__intro">%s</div>', $about);
      endif; ?>
      <ul class="widget__list">
        <?php foreach ($links as $link) :
          ob_start(); ?>
          <span class="widget__icon"><?php codetot_svg($link['icon'], true); ?></span>
          <span class="widget__content"><?php echo $link['content']; ?></span>
          <?php
          $content = ob_get_clean(); ?>

          <li class="w100 widget__item widget__item--<?php echo esc_attr($link['type']); ?>">
            <?php if (!empty($link['url'])) :
              printf(
                '<a class="f w100 widget__link" href="%1$s" title="%2$s">%3$s</a>',
                $link['url'],
                $link['title'],
                $content
              );
            else :
              echo $content;
            endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php echo $after_widget; ?>
<?php
  }
}

add_action('widgets_init', 'create_codetot_widget_company_info');
function create_codetot_widget_company_info()
{
  register_widget('codetot_widget_company_info');
}

// Field
if (function_exists('acf_add_local_field_group')) :

  acf_add_local_field_group(array(
    'key' => 'group_606fcbaf7210d',
    'title' => 'Widget: Contact info',
    'fields' => array(
      array(
        'key' => 'field_60e3d02e95fd1',
        'label' => 'Theme',
        'name' => 'theme',
        'type' => 'radio',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'choices' => array(
          'default' => 'Dark Text - Light Background',
          'dark' => 'Light Text - Dark Background',
        ),
        'allow_null' => 0,
        'other_choice' => 0,
        'default_value' => 'default',
        'layout' => 'horizontal',
        'return_format' => 'value',
        'save_other_choice' => 0,
      ),
      array(
        'key' => 'field_606fcbc2b6105',
        'label' => 'Title',
        'name' => 'title',
        'type' => 'text',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'maxlength' => '',
      ),
      array(
        'key' => 'field_606fd8ce269f3',
        'label' => 'Description',
        'name' => 'about',
        'type' => 'wysiwyg',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'tabs' => 'all',
        'toolbar' => 'full',
        'media_upload' => 0,
        'delay' => 0
      ),
      array(
        'key' => 'field_606fd2f2d07e1',
        'label' => 'Address',
        'name' => 'address',
        'type' => 'text',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => 'Ex: 123 Pham Hung, Bac Tu Liem, Ha Noi',
        'prepend' => 'Address:',
        'append' => '',
        'maxlength' => '',
      ),
      array(
        'key' => 'field_606fd30cd07e2',
        'label' => 'Phone',
        'name' => 'phone',
        'type' => 'text',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => '0910000000',
        'prepend' => 'Phone:',
        'append' => '',
        'maxlength' => '',
      ),
      array(
        'key' => 'field_606fd31cd07e3',
        'label' => 'Email',
        'name' => 'email',
        'type' => 'email',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => 'sales@codetot.com',
        'prepend' => 'Email:',
        'append' => '',
      ),
      array(
        'key' => 'field_606fd346d07e4',
        'label' => 'Zalo',
        'name' => 'zalo',
        'type' => 'text',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => '0910000000',
        'prepend' => 'https://zalo.me/',
        'append' => '',
        'maxlength' => '',
      ),
      array(
        'key' => 'field_606fd354d07e5',
        'label' => 'Messeger',
        'name' => 'messeger',
        'type' => 'text',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => 'codetot',
        'prepend' => 'https://m.me/',
        'append' => '',
        'maxlength' => '',
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'widget',
          'operator' => '==',
          'value' => 'codetot_widget_company_info',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
  ));

endif;
