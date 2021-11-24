<?php
/**
 * Footer block
 *
 * @package ct-bones
 * @author codetot
 * @since 0.0.1
 */
do_action( 'codetot_before_footer' );

the_block_part( 'footer' );
the_block_part( 'modal-search-form' );
the_block( 'slideout-menu' );

do_action( 'codetot_footer' ); ?>
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>
