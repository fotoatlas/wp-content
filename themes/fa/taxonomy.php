<?php

	/*
	* Taxonomy template.
	*/

    get_header();

    if ( is_plugin_active( 'translatepress-multilingual/index.php' ) ) {
        
        // Get Locale and "de_DE" to "de"
        $lang = get_locale();
    
        if ( strlen( $lang ) > 0 ) {
            $lang = explode( '_', $lang )[0];
        }
    
        $site_url = get_site_url() . '/' . $lang;
    
    } else {
    
        $site_url = get_site_url();
    }

    // Program to display URL of current page. 
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
        $link = "https"; 
    else
        $link = "http"; 
    
    // Here append the common URL characters. 
    $link .= "://"; 
    // Append the host(domain name, ip) to the URL. 
    $link .= $_SERVER['HTTP_HOST']; 
    //Append the requested resource location to the URL 
    $link .= $_SERVER['REQUEST_URI'];
    
    $tax = explode('/', $link);

    // Get Locale and "de_DE" to "de"
    $lang = get_locale();
    if ( strlen( $lang ) > 0 ) {
        $lang = explode( '_', $lang )[0];
    }

    // Get Taxname
    if (get_locale() == 'en_US') {
        $tax_name = $tax[3];
        //$language = '/' . $tax[3];
    } else 
    if (get_locale() == 'tr_TR') {
        $tax_name = $tax[3];
        //$language = '';
    }

     // Get Tax parameter
    $tax_parameter = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

    // retrieve term_id from currently queried terms
    $queried_object = get_queried_object();
    $term_id = $queried_object->term_id;
    $term = get_term( $term_id );

    $term_taxonomy = $term->taxonomy;
    $term_name = $term->name;
    $term_count = $term->count;

    // $term_wiki = get_field('place_taxonomy_wikipedia', $term);
    
    // Modal Place
    get_template_part( 'loop-templates/modal', 'image' );

?>

<div class="container-fluid mx-0">

    <div 
        id="featured-image-taxonomy" 
        class="row bg-dark mb-3" 
        style="min-height:5vh;background-size:cover;background-position:center;background-image:url()-webkit-box-shadow: inset 0px 4px 32px 8px rgba(0,0,0,0.5); box-shadow: inset 0px 4px 32px 8px rgba(0,0,0,0.5);">

        <div id="taxonomyNameMobile" class="col-8 ps-3 h3 d-block d-md-none sticky-top pt-6 pb-6"></div>

    </div>
    <!-- Content -->
    <div class="row pb-5">

        <div class="col-9 pb-3">
            <h2 id="taxonomyName"></h2>
        </div> 

        <div class="col-3 pb-3">
            <div class="float-end">
                <div class="btn-group">
                    <a class="btn  btn-sm btn-primary btn-h" id="harita">Harita</a>
                    <a class="btn  btn-sm btn-primary btn-d" id="liste">Liste</a>
                </div>
            </div>
        </div>

        <div class="col-12 px-3">
            <div class="content" id="taxonomyDescription"></div>
            <div class="content" id="taxonomyWikitext"></div>
            <div class="content" id="taxonomyWikimore"></div>
            <div class="card-columns py-5 d-none" id="taxonomyRelated"></div>
        </div>

    </div>
    
</div>

<script>

    //let title = "<?php //echo $term_wiki; ?>";
    let lang = "<?php echo $lang; ?>";

    let t = "<?php echo $term_taxonomy; ?>";
    let i = "<?php echo $term_id; ?>";

    let site_url = "<?php echo $site_url; ?>";
    let tax_name = "<?php echo $tax_name ?>"; // Tur, Kultur, Tema
    let tax_parameter = "<?php echo $tax_parameter->slug ; ?>"; // Çeşme, Kale, Höyük
    // let site_language = "<?php //echo $language; ?>";

    //Wikipedia TR

    // fetch(`https://${lang}.wikipedia.org/w/api.php?action=parse&page=${title}&prop=text&section=0&format=json&origin=*`)
    // .then(response => response.json()).then(data => {

    //     console.log(data);
        
    //     let wikitext =  `${data.parse.text["*"]}`;
    //     let wikireplaced = wikitext.replaceAll('<a href="/wiki','<a target="_blank" href="https://tr.m.wikipedia.org/wiki');
        
    //     let wikimore=  `<a target="_blank" class="iframe-popup small" href="https://tr.m.wikipedia.org/wiki/${title}">✶ Vikipedi</a> - <a target="_blank" class="iframe-popup small" href="https://tr.m.wikipedia.org/wiki/${title}#/editor/0">Düzenle</a>`;

    //     let getElementWikitext = document.getElementById('placeWikitext');
    //     let getElementWikimore = document.getElementById('placeWikimore');
        
    //     getElementWikitext.innerHTML = wikireplaced;
    //     getElementWikimore.innerHTML = wikimore;

    // });

    // document.getElementById('placeWikitext').innerHTML = '';
    // document.getElementById('placeWikimore').innerHTML = '';


    document.getElementById("harita").href = `${site_url}/arastir/h/?_${tax_name}=${tax_parameter}&_per_page=1000`; 
    document.getElementById("liste").href = `${site_url}/arastir/l/?_${tax_name}=${tax_parameter}&_per_page=25`; 

    Promise.all([
    fetch(
        `<?php echo $site_url; ?>/wp-json/wp/v2/${t}/${i}?_fields=id,name,link,slug,meta,description`
        )
    ])
    .then(responses => {
        Promise.all(responses.map(res => res.json()))
            .then(objects => {

                let taxonomyDetails = objects[0];
                
                // console.log(taxonomyDetails);

                //image
                let featuredImage = '';
                if(taxonomyDetails.meta.place_taxonomy_image && taxonomyDetails.meta.place_taxonomy_image != 0){
                    fetch(`<?php echo $site_url; ?>/wp-json/wp/v2/media/${taxonomyDetails.meta.place_taxonomy_image}?_fields=media_details`)
                    .then(response => response.json()).then(data => {
                    
                        //console.log(data);

                        featuredImage = data.media_details.sizes.large.source_url;
                        document.getElementById('featured-image-taxonomy').style.backgroundImage = `url(${featuredImage})`;
                        document.getElementById('featured-image-taxonomy').style.display = 'block';
                    });
                    
                } else {
                    // bi placeholder image kullan (ya da featured image div'ine display:none cak)
                    //document.getElementById('featured-image-taxonomy').style.display = 'none';
                }

                // Name, Description
                let name =  `<a ref="${taxonomyDetails.link}">${taxonomyDetails.name}</a>`;
                let nameMobile =  `<a class="link-light" href="${taxonomyDetails.link}">${taxonomyDetails.name}</a>`;

                let description =  `<div class="pb-3">${taxonomyDetails.description}</div>`;

                let getElementName = document.getElementById('taxonomyName');
                //let getElementNameMobile = document.getElementById('taxonomyNameMobile');
                let getElementDescription = document.getElementById('taxonomyDescription');

                getElementName.innerHTML = name;
                //getElementNameMobile.innerHTML = nameMobile;
                getElementDescription.innerHTML = description;

                //Wikipedia TR to other languages

                if(taxonomyDetails.meta.place_taxonomy_wikipedia && taxonomyDetails.meta.place_taxonomy_wikipedia != 0){
                	fetch(`https://www.wikidata.org/w/api.php?action=wbgetentities&sites=trwiki&titles=${taxonomyDetails.meta.place_taxonomy_wikipedia}&normalize=1&format=json&props=sitelinks&origin=*`)
                	.then(response => response.json()).then(data => {
                        
                        let entity = data.entities;

                        console.log(entity);

                        for (let i in entity) {  


                            let sitelinks = entity[i].sitelinks;

                            let has_enwiki = sitelinks.hasOwnProperty('enwiki'); 

                            if (lang =='en') {
                               //console.log('lang is en!');

                               if (has_enwiki) {
                                    //console.log('enwiki exists.');
                                    let enwiki = entity[i].sitelinks.enwiki.title;
                                    wtitle = enwiki;
                                } else {
                                    wtitle = 'HTTP_404';                            
                                }

                            } else if (lang =='tr') {

                                //console.log('lang is tr!');
                                let trwiki = entity[i].sitelinks.trwiki.title;
                                wtitle = trwiki;
                                
                            } else {
                            //
                            }

                            console.log(i + '/' + lang + '/' + wtitle);

                            fetch(`https://${lang}.wikipedia.org/w/api.php?action=parse&page=${wtitle}&prop=text&section=0&format=json&origin=*`)
                	        .then(response => response.json()).then(data => {

                                let wikitext =  `${data.parse.text["*"]}`;
                                let wikireplaced = wikitext.replaceAll(`<a href="/wiki','<a target="_blank" href="https://${lang}.m.wikipedia.org/wiki`);
                                
                                let wikimore=  `<a target="_blank" class="iframe-popup small" href="https://${lang}.m.wikipedia.org/wiki/${wtitle}">✶ Vikipedi</a> - <a target="_blank" class="iframe-popup small" href="https://${lang}.m.wikipedia.org/wiki/${wtitle}#/editor/0">Düzenle</a>`;

                                let getElementWikitext = document.getElementById('taxonomyWikitext');
                                let getElementWikimore = document.getElementById('taxonomyWikimore');
                                
                                getElementWikitext.innerHTML = wikireplaced;
                                getElementWikimore.innerHTML = wikimore;

                                // Iframe Edit and Wiki Pages
                                jQuery(document).ready(function($) {
                                    let isMobile = window.matchMedia("only screen and (max-width: 760px)").matches;

                                    if (isMobile) {
                                        //Disable
                                    } else {
                                        $('.iframe-popup').magnificPopup({
                                            type: 'iframe',
                                            mainClass: 'mfp-with-zoom',
                                            showCloseBtn: false
                                        });
                                    }
                                });

                                // Read More for Wikipedia Parser
                                // https://github.com/jedfoster/Readmore.js
                                jQuery('.mw-parser-output').readmore({
                                    lessLink: '<div class="col-12 px-0 py-2"><a href="#">▲ Daralt</a></div>',
                                    moreLink: '<div class="col-12 px-0 py-3 border-bottom" style="position:relative;z-index:3;-webkit-box-shadow: 0px -1px 26px 30px #FFFFFF;box-shadow: 0px -1px 26px 30px #FFF;"><a href="#">▼ Devamı</a></div>',
                                    collapsedHeight: 360
                                });

                            });

                        }

                	});
                } else {
                    document.getElementById('taxonomyWikitext').innerHTML = '';
                    document.getElementById('taxonomyWikimore').innerHTML = '';
                }

            })
    });

</script>

<?php if ( have_posts() ) : ?>

    <div class="py-3">

        <div class="col-12 p-0 pt-5 px-3">    
            <?php echo '<div class="small py-3 text-dark">Toplam ' . $term_count . ' adet ilişkili kayıt var.</div>';?>
        </div>

        <div id="ms-container" class="row m-2" data-masonry='{"percentPosition": true }' >

            <?php while ( have_posts() ) : the_post(); ?>
        
                <a class="ms-item  col-12 col-sm-6 col-md-4 col-lg-3 mb-2 px-1" onclick="imageModalFunction('<?php echo get_the_ID()?>')">
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

<?php
get_footer();
