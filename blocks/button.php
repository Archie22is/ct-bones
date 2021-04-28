<?php
$button_sizes = array('small', 'large');
$button_types = array('primary', 'secondary', 'white', 'dark', 'outline', 'outline-white', 'link', 'link-white');

$_class = 'button';
$_class .= !empty($size) && in_array($size, $button_sizes) ? ' button--' . $size : '';
$_class .= !empty($type) && in_array($type, $button_types) ? ' button--' . $type : '';
$_class .= !empty($class) ? ' ' . $class : '';

$_attr = !empty($attr) ? $attr : '';
$_attr .= !empty($target) ? ' target="' . $target . '"' : '';

$_url = !empty($url) ? $url : '';
$content = !empty($button) ? '<span class="button__text">' . esc_html($button) . '</span>' : '';

if (!empty($icon)) {
  $content .= '<span class="button__icon">' . codetot_svg(esc_attr($icon), false) . '</span>';
  $_class .= ' button--icon';
}

if (!empty($type)) {
  switch($type):

    case 'menu':
      $_class = 'button--menu ' . $_class;
      $content = '<span class="button__menu"></span>';
      break;

    case 'menu-white':
      $_class = 'button--menu button--menu-white ' . $_class;
      $content = '<span class="button__menu"></span>';
      break;

    case 'close':
      $_class = 'button--menu button--menu-close ' . $_class;
      $content = '<span class="button__menu"></span>';
      break;

    case 'close-white':
      $_class = 'button--menu button--menu-white button--menu-close ' . $_class;
      $content = '<span class="button__menu"></span>';
      break;

  endswitch;
}

if (!empty($url)) :
  printf('<a class="%1$s" href="%2$s"%3$s>%4$s</a>', esc_attr($_class), esc_url($_url), $_attr, $content);
else :
  printf('<button class="%1$s"%2$s>%3$s</button>', esc_attr($_class), $_attr, $content);
endif;
?>
