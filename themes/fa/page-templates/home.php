<?php
/**
 * Template Name: Home
 *
 * Template for displaying a page without sidebar even if a sidebar widget is published.
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
get_header();

?>

<!-- Intro -->
<?php //get_template_part( 'loop-templates/content', 'intro' ); ?>

<?php if ( wp_is_mobile() ) : ?>
	<!-- Mobile -->
	
	<?php get_template_part( 'loop-templates/modal', 'image' ); ?>
	<?php get_template_part( 'loop-templates/content', 'intro' ); ?>
	<?php the_content(); ?>
	<?php get_template_part( 'loop-templates/content', 'items' ); ?>
<?php else : ?>
	<!-- Desktop -->

	<?php get_template_part( 'loop-templates/modal', 'image' ); ?>
	<?php get_template_part( 'loop-templates/content', 'intro' ); ?>
	<?php the_content(); ?>
	<?php get_template_part( 'loop-templates/content', 'items' ); ?>
<?php endif; ?>

<?php
get_footer();
