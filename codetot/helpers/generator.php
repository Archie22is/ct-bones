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
    if (isset($args['enable_container'])) : printf('<div class="%s %s__container">', codetot_site_container(), $prefix_class); endif;

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