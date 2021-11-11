<?php
if ( ! empty( $query ) ) :

	$_class  = 'post-list';
	$_class .= ! empty( $class ) ? ' ' . $class : '';

	// Generate header
	$header = codetot_build_content_block(
		array(
			'title' => ! empty( $title ) ? $title : '',
		),
		'post-list'
	);

	// Generate list
	if ( isset( $offset ) && $offset ) :

		ob_start();
		$count_index = 1;
		while ( $query->have_posts() ) :
			$query->the_post();
			if ( $count_index > $offset ) :
				echo '<div class="post-list__row">';
				the_block( 'post-row' );
				echo '</div>';
		  endif;
			$count_index++;
	  endwhile;
		wp_reset_postdata();
		$content = ob_get_clean();

  else :

	  ob_start();
	  while ( $query->have_posts() ) :
		  $query->the_post();
		  echo '<div class="post-list__row">';
		  the_block( 'post-row' );
		  echo '</div>';
	endwhile;
	  wp_reset_postdata();
	  $content = ob_get_clean();

  endif;

  // Generate markup
the_block(
	'default-section',
	array(
		'class'   => $_class,
		'header'  => $header,
		'content' => $content,
)
);

endif;
