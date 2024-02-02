<?php

/**
 * FacetWP Template - Map
 */
    $mapbox_token = 'pk.eyJ1IjoiYmlydGFraW1zZXlsZXIiLCJhIjoiY2tpdDZtcDhtMm4xdTJ2cWpobWJxMjBzZSJ9.wWKNyeoOUADTIPjL0vUrNg';

    $site_url = get_site_url();
    $themepath = get_stylesheet_directory_uri();

?>

<link href="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>

<?php if ( have_posts() ) : ?>

    <?php 

        // Build GeoJSON feature collection array
        $geojson = array(
            'type'      => 'FeatureCollection',
            'features'  => array()
        );
                            
        // Loop starting			
        while ( have_posts() ) : the_post();

            // Koordinatları ACF den al, sayı formatına dönüştür
            $lng = floatval(get_post_meta( $post->ID, 'geo_longitude', true ));
            $lat = floatval(get_post_meta( $post->ID, 'geo_latitude', true ));
            $dir = floatval(get_post_meta( $post->ID, 'view_direction', true ));
            $dis = floatval(get_post_meta( $post->ID, 'view_distance', true ));
            // $cat = strip_tags( get_the_term_list( $post->ID, 'place_group', '', ', ' ) );
            // $status = strip_tags( get_the_term_list( $post->ID, 'place_status', '', ', ' ) );

            $feature = array(
                'type' => 'Feature',
                // 'id' => get_the_ID(),
                'geometry' => array(
                'type' => 'Point',
                //'coordinates' => [substr($lng, 0, 10),substr($lat, 0, 10)]
                'coordinates' => [$lng, $lat]
                ),
                'properties' => array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'dir' => $dir,
                'cat' => 1,
                'dis' => $dis,
                // 'status' => $status
                )
            );
            
        // $geojson içindeki features'in içine göm
        array_push($geojson['features'], $feature);

    ?>

    <?php endwhile; ?>

    <div class="map" id="fa_map">
    </div>

    <div class="d-none" id="imageTo"></div>

    <div class="modal" id="button_options" tabindex="-1" aria-labelledby="button_optionsTitle" aria-hidden="true">
        <div class="modal-dialog modal-options">
            <div class="modal-content">
                <div class="modal-body bg-white rounded">
                
                    <div id="layer_menu" class="layer_menu"></div>

                </div>
            </div>
        </div>
    </div>

    <div id="dom-target" style="display:none;"><?php echo json_encode($geojson); ?></div>

    <script>

        themepath = '<?php echo $themepath; ?>'; 
        siteurl = '<?php echo $site_url; ?>';    
        
        document.addEventListener('facetwp-loaded', function() {
       
            locations = document.getElementById("dom-target").textContent;
            data = JSON.parse(locations);

            ////// Map
            mapboxgl.accessToken = '<?php print($mapbox_token); ?>';
            const map = new mapboxgl.Map({
                container: 'fa_map',
                style: 'mapbox://styles/mapbox/light-v11', // style URL
                center: [28.957778643097875,41.01311237445259], // lng lat
                zoom: 12,
                maxZoom: 20,
                minZoom: 2,
                // https://github.com/mapbox/mapbox-gl-js/blob/main/src/ui/default_locale.js
                locale: {
                    'GeolocateControl.FindMyLocation': 'Konumumu göster',
                    'NavigationControl.ZoomIn': 'Yakınlaş',
                    'NavigationControl.ZoomOut': 'Uzaklaş',
                    'NavigationControl.ResetBearing': 'Yönü kuzeye çevir',
                }
            });

            // Fit Bounds https://stackoverflow.com/a/39271186
            let bbox = turf.bbox(data);
            map.fitBounds(bbox, {padding: 20});


            // Add zoom and rotation controls to the map.
            const nav = new mapboxgl.NavigationControl();
            map.addControl(nav, 'bottom-right');

            //// Locate
            const geolocate = new mapboxgl.GeolocateControl({
                positionOptions: {
                    enableHighAccuracy: true
                },
                trackUserLocation: true
            });
            map.addControl(geolocate,'bottom-right');

            //// Local Storage Layer
            if (localStorage.getItem('layer') === null) {
                localStorage.setItem('layer_id', 'mapbox_light');
                localStorage.setItem('layer', 'mapbox://styles/mapbox/light-v11');
            } else {

                if (localStorage.getItem('layer_id') === 'mapbox_light') {
                    //
                } else {
                    var layer = localStorage.getItem('layer');
                    map.setStyle(layer);
                }

            }

            map.on("load", function () {

                //// Map Layers
                themepath = '<?php echo get_stylesheet_directory_uri(); ?>';

                // Layer Menu
                maplayers = [{
                    label: 'Satellite - Esri',
                    id: 'esri_satellite',
                    source: themepath + '/map/sat_esri.json'
                    }, 
                    // {
                    // label: 'Satellite - Bing',
                    // id: 'bing_satellite',
                    // source: themepath + '/map/sat_bing.json'
                    // }, 
                    // {
                    // label: 'Satellite - Maptiler',
                    // id: 'maptiler_satellite',
                    // source: 'https://api.maptiler.com/maps/hybrid/style.json?key=9vGulB01GLnb2fKleiwl'
                    // }, 
                    {
                    label: 'Satellite - Google',
                    id: 'google_satellite',
                    source: themepath + '/map/sat_google.json'
                    }, {
                    label: 'Satellite - Mapbox',
                    id: 'mapbox_satellite',
                    source: 'mapbox://styles/mapbox/satellite-v9'
                    }, {
                    label: 'Street Light - Carto',
                    id: 'carto_positron',
                    source: 'https://basemaps.cartocdn.com/gl/positron-gl-style/style.json'
                    }, {
                    label: 'Voyager - Carto',
                    id: 'carto_voyager',
                    source: 'https://basemaps.cartocdn.com/gl/voyager-gl-style/style.json'
                    }, {
                    label: 'Light - Mapbox',
                    id: 'mapbox_light',
                    source: 'mapbox://styles/mapbox/light-v11'
                    }, {
                    label: 'Street Dark - Carto',
                    id: 'carto_dark_matter',
                    source: 'https://basemaps.cartocdn.com/gl/dark-matter-gl-style/style.json'
                }];
                
                const menu = document.getElementById('layer_menu');
                // Switch Map Layers
                maplayers.forEach(function(l) {

                    let button = document.createElement('button');
                    button.className = 'layer';
                    button.id = l.id;
                    button.textContent = l.label;

                    button.addEventListener('click', function() {

                        let active_button = document.querySelector('.layer_active');
                        if(active_button) {
                            active_button.classList.remove('layer_active');
                            active_button.disabled = false;
                        } 
                        this.classList.add('layer_active'); 
                        this.disabled = true;

                        map.setStyle(l.source);
                        localStorage.setItem('layer', l.source);
                        localStorage.setItem('layer_id', l.id);

                    });

                    menu.appendChild(button);

                });
                
                // Show default layer highlighted
                let default_button = document.querySelector('#' + localStorage.getItem('layer_id') );
                default_button.classList.add('layer_active');
                default_button.disabled = true; 

                //mouse interactivity

                let popup = new mapboxgl.Popup({
                    closeOnClick: false,
                    closeButton: false,
                    offset: 15,
                });

                const enterHandler = e => { 

                    map.setFilter("placehover", ["==", "id", e.features[0].properties.id]);


                    // Change the cursor to a pointer when the mouse is over the markers layer.
                    map.getCanvas().style.cursor = 'pointer';
                
                    let coordinates = e.features[0].geometry.coordinates.slice();

                    const currentZoom = map.getZoom();

                    if ("ontouchstart" in document.documentElement)
                        {
                            console.log("your device is a touch screen device.");
                            let description = '<a id="modal" onclick="imageModalFunction(\'' + e.features[0].properties.id + '\')" >imageTo</a>';
                            let getElementPlace = document.getElementById('imageTo');
                            getElementPlace.innerHTML = description;

                        }
                    else
                        {
                            //console.log("your device is NOT a touch device");     

                            if (currentZoom > 21) {
                                // zoom in
                                let description = '<a id="modal" onclick="imageModalFunction(\'' + e.features[0].properties.id + '\')" >imageTo</a>';
                                let getElementPlace = document.getElementById('imageTo');
                                getElementPlace.innerHTML = description;
                            } else {
                                // zoom out
                                let description = '<a id="modal" class="popup-title" onclick="imageModalFunction(\'' + e.features[0].properties.id + '\')" >' + e.features[0].properties.title + '</a>';
                                popup.setLngLat(coordinates).setHTML(description).addTo(map);
                            }
                        }  
                }
                map.on('mouseenter', 'points', enterHandler);

                const clickHandler = e => {

                    let coordinates = e.lngLat;

                    const currentZoom = map.getZoom();

                    if (currentZoom > 16) {
                        zoom = currentZoom; 
                    } else {
                        zoom = 16;
                    }

                    map.flyTo({
                        center: coordinates,
                        zoom: zoom,
                        speed: 0.3,
                        curve: 1,
                        essential: true
                    });

                    if ("ontouchstart" in document.documentElement) {
                        //console.log(e.features[0].properties.id);
                        //console.log("your device is a touch device");
                        document.getElementById('modal').click();
                    } else {
                        //console.log(e.features[0].properties.id);
                        //console.log("your device is NOT a touch device");
                        document.getElementById('modal').click();
                    }

                }
                map.on('click', 'points', clickHandler);

                const leaveHandler = e => {
                    // Change it back to a pointer when it leaves.
                    map.setFilter("placehover", ["==", "id", ""]);
                    map.getCanvas().style.cursor = '';
                    popup.remove();
                }
                map.on('mouseleave', 'points', leaveHandler);

                map.on('moveend', function() {
                    // Update localStorage
                    localStorage.setItem('center', map.getCenter().toArray());
                    localStorage.setItem('zoom', map.getZoom());
                });

                map.on('contextmenu', function(e) {
            
                    let popup = new mapboxgl.Popup({ closeOnClick: true })

                    .setLngLat(e.lngLat)
                    .setHTML(e.lngLat.lat + ',</br>' + e.lngLat.lng)
                    .addTo(map);

                    //new mapboxgl.Marker().setLngLat(e.lngLat).addTo(map);
                    //console.log(e.lngLat.lat + ',' + e.lngLat.lng);
                });

            });

            map.on("style.load", function () {
            
                map.addSource("points", {
                    data: data,
                    type: "geojson",
                })
                map.addSource("slices", {
                    data: { type: 'FeatureCollection', features: '' },
                    type: "geojson",
                });
                        
                map.addLayer({
                    id: "pizzas",
                    type: "fill",
                    source: "slices",
                    minzoom: 15,
                    paint: {
                        "fill-color": {
                            property: "cat",
                            type: "categorical",
                            stops: [
                            [0, "#3bb2d0"],
                            [1, "#3bb2d0"],
                            [2, "#e55e5e"],
                            [3, "#ed6498"],
                            [4, "#fbb03b"],
                            ],
                        },
                        "fill-opacity": 0.1,
                        "fill-opacity-transition": { duration: 500 }
                    },
                })	
                map.addLayer({
                    id: "points",
                    type: "circle",
                    source: "points",
                    minzoom: 13,
                    paint: {
                    // "circle-blur": 0.2,
                    "circle-color": {
                        property: "cat",
                        type: "categorical",
                        stops: [
                        [0, "#3bb2d0"],
                        [1, "#000000"],
                        [2, "#e55e5e"],
                        [3, "#ed6498"],
                        [4, "#fbb03b"],
                        ],
                    },
                    "circle-opacity": 1,
                    "circle-stroke-width": 2,
                    "circle-stroke-color": "#FFFFFF",
                    "circle-radius": {
                            property: "cat",
                            stops: [
                            [{ zoom: 0, value: 0 }, 6],
                            [{ zoom: 0, value: 5 }, 6],
                            [{ zoom: 8, value: 0 }, 6],
                            [{ zoom: 8, value: 5 }, 8],
                            [{ zoom: 16, value: 0 }, 8],
                            [{ zoom: 16, value: 5 }, 8],
                            ],
                        },
                    },
                });

                //Intensity
                map.addLayer({
                    'id': 'placeheat',
                    'type': 'heatmap',
                    'source': 'points',
                    'layout': {
                        // Make the layer visible by default.
                        'visibility': 'visible',
                    },
                    'maxzoom': 18,
                    'paint': {
                        'heatmap-weight': 1,
                        'heatmap-intensity': [
                            'interpolate',
                            ['linear'],
                            ['zoom'],
                            0, 1,
                            20, 3
                        ],
                        'heatmap-color': [
                            'interpolate',
                            ['linear'],
                            ['heatmap-density'],
                            0, 'rgba(236,222,239,0)',
                            0.2, 'rgb(208,209,230)',
                            0.4, 'rgb(166,189,219)',
                            0.6, 'rgb(103,169,207)',
                            0.8, 'rgb(28,144,153)'
                        ],
                        'heatmap-radius': [
                            'interpolate',
                            ['linear'],
                            ['zoom'],
                            5, 2,
                            18, 20
                        ],
                        'heatmap-opacity': [
                            'interpolate',
                            ['linear'],
                            ['zoom'],
                            12, 1,
                            20, 0
                        ]
                    }
                });

                //Hover
                map.addLayer({
                    'id': 'placehover',
                    'type': 'circle',
                    'source': 'points',     
                    'paint': {
                        // 'circle-radius': {'base': 2,'stops': [[10, 10], [15, 50], [22, 100]]},
                        'circle-radius': 10,
                        'circle-opacity': 0.8,
                        'circle-pitch-alignment': 'map',
                        'circle-pitch-scale': 'map',
                        'circle-color': 'white'
                    },
                    "filter": ["==", "id", ""]
                });

            });

            map.on('style.load', function () {
                
                map.addSource('mapbox-dem', {
                    'type': 'raster-dem',
                    'url': 'mapbox://mapbox.mapbox-terrain-dem-v1',
                    'tileSize': 512,
                    'maxzoom': 14
                });
                // add the DEM source as a terrain layer with exaggerated height
                map.setTerrain({ 'source': 'mapbox-dem', 'exaggeration': 1.5 });
            
            });

            // Create Slices by Boundingbox
            const sliceHandler = e => {

                const lastZoom = 16;

                const currentZoom = map.getZoom();

                if (currentZoom > lastZoom) {

                    //Fade Layers
                    map.setPaintProperty('pizzas', 'fill-opacity', 0.1);

                    let arcResolution = 180;
                    let sweepAngle = 60;
                    let slices = [];
                    
                    data.features.forEach(function (pt) {

                        for (let m = 15; m <= 60; m += 15) {
                            let sliceShape = [pt.geometry.coordinates];
                            let bearing = pt.properties.dir - 360;
                            let dis = pt.properties.dis;
                            let cat = pt.properties.cat;

                            for (let a = 0; a < arcResolution; a++) {
                                let destination = turf.destination(
                                pt,
                                (dis * m) / 200, // 60
                                bearing - ((a - arcResolution / 2) * sweepAngle) / arcResolution,
                                "miles"
                                ).geometry.coordinates;
                                sliceShape.push(destination);
                            }
                            //finish up pizza slice
                            sliceShape.push(pt.geometry.coordinates);
                            // add pizza slice for this time threshold to the total slices
                            slices.push(
                                turf.polygon([sliceShape], {
                                time: m,
                                cat: cat,
                                })
                            );
                        }
                        
                    });

                    slices = turf.featureCollection(slices);

                    // console.log(data);
                    // console.log(slices);

                    map.getSource('slices').setData(slices);
                    
                } else {
                    //Fade Layers
                    map.setPaintProperty('pizzas', 'fill-opacity', 0);
                }

            }

            map.on('style.load', sliceHandler);
            map.on('moveend', sliceHandler);

        });

    </script>

<?php else : ?>

<?php //get_template_part( 'loop-templates/content', 'none' ); ?>

<?php endif; ?>
