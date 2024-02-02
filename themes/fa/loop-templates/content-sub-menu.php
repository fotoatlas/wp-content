<?php
/**
 * FacetWP Template - Selections
 */

// $curauth = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug', $author_name ) : get_userdata( intval( $author ) );

?>

<?php if ( is_page('l') || is_page('h') ) : ?>
    <div id="sub-menu-left" class="col-8 mt-5 ms-3 pt-2 text-dark fixed-top-left">
        <button title="Filtrele" class="btn  btn-sm rounded-pill btn-primary float-start mb-3 me-2 pe-3 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#button_filter">
            <svg width="18" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="sliders-h" class="svg-inline--fa fa-sliders-h fa-w-16 mx-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M496 384H160v-16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v16H16c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h80v16c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-16h336c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm0-160h-80v-16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v16H16c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h336v16c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-16h80c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zm0-160H288V48c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v16H16C7.2 64 0 71.2 0 80v32c0 8.8 7.2 16 16 16h208v16c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-16h208c8.8 0 16-7.2 16-16V80c0-8.8-7.2-16-16-16z"></path></svg>
            Filtrele
        </button>
        <?php echo facetwp_display( 'selections' ); ?>
    </div>

    <div id="sub-menu-right" class="col-4 mt-5 me-3 pt-2 text-dark fixed-top-right">

        <?php if ( is_page('h') ) : ?>
            <!-- List + Layers-->
            <div class="btn-group btn-sm float-end mb-3 ms-2">
                <a title="Harita görünümü" class="btn  btn-sm btn-primary active d-flex align-items-center rounded-0" href="#">
                <svg class="mx-1" xmlns="http://www.w3.org/2000/svg" height="21" viewBox="0 0 576 512"><path fill="currentColor" d="M384 476.1L192 421.2V35.9L384 90.8V476.1zm32-1.2V88.4L543.1 37.5c15.8-6.3 32.9 5.3 32.9 22.3V394.6c0 9.8-6 18.6-15.1 22.3L416 474.8zM15.1 95.1L160 37.2V423.6L32.9 474.5C17.1 480.8 0 469.2 0 452.2V117.4c0-9.8 6-18.6 15.1-22.3z"/></svg>
                    
                </a>
                <a title="Liste görünümü" class="btn  btn-sm btn-primary btn-switch d-flex align-items-center rounded-0" href="#">
                <svg class="mx-1" xmlns="http://www.w3.org/2000/svg" height="21" viewBox="0 0 512 512"><path fill="currentColor" d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm88 64v64H64V96h88zm56 0h88v64H208V96zm240 0v64H360V96h88zM64 224h88v64H64V224zm232 0v64H208V224h88zm64 0h88v64H360V224zM152 352v64H64V352h88zm56 0h88v64H208V352zm240 0v64H360V352h88z"/></svg>
                    
                </a>
            </div>

            <button title="Katmanlar" class="btn  btn-sm rounded-pill btn-primary float-end mb-3 ms-2" data-bs-toggle="modal" data-bs-target="#button_options"><svg height="16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="layer-group" class="svg-inline--fa fa-layer-group fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M12.41 148.02l232.94 105.67c6.8 3.09 14.49 3.09 21.29 0l232.94-105.67c16.55-7.51 16.55-32.52 0-40.03L266.65 2.31a25.607 25.607 0 0 0-21.29 0L12.41 107.98c-16.55 7.51-16.55 32.53 0 40.04zm487.18 88.28l-58.09-26.33-161.64 73.27c-7.56 3.43-15.59 5.17-23.86 5.17s-16.29-1.74-23.86-5.17L70.51 209.97l-58.1 26.33c-16.55 7.5-16.55 32.5 0 40l232.94 105.59c6.8 3.08 14.49 3.08 21.29 0L499.59 276.3c16.55-7.5 16.55-32.5 0-40zm0 127.8l-57.87-26.23-161.86 73.37c-7.56 3.43-15.59 5.17-23.86 5.17s-16.29-1.74-23.86-5.17L70.29 337.87 12.41 364.1c-16.55 7.5-16.55 32.5 0 40l232.94 105.59c6.8 3.08 14.49 3.08 21.29 0L499.59 404.1c16.55-7.5 16.55-32.5 0-40z"></path></svg></button>
            <!-- <button title="Noktaları listele" class="btn  btn-sm rounded-pill btn-primary float-end mb-3 ms-2" data-bs-toggle="modal" data-bs-target="#button_visiblelist"><svg height="16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="list-ul" class="svg-inline--fa fa-list-ul fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M48 48a48 48 0 1 0 48 48 48 48 0 0 0-48-48zm0 160a48 48 0 1 0 48 48 48 48 0 0 0-48-48zm0 160a48 48 0 1 0 48 48 48 48 0 0 0-48-48zm448 16H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zm0-320H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16zm0 160H176a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h320a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16z"></path></svg></button> -->
        
        <?php endif; ?>

        <?php if ( is_page('l')) : ?>

            <div class="btn-group btn-sm float-end mb-3 ms-2">
                <a title="Harita görünümü" class="btn  btn-sm btn-primary btn-switch d-flex align-items-center rounded-0" href="#">
                <svg class="mx-1" xmlns="http://www.w3.org/2000/svg" height="21" viewBox="0 0 576 512"><path fill="currentColor" d="M384 476.1L192 421.2V35.9L384 90.8V476.1zm32-1.2V88.4L543.1 37.5c15.8-6.3 32.9 5.3 32.9 22.3V394.6c0 9.8-6 18.6-15.1 22.3L416 474.8zM15.1 95.1L160 37.2V423.6L32.9 474.5C17.1 480.8 0 469.2 0 452.2V117.4c0-9.8 6-18.6 15.1-22.3z"/></svg>
                    
                </a>
                <a title="Liste görünümü" class="btn  btn-sm btn-primary active d-flex align-items-center rounded-0" href="#">
                <svg class="mx-1" xmlns="http://www.w3.org/2000/svg" height="21" viewBox="0 0 512 512"><path fill="currentColor" d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm88 64v64H64V96h88zm56 0h88v64H208V96zm240 0v64H360V96h88zM64 224h88v64H64V224zm232 0v64H208V224h88zm64 0h88v64H360V224zM152 352v64H64V352h88zm56 0h88v64H208V352zm240 0v64H360V352h88z"/></svg>
                    
                </a>
            </div>

            <div class="rounded-pill btn  btn-sm btn-primary float-end ms-2 mb-3">                
                <?php echo facetwp_display( 'facet', 'per_page' ); ?>
            </div>
            
            <div class="rounded-pill btn  btn-sm rounded-pill btn-primary float-end ms-2 mb-3">                
                <?php echo facetwp_display( 'sort' ); ?>
            </div>

        <?php endif; ?>

        <?php if ( is_author() ) : ?>
            <div class="btn  btn-sm btn-primary float-end ms-2 mb-3">                
                <?php echo facetwp_display( 'facet', 'per_page' ); ?>
            </div>
            <div class="btn  btn-sm rounded-pill btn-primary float-end ms-2 mb-3">                
                <?php echo facetwp_display( 'sort' ); ?>
            </div>
        <?php endif; ?>

        <!-- <button title="Paylaş" class="btn  btn-sm rounded-pill btn-primary float-end ms-2 mb-3" data-bs-toggle="modal" data-bs-target="#ShareModal">                
            <svg height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M246.6 9.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 109.3V320c0 17.7 14.3 32 32 32s32-14.3 32-32V109.3l73.4 73.4c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-128-128zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64c0 53 43 96 96 96H352c53 0 96-43 96-96V352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V352z"/></svg>
        </button> -->

    </div>

    <div class="col-12 fixed-bottom-left px-3">
        <div class="float-sm-start">
            <?php echo facetwp_display( 'facet', 'sayfa' ); ?>
        </div>
        <div class="btn  btn-sm rounded-pill btn-light float-start mb-3 ms-3 px-3 d-none d-md-block d-lg-block">                
            <?php echo facetwp_display( 'facet', 'sonuc' ); ?>
        </div>  
    </div>
<?php endif; ?>