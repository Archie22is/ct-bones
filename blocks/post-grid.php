<?php
$_class = 'post-grid section';
$_class .= !empty($class) ? ' ' . $class : '';
$_class .= !empty($columns) ? ' has-' . $columns .'-columns': ' has-3-columns';
$container = codetot_site_container();

$columns = [];
while( $query->have_posts() ) : $query->the_post();
  $columns[] = get_block('post-card',array(
    'card_style' => !empty($card_style) ? $card_style : 'style-1'
  ));
endwhile; wp_reset_postdata();

$content = codetot_build_grid_columns($columns, 'post-grid', array(
  'column_class' => 'f fdc default-section__col'
));

if (!empty($query) && $query->have_posts()) :

  the_block('default-section', array(
    'class' => $_class,
    'content' => $content
  ));

endif;
