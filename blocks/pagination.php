<?php
if ( empty( $query ) ) {
	global $wp_query;
	$query = $wp_query;
}
  $big        = 999999999;
  $translated = __( 'Page', 'ct-bones' );
if ( $query->max_num_pages > 1 ) :
	?>
	<div class="pagination
	<?php 
	if ( ! empty( $class ) ) :
		echo ' ' . $class;
endif; 
	?>
	" >
	  <div class="container pagination__container">
		<div class="pagination__list">
		<?php 
		echo paginate_links(
			array(
				'base'               => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'             => '?paged=%#%',
				'current'            => max( 1, get_query_var( 'paged' ) ),
				'total'              => $query->max_num_pages,
				'before_page_number' => '<span class="screen-reader-text">' . $translated . '</span>',
			) 
		); 
		?>
		</div>
	  </div>
	</div>
<?php endif; ?>
