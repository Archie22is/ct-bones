<?php
/**
 * Block Cover
 *
 * @package ct_bones
 * @author codetot
 * @since 6.2.1
 */

$data = wp_parse_args($args, array(
	'lazy' => true,
	'class' => '',
	'image' => null,
	'content' => null
));

$attr = sprintf(' style="%1$s"', 'min-height: 400px;');

if ( !empty( $data['content'] ) ) :
?>
	<div class="block-cover wp-block-cover has-background-dim-60 has-background-dim<?php if ( !empty($data['class']) ) : echo ' ' . esc_attr( $data['class'] ); endif; ?>"<?php if ( !empty($attr) ) : echo ' ' . $attr; endif; ?>>
		<?php if ( !empty($data['image']) ) :
			echo wp_get_attachment_image( absint( $data['image'] ), 'large', false, array(
				'loading' => $data['lazy'] ?? false,
				'class' => 'wp-block-cover__image-background'
			) );
		endif; ?>
		<div class="wp-block-cover__inner-container">
			<div class="wp-block-group block-cover__content">
				<div class="wp-block-group__inner-container">
					<?php echo $data['content']; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
