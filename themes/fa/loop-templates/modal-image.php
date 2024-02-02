<?php

    /**
     * Modal Image
     * PhotoSwipe
     */

    /**
     * Detect plugin. For frontend only.
     */

    if ( is_plugin_active( 'translatepress-multilingual/index.php' ) ) {
        
        $lang = get_locale();

        if ( strlen( $lang ) > 0 ) {
            $lang = explode( '_', $lang )[0];
        }

        $site_url = get_site_url() . '/' . $lang;

    } else {

        $site_url = get_site_url();
        
    }

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/openseadragon/4.1.0/openseadragon.min.js" integrity="sha512-VKBuvrXdP1AXvfs+m4l3ZNZSI4PFJF0K0hGJJZ4RiNRkvFMO4IwFRHkoTc7xsdZhMgkLn+Ioq4elndAZicBcRQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<!--  Modal -->
<div class="modal" id="imageDetailModal" tabindex="-1" aria-labelledby="imageModal" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-fullscreen-sm-down">
        <div class="modal-content shadow-lg border-0">

            <!-- <div class="modal-header">
                <button type="button" class="btn-close fixed-top-right" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> -->

            <button type="button" class=" btn fixed-top-right text-light pe-4" data-bs-dismiss="modal" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 384 512">
                    <path fill="currentColor" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/>
                </svg>
            </button>


            <div class="figure-thumb bg-primary"  id="openseadragon1" style="width:auto;height:calc(100vh - 215px);"></div>

            <div class="modal-body">

                <div class="d-flex bd-highlight">
                    <div class="me-auto p-2 bd-highlight">         
                        <div class="h3" id="imageTitle"></div>
                        <div class="small" id="imageCreatorNames"></div>
                        <div class="small" id="dateMin"></div>
                        <div id="imageTypeNames"></div>
                    </div>
                    <div class="p-2 bd-highlight" id="imageDetails">
                    </div>    
                </div>

            </div>

        </div>
    </div>
</div>

<script>
let modalimage = new bootstrap.Modal(document.getElementById('imageDetailModal'), {
    keyboard: false,
    //backdrop: false,
});

let imageDetailModal = document.getElementById('imageDetailModal');

imageDetailModal.addEventListener('hidden.bs.modal', function(event) {
    loading =
        '<div class="spinner-grow spinner-grow-md" role="status"><span class="visually-hidden">...</span</div>';
    document.getElementById('imageTitle').innerHTML = loading;
    //document.getElementById('featured-image').style.backgroundImage = 'url()';
    document.getElementById('openseadragon1').innerHTML = '';
    document.getElementById('imageTypeNames').innerHTML = '';
});

function imageModalFunction(e) {

    modalimage.toggle();

    fetch(`<?php echo $site_url; ?>/wp-json/fa/image/${e}`)
        .then(response => response.json()).then(imageDetails => {

            featuredImage = imageDetails.featured_media.full;

            let viewer = OpenSeadragon({
                id: "openseadragon1",
                prefixUrl:     "https://cdn.jsdelivr.net/gh/Benomrans/openseadragon-icons@main/images/",
                tileSources: {
                    type: "image", 
                    url: `${featuredImage}`
                }
            });

            // // // Image Types
            // if (imageDetails.image_type) {
            //     imageTypeNames = '<b>Tür: </b>' + imageDetails.image_type;
            //     let getElementTypes = document.getElementById('imageTypeNames');
            //     getElementTypes.innerHTML = imageTypeNames;
            // } else {
            //     //console.log('type yok :(');
            // }

            // // Image Creators
            if (imageDetails.image_creator) {
                imageCreatorNames = '' + imageDetails.image_creator;
                let getElementCreators = document.getElementById('imageCreatorNames');
                getElementCreators.innerHTML = imageCreatorNames;
            } else {
                //console.log('type yok :(');
            }

            let imageId = `${imageDetails.id}`;

            let dateMin = `${imageDetails.date.min}`;
            let imageTitle = `<a href="<?php echo $site_url; ?>/i/${imageDetails.slug}">${imageDetails.title}</a>`;
            let imageDetail_button = `<a class="btn btn-secondary rounded-pill btn-sm" href="<?php echo $site_url; ?>/i/${imageDetails.slug}" role="button">Detaylar</a>`;

            let getElementTitle = document.getElementById('imageTitle');
            let getElementDetails = document.getElementById('imageDetails');
            let getElementDate = document.getElementById('dateMin');

            getElementTitle.innerHTML = imageTitle;
            getElementDetails.innerHTML = imageDetail_button;
            getElementDate.innerHTML = dateMin;

        });
};
</script>