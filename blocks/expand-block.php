<?php if ( !empty($content) ) : ?>
	<div class="expand-block<?php if ( !empty($class) ) : echo ' ' . $class; endif; ?>" data-block="expand-block" data-max-height="<?php echo absint($max_height); ?>">
		<div class="expand-block__content js-content" style="position: relative; overflow: hidden; height: <?php echo esc_attr( absint( $max_height) ); ?>px;"><?php echo $content; ?></div>
		<div class="expand-block__footer">
			<?php the_block('button', [
				'button' => esc_html__('View more', 'ct-bones'),
				'attr' => sprintf(' data-expanded-text="%s"', __('Collapse', 'ct-bones')),
				'type' => 'primary',
				'class' => 'expand-block__button js-open-trigger'
			]); ?>
		</div>
	</div>
<?php endif; ?>
