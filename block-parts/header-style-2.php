<?php the_block_part('header/header-topbar');?>
<div class="header__wrapper">
  <div class="container header__container">
    <div class="grid header__grid">
      <div class="grid__col header__col header__col--mobile-menu-button">
        <?php the_block_part('header/mobile-menu-button'); ?>
      </div>
      <?php the_block_part('header/logo'); ?>
      <div class="grid__col header__col header__col--menu-icons">
        <div class="header__menu-icons">
          <?php
         the_block_part('header/phone-icon');
         the_block_part('header/search-icon');
         the_block_part('header/cart-icon');
         the_block_part('header/account-icon');
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
