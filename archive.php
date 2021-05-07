<?php

get_header();
?>

	<main id="primary" class="site-main">

    <?php
    the_block('breadcrumbs');
    ?>

		<?php if ( have_posts() ) :
      global $wp_query;
      ?>

      <?php the_block('page-header', array(
        'class' => 'page-header--archive',
        /* translators: %s: search query. */
        'title' => single_cat_title( '', false )
      )); ?>

			<?php
			the_block('post-grid', array(
        'class' => 'post-grid--archive',
        'query' => $wp_query
      ));

      the_block('pagination');

		else :

      the_block('page-header', array(
        'class' => 'page-header--404',
        'title' => esc_html__('No Posts Found', 'ct-theme')
      ));

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

	</main><!-- #main -->

  <?php do_action('codetot_sidebar'); ?>

<?php
get_footer();
