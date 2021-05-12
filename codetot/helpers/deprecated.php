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

