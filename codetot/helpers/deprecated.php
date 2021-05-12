<?php

function codetot_get_single_sidebar() {
  return get_global_option('codetot_post_layout') ?? 'no-sidebar';
}

function codetot_get_page_sidebar() {
  return get_global_option('codetot_page_layout') ?? 'no-sidebar';
}

function codetot_get_category_sidebar_on_single() {
  return get_global_option('codetot_category_layout') ?? 'no-sidebar';
}

function codetot_get_category_column_number() {
  return get_global_option('category_column_number') ?? '1';
}

function codetot_get_category_post_card_style() {
  return get_global_option('post_card_style') ?? 'style-1';
}

/**
 * @param WP_Post $post
 * @param array $types
 * @return array
 */
function codetot_get_share_post_links($post) {
  $types = ['linkedin', 'facebook', 'twitter', 'pinterest'];
  $items = array();

  if (!empty($types)) {
    foreach ($types as $type) {
      $items[] = array(
        'type' => $type,
        'url' => codetot_get_share_post_link($post, $type)
      );
    }
  }

  return $items;
}

/**
 * @param WP_Post $post
 * @param string $type
 * @return string
 */
function codetot_get_share_post_link($post, $type) {
  $post_link = get_permalink($post->ID);

  switch ($type) {
    case 'twitter';
      return sprintf('https://twitter.com/share?url=%s', get_permalink($post->ID));
      break;

    case 'pinterest':
      return sprintf('https://pinterest.com/pin/create/button/?url=%1$s&amp;media=%2$s&amp;description=%3$s',
        $post_link,
        (has_post_thumbnail() ? wp_get_attachment_image_url(get_post_thumbnail_id($post->ID)) : ''),
        get_the_title($post->ID)
      );
      break;

    case 'facebook':
      return sprintf('https://www.facebook.com/sharer.php?u=%1$s',
        $post_link
      );
      break;

    case 'linkedin':
      return sprintf('https://www.linkedin.com/shareArticle?mini=true&url=%1$s&title=%2$s',
        $post_link,
        get_the_title($post->ID)
      );
      break;

    default:
      return apply_filters('codetot_share_post_link_' . esc_attr($type), '');

  }
}
