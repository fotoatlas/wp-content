<?php
/**
 * Template Name: Map
 *
 * Template for displaying a page without sidebar even if a sidebar widget is published.
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
get_header();

?>

<?php
	get_template_part( 'loop-templates/modal', 'image' );
	get_template_part( 'loop-templates/content', 'map' );
?>

<?php
get_footer();
