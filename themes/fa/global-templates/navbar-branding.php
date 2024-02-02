<?php
/**
 * Navbar branding
 *
 * @package Understrap
 * @since 1.2.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! has_custom_logo() ) { ?>

	<?php if ( is_front_page() && is_home() ) : ?>

		<h1 class="navbar-brand rounded-0 bg-black mb-0 ">
			<a rel="home" href="<?php echo esc_url( home_url( '/' ) ); ?>" itemprop="url">
				<?php bloginfo( 'name' ); ?>
			</a>
		</h1>

	<?php else : ?>

		<a 
			class="navbar-brand rounded-0 bg-black text-light" 
			rel="home" 
			href="<?php echo esc_url( home_url( '/' ) ); ?>"
		>
			<?php bloginfo( 'name' ); ?>
		</a>
		
	<?php endif; ?>

	<?php
} else {
	the_custom_logo();
}
