<?php
$container = 'container';
$has_woocommerce = class_exists('WooCommerce');
?>
<?php the_block_part('header/header-topbar');?>
<div class="header__wrapper">
  <div class="<?php echo $container; ?> header__container">
    <div class="grid header__grid">
      <?php the_block_part('header/navigation'); ?>
      <?php the_block_part('header/logo'); ?>
      <div class="grid__col header__col header__col--menu-icons">
        <div class="header__menu-icons">
          <?php
          do_action('codetot_header_icon_blocks');
          ?>
        </div>
      </div>
      <div class="grid__col header__col header__col--mobile-button">
        <?php the_block_part('header/mobile-menu-button'); ?>
      </div>
    </div>
  </div>
</div>
