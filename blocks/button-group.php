<?php
$_class  = 'button-group';
$_class .= ! empty( $buttons ) && count( $buttons ) > 1 ? ' button-group--multiple' : ' button-group--single';
$_class .= ! empty( $class ) ? ' ' . esc_attr( $class ) : '';

if ( ! empty( $buttons ) ) : ?>
  <div class="<?php echo $_class; ?>">
	<?php foreach ( $buttons as $button ) : ?>
	  <div class="button-group__item">
		<?php 
		the_block(
			'button',
			array(
				'button'    => $button['button_text'],
				'type'      => ! empty( $button['button_style'] ) ? $button['button_style'] : '',
				'size'      => ! empty( $button['button_size'] ) ? $button['button_size'] : '',
				'url'       => ! empty( $button['button_url'] ) ? $button['button_url'] : '',
				'attr'      => ! empty( $button['button_attr'] ) ? $button['button_attr'] : '',
				'target'    => ! empty( $button['button_target'] ) ? $button['button_target'] : '',
				'rel'       => ! empty( $button['rel'] ) ? $button['rel'] : '',
				'icon'      => ! empty( $icon ) ? $icon : '',
				'icon_html' => ! empty( $button['icon_html'] ) ? $button['icon_html'] : '',
			)
		); 
		?>
	  </div>
	<?php endforeach; ?>
  </div>
<?php endif; ?>
