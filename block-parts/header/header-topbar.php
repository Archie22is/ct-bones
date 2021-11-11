<?php
$enable_topbar        = codetot_get_theme_mod( 'enable_topbar_widget' ) ?? false;
$topbar_widget_column = codetot_get_theme_mod( 'topbar_widget_column' ) ?? '1-col';
$topbar_widget_column = str_replace( '-col', '', $topbar_widget_column );

$topbar_widget_background = codetot_get_theme_mod( 'topbar_background_color' ) ?? 'white';
$topbar_widget_contract   = codetot_get_theme_mod( 'topbar_text_contract' ) ?? 'light';

$class  = 'header-topbar';
$class .= ' has-' . $topbar_widget_column . '-columns';
$class .= ' bg-' . esc_html( $topbar_widget_background );
$class .= ' is-' . esc_html( $topbar_widget_contract ) . '-contract';

if ( $enable_topbar ) : ?>
  <div class="<?php echo $class; ?>">
	<div class="container header-topbar__container">
	  <div class="grid header-topbar__grid">
		<?php 
		for ( $i = 1; $i <= $topbar_widget_column; $i++ ) :
			$column_class  = 'grid__col header-topbar__col';
			$column_class .= $i === 1 ? ' header-topbar__col--left' : ' header-topbar__col--right';
			?>
		  <div class="<?php echo $column_class; ?>">
			<div class="header-topbar__content"><?php dynamic_sidebar( 'topbar-column-' . $i ); ?></div>
		  </div>
		<?php endfor; ?>
	  </div>
	</div>
  </div>
<?php endif; ?>
