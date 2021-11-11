<?php if ( ! empty( $items ) ) : ?>
  <p class="post-meta-list">
	<?php if ( ! empty( $icon ) ) : ?>
	  <span class="post-meta-list__icon"><?php codetot_svg( $icon, true ); ?></span>
	<?php endif; ?>
	<?php if ( ! empty( $title ) ) : ?>
	  <span class="label-text bold-text post-meta-list__title"><?php echo $title; ?></span>
	<?php endif; ?>
	<?php foreach ( $items as $item ) : ?>
	  <a class="post-meta-list__link" href="<?php echo esc_url( $item['url'] ); ?>"><?php echo $item['name']; ?></a>
	<?php endforeach; ?>
  </p>
<?php endif; ?>
