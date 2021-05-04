<?php

class codetot_widget_company_info extends WP_Widget {
  function __construct() {
    parent::__construct(
      'codetot_widget_company_info',
      '[CT] Company Info',
      array( 'description'  =>  'Your site’s most icon box' )
    );
  }
  function form( $instance ) {
    $default = array(
    );
    $instance = wp_parse_args( (array) $instance, $default );
  }
  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    return $instance;
  }
  function widget( $args, $instance ) {
    extract($args);
    $title = get_field('title','widget_' . $args["widget_id"]);
    $address = get_field('address','widget_' . $args["widget_id"]);
    $phone = get_field('phone','widget_' . $args["widget_id"]);
    $email = get_field('email','widget_' . $args["widget_id"]);
    $zalo = get_field('zalo','widget_' . $args["widget_id"]);
    $messeger = get_field('messeger','widget_' . $args["widget_id"]);
    $website = get_field('website','widget_' . $args["widget_id"]);
    $about = get_field('about','widget_' . $args["widget_id"]);

    ?>
      <div id="contact-info" class="widget widget-contact-info">
        <?php if(!empty($title)) : ?>
          <p class="widget__title"><?php echo $title; ?></p>
        <?php endif; ?>
          <div class="textwidget">
          <?php if(!empty($about)) : ?>
              <p><?php echo $about; ?></p>
          <?php endif; ?>
          <ul class="footer-contact">
            <?php if(!empty($phone)) : ?>
              <li class="footer-contact__item">
                <a href="tel:<?php echo $phone; ?>">
                <span class="footer-contact__icon"><?php codetot_svg('hotline', true);?></span>
                  <span class="footer-contact__content"><?php echo $phone; ?></span>
                </a>
              </li>
            <?php endif; ?>
            <?php if(!empty($address)) : ?>
              <li class="footer-contact__item">
                <span class="footer-contact__icon"><?php codetot_svg('address', true);?></span>
                <span class="footer-contact__content"><?php echo $address; ?></span>
              </li>
            <?php endif; ?>
            <?php if(!empty($email)) : ?>
              <li class="footer-contact__item">
                <a href="mailto:<?php echo $email; ?>">
                <span class="footer-contact__icon"><?php codetot_svg('email', true);?></span>
                  <span class="footer-contact__content"><?php echo $email; ?></span>
                </a>
              </li>
            <?php endif; ?>
            <?php if(!empty($zalo)) : ?>
              <li class="footer-contact__item">
                <a href="tel:<?php echo $zalo; ?>">
                <span class="footer-contact__icon"><?php codetot_svg('social-zalo', true);?></span>
                  <span class="footer-contact__content"><?php echo $zalo; ?></span>
                </a>
              </li>
            <?php endif; ?>
            <?php if(!empty($messeger)) : ?>
              <li class="footer-contact__item">
                <a href="<?php echo $messeger; ?>">
                <span class="footer-contact__icon"><?php codetot_svg('social-messenger', true);?></span>
                  <span class="footer-contact__content"><?php echo $messeger; ?></span>
                </a>
              </li>
            <?php endif; ?>
            <?php if(!empty($phone)) : ?>
              <li class="footer-contact__item">
                <a href="<?php echo $website; ?>">
                <span class="footer-contact__icon"><?php codetot_svg('global', true);?></span>
                  <span class="footer-contact__content"><?php echo $website; ?></span>
                </a>
              </li>
            <?php endif; ?>
          </ul>
          </div>
      </div>
    <?php
  }
}

add_action( 'widgets_init', 'create_codetot_widget_company_info' );
function create_codetot_widget_company_info() {
  register_widget('codetot_widget_company_info');
}

// Field
if( function_exists('acf_add_local_field_group') ):

  acf_add_local_field_group(array(
    'key' => 'group_606fcbaf7210d',
    'title' => 'Contac info',
    'fields' => array(
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
        'label' => 'About',
        'name' => 'about',
        'type' => 'textarea',
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
        'maxlength' => '',
        'rows' => '',
        'new_lines' => '',
      ),
      array(
        'key' => 'field_606fd2f2d07e1',
        'label' => 'Địa chỉ',
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
        'prepend' => '',
        'append' => '',
        'maxlength' => '',
      ),
      array(
        'key' => 'field_606fd30cd07e2',
        'label' => 'Điện thoại',
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
        'placeholder' => 'Ex: 01234456789',
        'prepend' => '',
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
        'placeholder' => 'Ex: info@company.com',
        'prepend' => '',
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
        'placeholder' => 'https://zalo.me/0123456789',
        'prepend' => '',
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
        'placeholder' => 'Ex: https://m.me/codetot',
        'prepend' => '',
        'append' => '',
        'maxlength' => '',
      ),
      array(
        'key' => 'field_606fd451af920',
        'label' => 'Website',
        'name' => 'website',
        'type' => 'url',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => 'https://codetot.com',
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

