<?php

add_action('admin_enqueue_scripts', 'codetot_admin_settings_enqueue_styles');
function codetot_admin_settings_enqueue_styles() {
    wp_enqueue_style('codetot-metabox-settings', CODETOT_ADMIN_ASSETS_URI . '/metabox-admin.css');
}