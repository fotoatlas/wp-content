<?php
/**
 * Template Name: Browse List
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
get_header();

$site_url = get_site_url();

?>

<?php if ( wp_is_mobile() ) : ?>

	<!-- Mobile -->

	<?php get_template_part( 'loop-templates/modal', 'image' ); ?>
	<?php get_template_part( 'loop-templates/content-sub-menu'); ?>
    <?php get_template_part( 'loop-templates/modal-filter'); ?>

	<?php echo facetwp_display( 'template', 'list' ); ?>
	
<?php else : ?>

	<!-- Desktop -->

	<?php get_template_part( 'loop-templates/modal', 'image' ); ?>
	<?php get_template_part( 'loop-templates/content-sub-menu'); ?>
    <?php get_template_part( 'loop-templates/modal-filter'); ?>

	<?php echo facetwp_display( 'template', 'list' ); ?>

<?php endif; ?>

<script>

	const site_url = '<?php echo $site_url;?>'; // Replace 'your_site_url' with the actual value
	const btnSwitch = document.querySelector('.btn-switch');

	document.addEventListener('facetwp-loaded', function() {
		if (window.location.search) {

			let url = new URL(window.location.href);
			url.searchParams.delete('_paged');
			url.searchParams.delete('_per_page');
			url.searchParams.delete('_sort');
			var end = url.search;

			btnSwitch.setAttribute('href', site_url + '/arastir/h/' + end);
		} else {
			btnSwitch.setAttribute('href', site_url + '/arastir/h/');
		}
	});
	
</script>

<?php
get_footer();
