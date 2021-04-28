<?php
$_title = !empty($title) ? $title : __('Page Not Found', 'ct-theme');
$_content = !empty($content) ? $content : __('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'ct-theme');
?>
<section class="page-404<?php if (!empty($class)) : echo ' ' . $class; endif; ?>">
  <div class="container page-404__container">
    <div class="grid page-404__grid ">
      <div class="grid__col page-404__col">
        <p class="page-404__error">404</p>
      </div>
      <div class="grid__col page-404__col">
        <h1 class="page-404__title"><?php echo $_title ?></h1>
        <div class="mt-1 page-404__content"><?php echo $_content ?></div>
        <?php if (!empty($after_content)) : ?>
          <div class="mt-1 page-404__after-content"><?php echo $after_content; ?></div>
        <?php endif; ?>
        <div class="mt-1 page-404__footer">
          <?php the_block('button', array(
            'button' => __('Back to Homepage', 'ct-theme'),
            'type' => 'dark',
            'class' => 'page-404__button',
            'url' => esc_url(home_url('/'))
          )); ?>
        </div>
      </div>
    </div>
  </div>
</section>
