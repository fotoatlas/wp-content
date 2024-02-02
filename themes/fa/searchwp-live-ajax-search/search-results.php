<?php
/**
 * Search results are contained within a div.searchwp-live-search-results
 * which you can style accordingly as you would any other element on your site
 *
 * Some base styles are output in wp_footer that do nothing but position the
 * results container and apply a default transition, you can disable that by
 * adding the following to your theme's functions.php:
 *
 * add_filter( 'searchwp_live_search_base_styles', '__return_false' );
 *
 * There is a separate stylesheet that is also enqueued that applies the default
 * results theme (the visual styles) but you can disable that too by adding
 * the following to your theme's functions.php:
 *
 * wp_dequeue_style( 'searchwp-live-search' );
 *
 * You can use ~/searchwp-live-search/assets/styles/style.css as a guide to customize
 */
?>

<?php if ( have_posts() ) : ?>
	
		<?php while ( have_posts() ) : the_post(); ?>
			<?php $post_type = get_post_type_object( get_post_type() ); ?>
			<div class="row pb-1">
				<div class="col-md-2 col-5 rounded bg-primary" style="background: no-repeat url('<?php echo get_the_post_thumbnail_url( $post->ID, array( 75, 75) ); ?>');background-size:auto;"></div>
				<div class="col-md-10 col-7 pb-3">
				
					<div class="entry-title" role="option" id="" aria-selected="false">
						<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
					</div>
					<small>
					<?php echo get_the_term_list( $post->ID, 'image_type', ' ', ', ' ); ?>
					<?php						
						// https://developer.wordpress.org/reference/functions/wp_list_categories/#Display_Terms_in_a_custom_taxonomy						
						$taxonomy = 'image_administrative';
						// Get the term IDs assigned to post.
						$post_terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
						// Separator between links.
						$separator = ', ';
						if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ) {
							$term_ids = implode( ',' , $post_terms );
							$terms = wp_list_categories( array(
								'title_li' => '',
								'style'    => 'none',
								'echo'     => false,
								'taxonomy' => $taxonomy,
								'include'  => $term_ids
							) );
							$terms = rtrim( trim( str_replace( '<br />',  $separator, $terms ) ), $separator );
							// Display post categories.
							echo ' - ';
							echo  $terms;
						}
					?>
					</small>
				
				</div>
			</div>
		<?php endwhile; ?>
	
<?php else : ?>
	<p class="searchwp-live-search-no-results" role="option">
		<em><?php esc_html_e( 'No results found.', 'searchwp-live-ajax-search' ); ?></em>
	</p>
<?php endif; ?>
