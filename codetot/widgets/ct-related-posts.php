<?php
add_action( 'widgets_init', 'related_posts_widget' );

function related_posts_widget() {
	register_widget( 'Codetot_Related_Post_Widget' );
}

/**
 * Related_Posts widget class
 *
 * @since 2.8.0
 */
class Codetot_Related_Post_Widget extends WP_Widget {


	function __construct() {
		parent::__construct(
			'codetot_related_posts',
			sprintf( __( '%s  Related Posts', 'ct-bones' ), '[CT]' ),
			array(
				'description' => esc_html__( 'A widget that displays related posts.', 'ct-bones' ),
			)
		);
	}

	function widget( $args, $instance ) {

		$cache = wp_cache_get( 'widget_related_posts', 'widget' );

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();
		extract( $args );

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Related Posts', 'ct-bones' ) : $instance['title'], $instance, $this->id_base );
		if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) ) {
			$number = 10;
		}

		global $post;

		$args_resent_post = array(
			'post_status'    => 'publish',
			'post_type'      => 'post',
			'posts_per_page' => $number,
		);
		if ( is_single() ) {
			$args_resent_post['category__in'] = wp_get_post_categories( $post->ID );
			$args_resent_post['post__not_in'] = array( $post->ID );
		}
		$query = new WP_Query( $args_resent_post );
		if ( $query->have_posts() ) :
			?>
			<?php echo $before_widget; ?>
			<?php
			if ( $title ) {
				echo $before_title . $title . $after_title;}
			?>
			<div class="widget__list">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					?>
					<div class="w100 widget__item">
						<span class="f widget__item-wrapper">
							<a class="f widget__image-link" href="<?php the_permalink(); ?>">
								<?php
								if ( has_post_thumbnail() ) :
									echo get_the_post_thumbnail( get_the_id(), 'thumbnail' );
								else :
									the_block( 'image-placeholder' );
								endif;
								?>
							</a>
							<span class="f fdc widget__content">
								<span class="f fw widget__item-title"><a class="d-block w100 bold-text widget__item-title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
								<span class="d-block mt-05 post-date widget__post-date"><?php echo get_the_date( 'd/m/Y', get_the_ID() ); ?></span>
							</span>
						</span>
					</div>
				<?php endwhile; ?>
			</div>
			<?php echo $after_widget; ?>
			<?php
			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();

		endif;

		$cache[ $args['widget_id'] ] = ob_get_flush();
		wp_cache_set( 'widget_related_posts', $cache, 'widget' );
	}

	function update( $new_instance, $old_instance ) {
		$instance           = $old_instance;
		$instance['title']  = strip_tags( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['widget_related_entries'] ) ) {
			delete_option( 'widget_related_entries' );
		}

		return $instance;
	}

	function flush_widget_cache() {
		 wp_cache_delete( 'widget_related_posts', 'widget' );
	}

	function form( $instance ) {
		$title  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'ct-bones' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'ct-bones' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>
		<?php
	}
}
