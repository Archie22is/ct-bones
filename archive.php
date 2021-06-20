<?php

get_header();
$card_style = get_global_option('codetot_post_card_style') ?? 'style-1';
$number_columns = get_global_option('codetot_category_column_number');
$title = get_the_archive_title();
?>
	<main id="primary" class="site-main">
  <?php do_action('codetot_before_category_main'); ?>
    <?php

    printf('<h1 class="page-title">%s</h1>', $title);

    if ( have_posts() ) :
      global $wp_query; ?>

			<?php
      $columns = [];
      while( $wp_query->have_posts() ) : $wp_query->the_post();
        $columns[] = get_block('post-card',array(
          'card_style' => !empty($card_style) ? $card_style : 'style-1'
        ));
      endwhile; wp_reset_postdata();

      $content = codetot_build_grid_columns($columns, 'post-grid', array(
        'column_class' => 'f fdc default-section__col'
      ));

      printf('<div class="mt-1 site-main__main-category default-section %s">', 'has-'. esc_attr($number_columns) . '-columns');
      echo $content;
      the_block('pagination');
      echo '</div>';
		else :

      ob_start();
      echo '<p>';
      esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'ct-bones');
      echo '</p>';
      echo get_search_form(array(
        'id' => '404'
      ));

      $content = ob_get_clean();

      the_block('message-block', array(
        'class' => 'message-block--404',
        'content' => $content
      ));

		endif;
		?>

    <?php do_action('codetot_sidebar'); ?>
    <?php do_action('codetot_after_category_main'); ?>
	</main><!-- #main -->
<?php
get_footer();
