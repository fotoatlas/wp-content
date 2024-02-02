<?php
/**
 * Modal Filter Taxonomy
 */
?>

<div class="modal" id="button_filter" tabindex="-1" role="dialog" aria-labelledby="button_filterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-fullscreen-sm-down modal-filter" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">

                <div class="row m-0">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 py-3 border-bottom border-dark">
                        <?php echo facetwp_display( 'facet', 'ara' ); ?>  
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 py-3 border-bottom border-dark">
                        <?php echo facetwp_display( 'facet', 'tarih' ); ?>  
                    </div>
                    <div class="col-12 col-md-4 pt-3 border-bottom border-dark">    
                        <?php echo facetwp_display( 'facet', 'tur' ); ?>  
                    </div>
                    <div class="col-12 col-md-4 pt-3 border-start border-bottom border-dark">    
                        <?php echo facetwp_display( 'facet', 'bolge' ); ?>  
                    </div>

                    <div class="col-12 col-md-4 pt-3 border-start border-bottom border-dark">    
                        <?php echo facetwp_display( 'facet', 'konu' ); ?>  
                    </div>
                    <div class="col-12 col-md-4 pt-3 border-dark">    
                        <?php echo facetwp_display( 'facet', 'olusturan' ); ?>  
                    </div>
                    <div class="col-12 col-md-8 pt-3 border-start border-dark">    
                        <?php echo facetwp_display( 'facet', 'kaynak' ); ?>  
                    </div>

                </div>
                
            </div>
        </div>
    </div>
</div>