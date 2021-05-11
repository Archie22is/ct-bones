<?php
// Prevent direct access.
if (!defined('ABSPATH')) exit;

class Codetot_Related_Posts
{
  /**
   * Singleton instance
   *
   * @var Codetot_Related_Posts
   */
  private static $instance;

  /**
   * @var bool
   */
  protected $enable;

  /**
   * @var string
   */
  protected $type;

  /**
   * Get singleton instance.
   *
   * @return Codetot_Related_Posts
   */
  public final static function instance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Class constructor
   */
  public function __construct()
  {
    $this->enable = get_global_option('codetot_enable_post_related_posts') ?? true;
    $this->query_type = get_global_option('codetot_related_posts_type') ?? 'category';

    if ($this->enable) {
      add_action('codetot_after_post', array($this, 'render_section'), 10);
    }
  }

  public function get_categories() {
    $categories = get_the_category();

    return !empty($categories) ? wp_list_pluck($categories, 'term_id') : [];
  }

  public function get_tags() {
    $tags = get_the_tags();

    return !empty($tags) ? wp_list_pluck($tags, 'term_id') : [];
  }

  public function get_query_args() {
    if (!is_singular('post')) {
      return new WP_Error(
        '400',
        __('This query must work with singular post only.', 'ct-bones')
      );
    }

    $post_id = get_the_ID();

    $post_args = array(
      'posts_per_page' => apply_filters('codetot_related_posts_number', 3),
      'post__not_in' => array($post_id)
    );

    switch($this->query_type) :

      case 'category':
        $categories = $this->get_categories();

        if (!empty($categories)) {
          $post_args['category__in'] = $categories;
        }

        break;

      case 'tag':

        $tags = $this->get_tags();

        if (!empty($tags)) {
          $post_args['tag__in'] = $tags;
        }

        break;

      // Both of categories and tags
      default:

        $categories = $this->get_categories();

        $tax_query = array();

        if (!empty($categories)) {
          $tax_query[] = array(
            'taxonomy' => 'category',
            'field' => 'term_id',
            'terms' => $categories
          );
        }

        $tags = $this->get_tags();

        if (!empty($tags)) {
          $tax_query[] = array(
            'taxonomy' => 'tag',
            'field' => 'term_id',
            'terms' => $tags
          );
        }

        if (count($tax_query) > 1) {
          $tax_query['relation'] = 'OR';
        }

        $post_args['tax_query'] = $tax_query;

    endswitch;

    return $post_args;
  }

  public function render_section() {
    $post_args = $this->get_query_args();

    $post_query = new WP_Query($post_args);

    $post_layout = get_global_option('codetot_post_layout') ?? 'no-sidebar';
    $class = 'post-grid--related-posts';
    $class .= $post_layout === 'no-sidebar' ? ' default-section--no-container' : '';

    if ($post_query->have_posts()) {
      the_block('post-grid', array(
        'class' => $class,
        'title' => esc_html__('Related posts', 'ct-bones'),
        'query' => $post_query
      ));
    }
  }
}

Codetot_Related_Posts::instance();
