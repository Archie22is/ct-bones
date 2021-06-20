<?php
$container = 'container';
$has_woocommerce = class_exists('WooCommerce');
$search_form_button = get_block('button', array(
  'icon' => 'search',
  'type' => 'primary',
  'class' => 'header__search-button',
  'attr' => ' type="submit"'
));
?>
<?php the_block_part('header/header-topbar');?>
<div class="header__wrapper">
  <div class="header__row header__row--main">
    <div class="<?php echo $container; ?> header__container">
      <div class="grid header__grid">
        <div class="grid__col header__col header__col--mobile-button">
          <?php the_block_part('header/mobile-menu-button'); ?>
        </div>
        <?php the_block_part('header/logo'); ?>
        <div class="grid__col header__col header__col--search-form">
          <div class="header__widget header__widget--form header__widget--form-product">
            <?php echo get_search_form(array('id' => 'header')); ?>
          </div>
        </div>
        <div class="grid__col header__col header__col--menu-icons">
        <?php echo do_shortcode('[contact]'); ?>
          <div class="header__menu-icons">
            <?php
            the_block_part('header/search-icon');
            if ($has_woocommerce) {
              the_block_part('header/account-icon');
              the_block_part('header/cart-icon');
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="header__row header__row--navigation">
    <div class="<?php echo $container; ?> header__container">
      <div class="grid header__grid">
        <?php the_block_part('header/vertical'); ?>
        <?php the_block_part('header/navigation'); ?>
      </div>
    </div>
  </div>
</div>
