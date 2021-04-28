<?php
$enable_topbar = get_global_option('codetot_header_topbar_enable') ?? false;
$topbar_content = get_codetot_data('codetot_header_topbar_content') ?? null;

if ($enable_topbar && !empty($topbar_content)) : ?>
  <div class="header-topbar">
    <div class="container header-topbar__container">
      <div class="grid header-topbar__grid">
        <div class="grid__col header-topbar__col">
          <div class="header-topbar__content"><?php echo do_shortcode($topbar_content); ?></div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
