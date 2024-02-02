<?php
/**
 * FacetWP Template - List Masonry
 */
?>

<?php if ( have_posts() ) : ?>

<div class="py-5 my-5">

    <div class="col-12 p-0 px-3">    
        <?php //echo '<div class="small py-3 text-dark">Toplam ' . $term_count . ' adet ilişkili kayıt var.</div>';?>
    </div>

    <div id="ms-container" class="row m-2" data-masonry='{"percentPosition": true }' >

        <?php while ( have_posts() ) : the_post(); ?>
    
            <a class="ms-item col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 mb-2 px-1" onclick="imageModalFunction('<?php echo get_the_ID()?>')">
                <div class="card">

                    <?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) {
                        echo the_post_thumbnail( 'medium', array( 'class'  => 'card-img figure-img img-fluid m-0' ) ); // show featured image
                        } else {
                        //echo main_image(); //function to call first uploaded image in functions file
                    } ?>  

                    <div class="card-img-overlay p-1 d-flex align-items-end">
                        <div class="card-body p-0 px-1">
                            <p class="mb-0 text-light small">● <?php the_title(); ?></p>     
                        </div>    
                    </div>
                   
                </div>
            </a>

        <?php endwhile; ?>

    </div>

</div>

<?php else : ?>
<?php get_template_part( 'loop-templates/content', 'none' ); ?>
<?php endif; ?>

<script>

(function($) {
    $(document).on('facetwp-loaded', function() {
        var $grid = $("#ms-container");
            $grid.masonry({
                itemSelector: ".ms-item"
            });
    });
})(jQuery);

</script>