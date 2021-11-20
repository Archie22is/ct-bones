<?php
$_class  = 'post-grid section';
$_class .= ! empty( $class ) ? ' ' . $class : '';
$_class .= ! empty( $columns ) ? ' has-' . $columns . '-columns' : ' has-3-columns';

$header = ! empty( $title ) ? codetot_build_content_block(
	array(
		'title' => $title,
	),
	'post-grid'
) : '';

$columns = array();
while ( $query->have_posts() ) :
	$query->the_post();
	$columns[] = array(
		'class' => 'f fdc post-grid__col',
		'content' => get_block( 'post-card' )
	);
endwhile;
wp_reset_postdata();

$content = codetot_generate_grid_columns($columns);

if ( ! empty( $query ) && $query->have_posts() ) :

	the_block(
		'default-section',
		array(
			'class'   => $_class,
			'header'  => $header,
			'content' => $content,
		)
	);

endif;
