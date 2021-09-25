<?php

// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_Theme_Layout
{
  /**
   * Singleton instance
   *
   * @var Codetot_Theme_Layout
   */
  private static $instance;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Theme_Layout
   */
  final public static function instance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Class constructor
   */
  private function __construct()
  {
    add_action('codetot_sidebar', 'codetot_get_sidebar', 10);
    add_action('codetot_after_content_post', 'codetot_layout_single_post_social_share_html', 5);
    add_action('codetot_footer_row_middle', 'codetot_render_footer_copyright_block', 10);

    $is_not_flexible_page = get_page_template_slug( get_the_ID()) !== 'flexible';
		$is_fullwidth_page = get_page_template_slug( get_the_ID() ) === 'page-fullwidth.php';

    $is_not_woocommerce_pages = class_exists('WooCommerce') ? (!is_account_page() && !is_cart() && !is_checkout()) : !class_exists('WooCommerce');

    if (
      is_page() &&
      $is_not_flexible_page &&
      $is_not_woocommerce_pages &&
			!$is_fullwidth_page
    ) {
      $this->generate_page_layout();
    }

    if (is_singular() && !is_singular('product') && !is_singular('page')) {
      $this->generate_post_layout();
    }

    $this->generate_default_index_layout();
  }

  public function load_page_header() {
    $sidebar_layout = codetot_get_theme_mod('page_layout') ?? 'no-sidebar';
    $header_class = $sidebar_layout !== 'no-sidebar' ? 'page-header--no-container' : ' mt-1';
    $header_class .= ' mb-1';

    the_block('page-header', array(
      'class' => $header_class,
      'title' => get_the_title()
    ));
  }

  public function generate_page_layout() {
    $sidebar_layout = codetot_get_theme_mod('page_layout') ?? 'no-sidebar';

    if ( !is_front_page() ) {
      add_action('codetot_after_header', 'codetot_breadcrumbs_html', 9);
    }
    add_action('codetot_after_header', function() use($sidebar_layout) {
      do_action('codetot_before_page_block');

      if ($sidebar_layout !== 'no-sidebar') {
        echo codetot_layout_page_block_open('page-block--page ' . $sidebar_layout, false);
      }
    }, 10);

    add_action('codetot_page', array($this, 'load_page_header'), 20);
    add_action('codetot_page', function() use($sidebar_layout) {
      ob_start();

      echo '<div class="wysiwyg">';
      the_content();
      echo '</div>';
      wp_link_pages(
        array(
          'before'      => '<div class="page-links">' . __( 'Pages:', 'ct-bones' ),
          'after'       => '</div>',
          'link_before' => '<span>',
          'link_after'  => '</span>',
        )
      );

      $content = ob_get_clean();

      if ($sidebar_layout !== 'no-sidebar') {
        echo $content;
      } else {
        the_block('default-section', array(
          'class' => 'section page-content page-content--no-sidebar',
          'content' => $content
        ));
      }

    }, 30);
    add_action('codetot_page', function() use($sidebar_layout) {
      $content = $this->generate_comments();

      if ($sidebar_layout !== 'no-sidebar') {
        echo '<div class="page-comments">';
        comments_template();
        echo '</div>';
      } else {
        the_block('default-section', array(
          'class' => 'section page-comments',
          'content' => $content
        ));
      }
    }, 40);

    add_action('codetot_before_sidebar', 'codetot_layout_page_block_between_html', 10);
    add_action('codetot_footer', 'codetot_layout_page_block_close_html', 10);
  }

  public function generate_post_layout() {
    $sidebar_layout    = codetot_get_theme_mod('post_layout') ?? 'right-sidebar';
    $enable_hero_image = codetot_get_theme_mod('extra_single_post_layout', 'pro') ?? 'none';

    if ($enable_hero_image === 'hero_image') {
      add_action('codetot_after_header', 'codetot_layout_single_post_hero_image_html', 2);
      add_filter('codetot_hide_single_post_header', '__return_true');
    }

    add_action('codetot_after_header', 'codetot_breadcrumbs_html', 9);
    add_action('codetot_after_header', function() use ($sidebar_layout) {
        echo codetot_layout_page_block_open('page-block--page ' . $sidebar_layout, false);
    }, 10);
    add_action('codetot_before_sidebar', 'codetot_layout_page_block_between_html', 10);
    add_action('codetot_footer', 'codetot_layout_page_block_close_html', 10);
  }

  public function generate_comments() {
    ob_start();
      if ( comments_open() || get_comments_number() ) :
        comments_template();
      endif;

   return ob_get_clean();
  }

  public function generate_default_index_layout() {
    add_action('codetot_before_index_main', 'codetot_breadcrumbs_html', 1);
    add_action('codetot_before_index_main', 'codetot_layout_page_block_open_html', 10);
    add_action('codetot_before_index_sidebar', 'codetot_layout_page_block_between_html', 1);
    add_action('codetot_index_main_layout', 'codetot_layout_archive_page_header_html', 1);
    add_action('codetot_index_main_layout', 'codetot_layout_post_list_html', 5);
    add_action('codetot_index_main_layout', 'codetot_layout_post_list_pagination', 10);

    add_action('codetot_after_index_main', function() {
      echo $this->page_block_close();
    }, 10);
  }

  public function page_block_between()
  {
    ob_start();
    echo '</div>'; // Close .page-block__col--main
    echo '<div class="grid__col page-block__col--sidebar">';
    return ob_get_clean();
  }

  public function page_block_close() {
    ob_start();
    echo '</div>'; // Close .page-block__col--sidebar
    echo '</div>'; // Close .page-block__grid
    echo '</div>'; // Close .page-block__container
    echo '</div>'; // Close .page-block
    return ob_get_clean();
  }
}

function codetot_layout_page_block_open($available_class = '')
  {
    ob_start();
    printf('<div class="page-block %s">', $available_class);
    echo '<div class="container page-block__container">';
    echo '<div class="grid page-block__grid">';
    echo '<div class="grid__col page-block__col page-block__col--main">';

    return ob_get_clean();
  }

function codetot_breadcrumbs_html() {
  the_block('breadcrumbs');
}

function codetot_layout_page_block_open_html()
{
  if (is_category() || is_archive()) {
    $sidebar_layout = codetot_get_theme_mod('category_layout') ?? 'no-sidebar';
  } else {
    $sidebar_layout = codetot_get_theme_mod('post_layout') ?? 'no-sidebar';
  }

  echo codetot_layout_page_block_open('page-block--archive ' . esc_attr($sidebar_layout), false);
}

function codetot_layout_page_block_between_html()
{
  ob_start();
  echo '</div>'; // Close .page-block__col--main
  echo '<div class="grid__col page-block__col--sidebar">';
  echo ob_get_clean();
}

function codetot_layout_page_block_close_html() {
  ob_start();
  echo '</div>'; // Close .page-block__col--sidebar
  echo '</div>'; // Close .page-block__grid
  echo '</div>'; // Close .page-block__container
  echo '</div>'; // Close .page-block
  echo ob_get_clean();
}

function codetot_layout_archive_page_header_html() { ?>
  <?php if (!is_front_page()) :

  $description = get_the_archive_description();
  ?>
  <header class="mt-05 mb-1 page-header">
    <?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
    <?php if ( $description ) : ?>
      <div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
    <?php endif; ?>
  </header><!-- .page-header -->
  <?php endif; ?>
<?php }

function codetot_layout_single_post_social_share_html() {
  $hide_social_share = codetot_get_theme_mod('hide_social_share') ?? false;

  if (!$hide_social_share) :
    global $post;

    the_block('social-links', array(
      'class' => 'social-links--share',
      'label' => __('Share', 'ct-theme'),
      'items' => codetot_get_share_post_links($post)
    ));
  endif;
}

function codetot_layout_single_post_hero_image_html() {
  global $post;

  $categories = get_the_category();
  $category_html = '<ul class="hero-image__post-meta">';

  if (!empty($categories)) {
    foreach ($categories as $category) :
      $category_html .= sprintf('<li class="hero-image__post-meta__item"><a class="hero-image__post-meta__link" href="%1$s">%2$s</a></li>',
      get_term_link($category, 'category'),
      $category->name
    );
    endforeach;
  }

  $category_html .= '</ul>';

  the_block('hero-image', array(
    'label' => $category_html,
    'title' => $post->post_title,
    'class' => 'hero-image--single-post',
    'image' => get_post_thumbnail_id(),
    'spacing' => 'large',
    'background_contract' => 'dark',
    'content_alignment' => 'center',
    'overlay' => '0.4'
  ));
}

function codetot_layout_post_list_html() {
  global $wp_query;

  $archive_layout  = codetot_get_theme_mod('archive_post_layout') ?? 'row';
  $columns         = codetot_get_theme_mod('archive_post_column') ?? 3;

  if ($archive_layout === 'list') {
    the_block('post-list', array(
      'class' => 'section default-section--no-container',
      'query' => $wp_query
    ));
  } else {
    the_block('post-grid', array(
      'class' => 'section default-section--no-container',
      'columns' => $columns,
      'query' => $wp_query
    ));
  }
}

function codetot_layout_post_list_pagination() {
  the_block('pagination');
}

function codetot_render_footer_copyright_block() {
  $hide_footer_copyright = codetot_get_theme_mod('hide_footer_copyright') ?? false;
  $hide_social_links = codetot_get_theme_mod('footer_hide_social_links') ?? false;
  $footer_copyright = codetot_get_footer_copyright();
  $social_links_html = apply_filters('codetot_footer_social_links_html', get_block('social-links', array(
    'class' => 'social-links--dark-contract social-links--footer-bottom'
  )));

  if (!$hide_footer_copyright && !empty($footer_copyright) ): ?>
    <div class="footer__bottom">
      <div class="container footer__container">
        <div class="grid footer__bottom-grid">
          <div class="grid__col footer__bottom-col footer__bottom-col--left">
            <div class="footer__copyright-text"><?php echo $footer_copyright; ?></div>
          </div>
          <?php if (!$hide_social_links && !empty(strip_tags($social_links_html))) : ?>
            <div class="grid__col footer__bottom-col footer__bottom-col--right">
              <?php echo $social_links_html; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endif;
}

Codetot_Theme_Layout::instance();
