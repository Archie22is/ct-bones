<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CT_Bones
 */

?>
<?php
do_action( 'codetot_before_page' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php do_action( 'codetot_page' ); ?>
</article><!-- #post-<?php the_ID(); ?> -->
<?php do_action( 'codetot_after_page' );
?>
