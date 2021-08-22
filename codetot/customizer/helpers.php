<?php

/**
 * Undocumented function
 *
 * @param string $field_id
 * @param string $type
 * @return void
 */
if ( !function_exists('codetot_get_theme_mod') ) :
  function codetot_get_theme_mod($field_id, $type = 'default') {
    $options = isset($type) && $type === 'pro' ? get_theme_mod('codetot_pro_settings') : get_theme_mod('codetot_theme_settings');

    if ( !empty($field_id) && isset($options[sanitize_key($field_id)]) ) {
      return $options[sanitize_key($field_id)];
    } else {
      return null;
    }
  }
endif;
