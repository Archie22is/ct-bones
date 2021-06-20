<?php
add_action( 'widgets_init', 'recent_posts_widget' );

function recent_posts_widget() {

	register_widget( 'Codetot_Recent_Post_Widget' );
}

/**
 * Recent_Posts widget class
 *
 * @since 2.8.0
 */
class Codetot_Recent_Post_Widget extends WP_Widget {

	function __construct() {
    parent::__construct(
      'codetot_recent_posts',
      sprintf(__('%s  Recent Posts', 'ct-bones'), '[CT]'),
      array(
        'description' => esc_html__('A widget that displays recent posts.', 'ct-bones')
      )
    );
	}

	function widget($args, $instance) {

		$cache = wp_cache_get('widget_recent_posts', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( !isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();
		extract($args);

		if ( empty( $instance['image'] ) ) $instance['image'] = false;
		$is_image = $instance['image'] ? 'true' : 'false';

        if ( empty( $instance['date-stamp'] ) ) $instance['date-stamp'] = false;
		$is_date_stamp = $instance['date-stamp'] ? 'true' : 'false';

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Posts', 'codetot') : $instance['title'], $instance, $this->id_base);
		if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) )
 			$number = 10;
    $args_resent_post = array(
        'post_status' => 'publish',
        'post_type' => 'post',
        'posts_per_page' => $number,
      );
		$query = new WP_Query($args_resent_post);
		if ($query->have_posts()) :
    ?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<?php echo '<ul class="codetot-recent-posts">'; ?>
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
		<li class="codetot-recent-posts__item">
      <div class="f fw codetot-recent-posts__post">
        <div class="codetot-recent-posts__wrapper">
          <a href="<?php the_permalink() ?>">
            <?php
              if (has_post_thumbnail()) :
                the_block('image', array(
                  'image' => get_post_thumbnail_id(),
                  'class' => 'image--cover codetot-recent-posts__image'
                ));
              else :
                the_block('image-placeholder', array(
                  'class' => 'codetot-recent-posts__image'
                ));
              endif;
            ?>
            </a>
        </div>
        <div class="codetot-recent-posts--content">
          <p><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></p>
        </div>
      </div>
		</li>
		<?php endwhile; ?>
		<?php echo '</ul>'; ?>
		<?php echo $after_widget; ?>
  <?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_recent_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries']) )
			delete_option('widget_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_posts', 'widget');
	}

	function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'ct-bones' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'ct-bones' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
	}
}
?>
