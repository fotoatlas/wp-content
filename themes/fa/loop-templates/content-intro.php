<?php

    /**
     * 
     * Introduction 
     * Ajax Live Search 
     * 
     */

    $site_url = site_url();

    $args = array( 
        'post_type' => 'attachment', 
        'posts_per_page'   => 1, 
        'post_mime_type' => 'image', 
        'orderby' => 'rand',

        'no_found_rows' => true, // counts posts, remove if pagination required
        'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
        'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
        'cache_results'          => false
    ); 
    
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'media_tag',
            'terms' => array( 'featured' ),
            'field' => 'slug',
        ),
    );

    $attachments = get_posts( $args );

    if ( $attachments ) {
        foreach ( $attachments as $post ) {
            setup_postdata( $post );

            $imageThumb = wp_get_attachment_image_src( $post->ID, 'full' );
            // $imageCaption = wp_get_attachment_caption( $post->ID );
            // $creator = get_post_meta( get_the_ID(), 'place_media_creator', true );

            // Link to the parent post
            $parent_id = $post->post_parent;
            $parent_title = get_the_title( $parent_id );
            $parent_permalink = get_permalink( $parent_id );
            //$link = '• <a class="link-light" href="' . $parent_permalink . '">Sayfaya git →</a>';
            $link = $parent_permalink;

        }
        wp_reset_postdata();
    }
?>

<div class="row m-0 p-0" style="height:50vh">
    <div class="d-flex flex-column pt-1 p-3 m-0 bg-dark" style="-webkit-box-shadow: inset 0 0 20rem 1rem rgba(0,0,0,1); box-shadow: inset 0 0 20rem 1rem rgba(0,0,0,1);background-size:cover;background-position:center;background-image:url(<?php echo $imageThumb[0]; ?>);"/>
        
        <div class="col-lg-6 col-12 mx-auto my-auto main-search align-items-center justify-content-center">
            <form action="<?php echo $site_url; ?>/arastir/l/" method="get">
                <div class="input-group">
                    <input data-swplive="true" class="field form-control form-intro rounded-pill" type="search" placeholder="Bir anahtar kelime yazın" name="_ara">
                    <div class="input-group-append">
                        <button class="btn btn-dark rounded-pill ms-2" type="submit">Ara</button>
                    </div>
                </div>
            </form>

        </div>

        <div class="main-caption position-relative">
            <a href="<?php echo $link; ?>" class="bg-black text-light small px-2 py-1">
                <?php echo $parent_title; ?>
            </a>
        </div>

    </div>

</div>