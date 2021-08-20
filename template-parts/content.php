<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CT_Bones
 */

$hide_featured_image = get_global_option('codetot_settings_hide_featured_image') ?? false;
$hide_post_meta = get_global_option('codetot_settings_hide_post_meta') ?? false;
$_hide_post_meta = 'post' === get_post_type() && !$hide_post_meta;
$hide_header = apply_filters('codetot_hide_single_post_header', false);

ob_start();
ct_bones_posted_on();
ct_bones_posted_by();
ct_bones_entry_categories();
$post_meta = ob_get_clean();

if ($_hide_post_meta) {
  $post_meta = '';
}

ob_start();
if (is_singular()) :
  the_title('<h1 class="entry-title">', '</h1>');
else :
  the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
endif;
$header = ob_get_clean();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php if (!$hide_header) : ?>
    <header class="entry-header">
      <?php
      echo $header;
      if (apply_filters('codetot_single_post_meta', $post_meta) !== '') : ?>
        <div class="entry-meta">
          <?php echo $post_meta; ?>
        </div>
      <?php endif;
      ?>
    </header>
  <?php endif; ?>
  <?php if (!$hide_featured_image) : ?>
    <div class="entry-thumbnail">
      <?php ct_bones_post_thumbnail(); ?>
    </div>
  <?php endif; ?>

  <div class="wysiwyg entry-content">
    <?php
    the_content(
      sprintf(
        wp_kses(
          /* translators: %s: Name of current post. Only visible to screen readers */
          __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'ct-bones'),
          array(
            'span' => array(
              'class' => array(),
            ),
          )
        ),
        wp_kses_post(get_the_title())
      )
    );

    wp_link_pages(
      array(
        'before' => '<div class="page-links">' . esc_html__('Pages:', 'ct-bones'),
        'after'  => '</div>',
      )
    );
    ?>
  </div><!-- .entry-content -->

  <footer class="entry-footer">
    <?php
    ct_bones_entry_tags();
    ct_bones_entry_comment_links();
    ct_bones_entry_footer();
    ?>
  </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
