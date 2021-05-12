<?php

get_header();
?>

	<main id="primary" class="site-main">

    <?php
    the_block('breadcrumbs');

    the_block('page-header', array(
      'class' => 'page-header--archive',
      /* translators: %s: search query. */
      'title' => get_the_archive_title()
    ));

    if ( have_posts() ) :
      global $wp_query; ?>

			<?php
			the_block('post-grid', array(
        'class' => 'post-grid--archive',
        'display_meta' => true,
        'query' => $wp_query,
        'card_style' => get_global_option('codetot_post_card_style') ?? 'style-1',
        'columns' => get_global_option('codetot_category_column_number') ?? '3',
      ));

      the_block('pagination');

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

	</main><!-- #main -->

  <?php do_action('codetot_sidebar'); ?>

<?php
get_footer();
