<?php

if (!function_exists('codetot_generate_block_background_class')) {
  /**
   * @param $background_type
   * @return string
   */
  function codetot_generate_block_background_class($background_type) {
    $_class = !empty($background_type) ? ' bg-' . esc_attr($background_type) : '';
    if (!empty($background_type) && codetot_is_dark_background($background_type)) {
      $_class .= ' is-dark-contract';
    }

    $_class .= !empty($background_type) && $background_type !== 'white' ? ' section-bg' : ' section';

    return $_class;
  }
}

if (!function_exists('codetot_build_content_block')) {
  /**
   * @param $args
   * @param $prefix_class
   * @return false|string
   */
  function codetot_build_content_block($args, $prefix_class) {
    $output_elements = [];
    $title_tag = (!empty($args['title_tag']) ? $args['title_tag'] : 'h2');
    $block_tag = (!empty($args['block_tag']) ? $args['block_tag'] : 'div');
    $_class = (!empty($args['default_class'])) ? $args['default_class'] : $prefix_class . '__header';

    if (!empty($args['label'])) {
      $output_elements['label'] = sprintf('<p class="%1$s__label">%2$s</p>', $prefix_class, $args['label']);
    }

    if (!empty($args['title'])) {
      $output_elements['label'] = sprintf('<%1$s class="%2$s__title">%3$s</%4$s>',
        $title_tag,
        $prefix_class,
        $args['title'],
        $title_tag
      );
    }

    if (!empty($args['description'])) {
      $output_elements['description'] = sprintf('<div class="wysiwyg %1$s__description">%2$s</div>', $prefix_class, $args['description']);
    }

    $_class .= !empty($args['alignment']) ? ' ' . $prefix_class . '--' . $args['alignment'] . ' section-header--' . $args['alignment'] : '';
    $_class .= !empty($args['class']) ? ' ' . $args['class'] : '';

    ob_start();
    printf('<%s class="%s">', $block_tag, $_class);
    if (isset($args['enable_container'])) : printf('<div class="%s %s__container">', 'container', $prefix_class); endif;

    if (!empty($args['before_content']) ) :
      echo $args['before_content'];
    endif;
    echo implode('', $output_elements);

    if (!empty($args['after_content']) ) :
      echo $args['after_content'];
    endif;
    if (isset($args['enable_container'])) :
      printf('</div>');
    endif;
    printf('</%s>', $block_tag);
    return ob_get_clean();
  }
}

if (!function_exists('codetot_build_grid_columns')) {
  /**
   * Generate HTML markup for grid columns
   *
   * @param array $columns
   * @param string $prefix_class
   * @param array $args
   * @return string
   */
  function codetot_build_grid_columns($columns, $prefix_class, $args = []) {
    if (!is_array($columns)) {
      return '';
    }

    if (!empty($args) && !empty($args['grid_class'])) {
      $grid_class = $args['grid_class'];
    }

    if (!empty($args) && !empty($args['column_class'])) {
      $column_class = $args['column_class'];
    }

    if (!empty($args) && !empty($args['column_attributes'])) {
      $column_attributes = $args['column_attributes'];
    }

    ob_start(); ?>
    <div class="grid <?php echo $prefix_class; ?>__grid<?php if (!empty($grid_class)) : echo ' ' . $grid_class; endif; ?>">
      <?php foreach ($columns as $column) : ?>
        <div class="grid__col <?php echo $prefix_class; ?>__col<?php if (!empty($column_class)) : echo ' ' . $column_class; endif; ?>"<?php if (!empty($column_attributes) && is_array($column_attributes)) : echo ' ' . $column_attributes; endif; ?>>
          <?php echo $column; ?>
        </div>
      <?php endforeach; ?>
    </div>
    <?php

    return ob_get_clean();
  }
}

/**
 * @param WP_Post $post
 * @param array $types
 * @return array
 */
function codetot_get_share_post_links($post) {
  $types = ['linkedin', 'facebook', 'twitter', 'pinterest'];
  $items = array();

  if (!empty($types)) {
    foreach ($types as $type) {
      $items[] = array(
        'type' => $type,
        'url' => codetot_get_share_post_link($post, $type)
      );
    }
  }

  return $items;
}

/**
 * @param WP_Post $post
 * @param string $type
 * @return string
 */
function codetot_get_share_post_link($post, $type) {
  $post_link = get_permalink($post->ID);

  switch ($type) {
    case 'twitter';
      return sprintf('https://twitter.com/share?url=%s', get_permalink($post->ID));
      break;

    case 'pinterest':
      return sprintf('https://pinterest.com/pin/create/button/?url=%1$s&amp;media=%2$s&amp;description=%3$s',
        $post_link,
        (has_post_thumbnail() ? wp_get_attachment_image_url(get_post_thumbnail_id($post->ID)) : ''),
        get_the_title($post->ID)
      );
      break;

    case 'facebook':
      return sprintf('https://www.facebook.com/sharer.php?u=%1$s',
        $post_link
      );
      break;

    case 'linkedin':
      return sprintf('https://www.linkedin.com/shareArticle?mini=true&url=%1$s&title=%2$s',
        $post_link,
        get_the_title($post->ID)
      );
      break;

    default:
      return apply_filters('codetot_share_post_link_' . esc_attr($type), '');

  }
}
