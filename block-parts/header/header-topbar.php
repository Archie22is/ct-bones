<?php
$class = 'header-topbar';
$class .= $columns <= 1 ? ' has-1-column' : ' has-' .$columns . '-columns';

$enable_topbar = codetot_get_theme_mod('enable_topbar_widget') ?? false;
$columns = (int) codetot_get_theme_mod('topbar_widget_column') ?? 1;

if ($enable_topbar) : ?>
  <div class="<?php echo $class; ?>">
    <div class="container header-topbar__container">
      <div class="grid header-topbar__grid">
        <?php for($i = 1; $i <= $columns; $i++) :
            $column_class = 'grid__col header-topbar__col';
            $column_class .= $i === 1 ? ' header-topbar__col--left' : ' header-topbar__col--right';
          ?>
          <div class="<?php echo $column_class; ?>">
            <div class="header-topbar__content"><?php dynamic_sidebar('topbar-column-' . $i); ?></div>
          </div>
        <?php endfor; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
