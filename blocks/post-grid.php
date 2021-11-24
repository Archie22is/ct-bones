<?php
$_class  = 'post-grid section';
$_class .= ! empty( $class ) ? ' ' . $class : '';

$_columns = array();
if ( !empty($columns) && is_int($columns) ) {
	$_columns['desktop'] = $columns;
	error_log('Deprecated passing $columns as number to post-grid section.');
}

if ( !empty($columns) && is_array($columns)) {
	$_columns = $columns;
}

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

$content = codetot_generate_grid_columns($columns, array(
	'columns' => $_columns
));

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
