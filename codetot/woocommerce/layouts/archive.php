<?php

add_action('codetot_after_header', 'ct_bones_woocommerce_layout_archive_open', 10);
add_action('codetot_after_sidebar', 'ct_bones_woocommerce_layout_archive_close', 90);
add_action('codetot_before_sidebar', 'ct_bones_woocommerce_layout_archive_sidebar_open', 10);

function ct_bones_woocommerce_layout_archive_open() {
  if (is_shop() || is_product_category()) {
    echo '<div class="page-block page-block--archive">';
    echo '<div class="container page-block__container">';
    echo '<div class="grid page-block__grid">';
    echo '<div class="grid__col page-block__col page-block__col--main">';
  }
}

function ct_bones_woocommerce_layout_archive_close() {
  if (is_shop() || is_product_category()) {
    echo '</div>'; // Close .page-block__col--sidebar
    echo '</div>'; // Close .page-block__grid
    echo '</div>'; // Close .page-block__container
    echo '</div>'; // Close .page-block--archive
  }
}

function ct_bones_woocommerce_layout_archive_sidebar_open() {
  if (is_shop() || is_product_category()) {
    echo '</div>';
    echo '<div class="grid__col page-block__col page-block__col--sidebar">';
  }
}
