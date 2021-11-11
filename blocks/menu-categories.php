<?php
if ( $menu_default ) :
	$default_category = 'product_cat';
endif;
?>
<div class="menu-categories js-hero-menu">
  <h3 class="menu-categories__title js-menu-primary-toggle">
	<span class="menu-categories__icon-desktop"><?php codetot_svg( 'menu', true ); ?></span>
	<?php _e( 'Category', 'ct-bones' ); ?>
	<span class="menu-categories__icon-mobile"><?php codetot_svg( 'chevron-down', true ); ?></span>
  </h3>

  <?php if ( ! empty( $menu_1_product_category ) ) : ?>
	<ul class="menu-categories__categories js-menu-primary">
		<?php foreach ( $menu_1_product_category as $item ) : ?>
		<li class="menu-categories__item-category-primary js-accordion-item-1">
			<?php
			$menu_2_product_category = ( $menu_default ) ? ( ( ! empty( get_term_children( $item->term_id, $default_category ) ) ) ? get_term_children( $item->term_id, $default_category ) : null ) : ( ( ! empty( $item['secondary_menu_item'] ) ) ? $item['secondary_menu_item'] : null );
			$menu_1_item_link        = ( $menu_2_product_category ) ? 'javascript:void(0)' : ( ( $menu_default ) ? get_term_link( $item->term_id, $default_category ) : ( ( ! empty( $item['link'] ) ) ? $item['link'] : '#' ) );
			$menu_1_item_title       = ( $menu_default ) ? $item->name : ( ( ! empty( $item['name'] ) ) ? $item['name'] : '' );
			?>

		  <a href="<?php echo $menu_1_item_link; ?>" class="menu-categories__item-link js-accordion-item-toggle">
			<?php _e( $menu_1_item_title, 'ct-bones' ); ?>
			<?php if ( ( $menu_2_product_category !== null ) ) : ?>
			  <span class="menu-categories__item-icon"><?php codetot_svg( 'chevron-down', true ); ?></span>
			<?php endif; ?>
		  </a>
			<?php if ( ( $menu_2_product_category !== null ) ) : ?>
			<ul class="menu-categories__sub-menu-1 js-accordion-item-content-1" style="display: none;">
				<?php foreach ( $menu_2_product_category as $_item ) : ?>
				<li class="menu-categories__item-category-secondary">
					<?php
					$menu_2_item_link  = ( $menu_default ) ? get_term_link( $_item, $default_category ) : ( ( ! empty( $_item['link'] ) ) ? $_item['link'] : '' );
					$menu_2_item_title = ( $menu_default ) ? get_the_category_by_ID( $_item ) : ( ( ! empty( $_item['name'] ) ) ? $_item['name'] : '' );
					?>
				  <a href="<?php echo $menu_2_item_link; ?>"
					 class="menu-categories__item-link"><?php _e( $menu_2_item_title, 'ct-bones' ); ?></a>
					<?php $menu_3_product_category = ( $menu_default ) ? get_term_children( $_item, $default_category ) : ( ! empty( $_item['tertiary_menu_item'] ) ) ? $_item['tertiary_menu_item'] : null; ?>
					<?php if ( ( $menu_3_product_category !== null ) ) : ?>
					<ul class="menu-categories__sub-menu-2">
						<?php foreach ( $menu_3_product_category as $__item ) : ?>
						<li class="menu-categories__item-category-tertiary">
							<?php
							$menu_3_item_link  = ( $menu_default ) ? get_term_link( $__item, $default_category ) : ( ( ! empty( $__item['link'] ) ) ? $__item['link'] : '' );
							$menu_3_item_title = ( $menu_default ) ? get_the_category_by_ID( $__item ) : ( ( ! empty( $__item['name'] ) ) ? $__item['name'] : '' );
							?>
						  <a href="<?php echo $menu_3_item_link; ?>"
							 class="menu-categories__item-link"><?php _e( $menu_3_item_title, 'ct-bones' ); ?></a>
						</li>
					  <?php endforeach; ?>
					</ul>
				  <?php endif; ?>
				</li>
			  <?php endforeach; ?>
			</ul>
						  <?php endif; ?>
		</li>
	  <?php endforeach; ?>
	</ul>
  <?php endif; ?>
</div>
