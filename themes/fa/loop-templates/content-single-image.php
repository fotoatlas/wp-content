<?php
/**
 * The template for displaying all single posts
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
$container = get_theme_mod( 'understrap_container_type' );

$single_lat = get_post_meta($post->ID, "geo_latitude", true );
$single_long = get_post_meta($post->ID, "geo_longitude", true );
$bear = get_post_meta($post->ID, "view_direction", true );

$date_min = get_post_meta($post->ID, "image_date_min", true );
$date_max = get_post_meta($post->ID, "image_date_max", true );

// Date view
if (empty($date_max)) {

	$date_view = get_post_meta($post->ID, "image_date_min", true );

} else {

	if ($date_min == $date_max) {
		// Values of x and y are the same.
		$date_view = get_post_meta($post->ID, "image_date_min", true );
	} else {
		// Values of x and y are different.
		$date_view = get_post_meta($post->ID, "image_date_min", true ) . ' – ' . get_post_meta($post->ID, "image_date_max", true );
	}

}

$permalink = get_post_meta($post->ID, "image_oa_url", true );
$id = get_post_meta($post->ID, "image_oa_id", true );

$site_url = get_site_url();
$pluginpath = plugins_url();


// $the_theme     = wp_get_theme();
// $theme_version = $the_theme->get( 'Version' );
// $semantic_tags = "/js/recogito-semantic-tags.min.js";
// $js_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . $semantic_tags );
// wp_enqueue_script( 'recogito-semantic-tags', get_stylesheet_directory_uri() . $semantic_tags, array(), $js_version, true );

?>

<link href="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/@recogito/recogito-js@1.8.4/dist/recogito.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/@recogito/recogito-js@1.8.4/dist/recogito.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@recogito/annotorious@2.7.13/dist/annotorious.min.css">

<script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-firestore.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@recogito/annotorious@2.7.13/dist/annotorious.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@recogito/firebase-storage@latest/dist/recogito-firebase-storage.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@recogito/annotorious-openseadragon@2.7.14/dist/openseadragon-annotorious.min.js"></script>

<link rel="stylesheet" href="https://www.unpkg.com/photoswipe@5.2.8/dist/photoswipe.css">
<script src="https://www.unpkg.com/photoswipe@5.2.8/dist/umd/photoswipe-lightbox.umd.min.js"></script>
<script src="https://www.unpkg.com/photoswipe@5.2.8/dist/umd/photoswipe.umd.min.js"></script>

<style>

	.r6o-editor {
		font-family: unset;
		line-height: unset;
	}

	.r6o-btn {
		padding: unset;
		font-size: unset;
	}

	.r6o-widget.comment .r6o-arrow-down {
		line-height: 1;
	}

	.a9s-outer,
	.a9s-inner {
		stroke: unset !important;
	}

	.a9s-annotationlayer:hover, 
	.a9s-outer{
		stroke: #FFFFFFb3;
		stroke-width: 1px !important;
	}

	.r6o-widget.r6o-tag ul.r6o-taglist li .r6o-delete-wrapper svg {
		vertical-align: middle;
	}

</style>

<div class="row">

	<?php
	// Check if the post has the specified term
	if (has_term(array('konum-yok', 'henuz-konumu-isaretlenmedi'), 'image_subject', $post->ID)) { ?>

	<?php } else { ?>

		<div class="col-md-4 col-sm-6 col-12 p-0 m-0">

			<div id="fa_map_single" class="map-single">

				<div id="sub-menu-left" class="mt-5 ms-3 pt-2 text-dark fixed-top-left">
					<button title="Katmanlar" style="position:relative;z-index:9999;" class="btn  btn-sm rounded-pill btn-primary float-start" data-bs-toggle="modal" data-bs-target="#button_options"><svg height="16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="layer-group" class="svg-inline--fa fa-layer-group fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M12.41 148.02l232.94 105.67c6.8 3.09 14.49 3.09 21.29 0l232.94-105.67c16.55-7.51 16.55-32.52 0-40.03L266.65 2.31a25.607 25.607 0 0 0-21.29 0L12.41 107.98c-16.55 7.51-16.55 32.53 0 40.04zm487.18 88.28l-58.09-26.33-161.64 73.27c-7.56 3.43-15.59 5.17-23.86 5.17s-16.29-1.74-23.86-5.17L70.51 209.97l-58.1 26.33c-16.55 7.5-16.55 32.5 0 40l232.94 105.59c6.8 3.08 14.49 3.08 21.29 0L499.59 276.3c16.55-7.5 16.55-32.5 0-40zm0 127.8l-57.87-26.23-161.86 73.37c-7.56 3.43-15.59 5.17-23.86 5.17s-16.29-1.74-23.86-5.17L70.29 337.87 12.41 364.1c-16.55 7.5-16.55 32.5 0 40l232.94 105.59c6.8 3.08 14.49 3.08 21.29 0L499.59 404.1c16.55-7.5 16.55-32.5 0-40z"></path></svg></button>
				</div>

			</div>

		</div>

		<div class="modal" id="button_options" tabindex="-1" aria-labelledby="button_optionsTitle" aria-hidden="true">
			<div class="modal-dialog modal-options">
				<div class="modal-content">
					<div class="modal-body bg-white rounded">
					
						<div id="layer_menu" class="layer_menu"></div>

					</div>
				</div>
			</div>
		</div>
		
		<script>

			// Check Hash at first
			// if(window.location.hash) {
			//     checkhash = 1; 
			//     //console.log('hash exist ' + checkhash);
			// } else {
			//     checkhash = 0; 
			//     //console.log('hash does not exist '  + checkhash);
			// }


			const pluginpath = '<?php echo $pluginpath; ?>';
			const siteurl = '<?php echo get_site_url(); ?>';

			data = pluginpath + '/fa-geojson/geo.json';
			data_single = JSON.parse(
				'{"type": "FeatureCollection", "features": [{"type": "Feature","properties": {},"geometry": {"type": "Point","coordinates": [<?php print($single_long); ?>,<?php print($single_lat); ?>]}}]}'
			);

			let slices = [];

			function processBearNumber(bearNumber) {
				// Check if bearNumber is greater than 90
				if (bearNumber > 180) {
					// If bearNumber is greater than 90, turn it to negative
					return -1 * (360 - bearNumber);
				} else {
					// If bearNumber is not greater than 90, return it as is
					return bearNumber;
				}
			}

			// Example usage:
			let id = <?php print($post->ID); ?>;
			let bearNumber = <?php print($bear); ?>;
			let processedBearNumber = processBearNumber(bearNumber);

			// console.log(id);
			// console.log(processedBearNumber); // Output: -100
			// console.log(data_single);

			mapboxgl.accessToken = 'pk.eyJ1IjoiYmlydGFraW1zZXlsZXIiLCJhIjoiY2tpdDZtcDhtMm4xdTJ2cWpobWJxMjBzZSJ9.wWKNyeoOUADTIPjL0vUrNg';
			const map = new mapboxgl.Map({
				container: 'fa_map_single',
				//hash: true,
				style: 'mapbox://styles/mapbox/light-v11', // style URL
				center: [<?php print($single_long); ?>, <?php print($single_lat); ?>],
				zoom: 18,
				pitch: 60,
				bearing: processedBearNumber,
				maxZoom: 21,
				locale: {
					'GeolocateControl.FindMyLocation': 'Konumumu göster',
					'NavigationControl.ZoomIn': 'Yakınlaş',
					'NavigationControl.ZoomOut': 'Uzaklaş',
					'NavigationControl.ResetBearing': 'Yönü kuzeye çevir',
				}
			});

			// Add zoom and rotation controls to the map.
			const nav = new mapboxgl.NavigationControl();
			map.addControl(nav, 'bottom-right');

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

			// Check if hashtag exist
			// if (checkhash === 0) {
			// 	//console.log('Hash yok');
			// 	// Local Storage Center
			// 	if (localStorage.getItem('center') === null) {

			// 		localStorage.setItem('center', map.getCenter().toArray());

			// 	} else {

			// 		let center = localStorage.getItem('center');
			// 		let latLng = center.split(','); 
			// 		map.setCenter(latLng);

			// 	}
			// 	// Local Storage Zoom
			// 	if (localStorage.getItem('zoom') === null) {

			// 		localStorage.setItem('zoom', map.getZoom());
			
			// 	} else {

			// 		let center = localStorage.getItem('zoom');
			// 		//let latLng = center.split(","); 
			// 		map.zoomTo(center);

			// 	}

			// }



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
				map.addSource("points_selected", {
					data: data_single,
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
						
				map.addLayer({
					'id': 'selected',
					'type': 'circle',
					'source': 'points_selected',
					'layout': {
						// Make the layer visible by default.
						'visibility': 'visible'
					},
					'paint': {
						'circle-radius': 14,
						'circle-color': 'rgba(0,0,0,1)',
						'circle-stroke-color': 'rgba(255,255,255,1)',
						'circle-stroke-width': 2
					}
				})

				// map.addLayer({
				// 	'id': 'add-3d-buildings',
				// 	'source': 'composite',
				// 	'source-layer': 'building',
				// 	'filter': ['==', 'extrude', 'true'],
				// 	'type': 'fill-extrusion',
				// 	'minzoom': 15,
				// 	'paint': {
				// 		'fill-extrusion-color': '#aaa',

				// 		// Use an 'interpolate' expression to
				// 		// add a smooth transition effect to
				// 		// the buildings as the user zooms in.
				// 		'fill-extrusion-height': [
				// 			'interpolate',
				// 			['linear'],
				// 			['zoom'],
				// 			15,
				// 			0,
				// 			15.05,
				// 			['get', 'height']
				// 		],
				// 		'fill-extrusion-base': [
				// 			'interpolate',
				// 			['linear'],
				// 			['zoom'],
				// 			15,
				// 			0,
				// 			15.05,
				// 			['get', 'min_height']
				// 		],
				// 		'fill-extrusion-opacity': 0.6
				// 	}
				// });

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


			// Correct bounds
			function toPolygon(bounds) {

				if (!bounds instanceof mapboxgl.LngLatBounds) {
					throw new TypeError('Expecting bounds to be mapboxgl.LngLatBounds, got ' + bounds);
				}
				let canvas = map.getCanvas(),
					w = canvas.width,
					h = canvas.height,
					cUL = map.unproject([0, 0]).toArray(),
					cUR = map.unproject([w, 0]).toArray(),
					cLR = map.unproject([w, h]).toArray(),
					cLL = map.unproject([0, h]).toArray();

				let coordinates = [cUL, cUR, cLR, cLL, cUL];
				//console.log("coordinates: " + coordinates);

				let line = turf.lineString([cUL, cUR, cLR, cLL, cUL]);
				let bbox = turf.bbox(line);
				//console.log("bbox: " + bbox);
									
				return bbox;
				// return turf.polygon([coordinates]);

			};

			let index = 0;

			// Create Slices by Boundingbox
			const sliceHandler = e => {

				const lastZoom = 16;

				const currentZoom = map.getZoom();

				if (currentZoom > lastZoom) {

					//Fade Layers
					map.setPaintProperty('pizzas', 'fill-opacity', 0.1);

					let viewPolygon = toPolygon(map.getBounds());
					// console.log("ws: " + viewPolygon[0] + "," + viewPolygon[1]);
					// console.log("en: " + viewPolygon[2] + "," + viewPolygon[3]);
					// console.log('zoom level bigger than 10');

					// FA
					let bounds = `e=${viewPolygon[2]}&w=${viewPolygon[0]}&n=${viewPolygon[3]}&s=${viewPolygon[1]}`;
					let result_fa = siteurl + '/wp-json/fa/images?' + bounds;

					fetch(result_fa)
					.then((response) => {
						return response.json();
					})
					.then((points) => {

						let arcResolution = 180;
						let sweepAngle = 60;
						let slices = [];
						
						points.features.forEach(function (pt) {

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

									// add dotted line (skip if too small)
									// if (
									// m === 60 &&
									// a === Math.floor(arcResolution / 2) &&
									// dis > 10
									// ) {
									// slices.push(
									// 	turf.lineString([pt.geometry.coordinates, destination], {
									// 	cat: cat,
									// 	})
									// );
									// }
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

								//add time marker for slice (skip if too small)
								// if (dis > 10) {
								// 	let marker = turf.point(sliceShape[5], {
								// 	time: "Untitled-3-0" + m / 15,
								// 	angle: bearing,
								// 	dis: dis,
								// 	cat: cat,
								// 	});
								// 	slices.push(marker);
								// }
							}
							
						});

						slices = turf.featureCollection(slices);

						console.log(points);
						console.log(slices);

						map.getSource('slices').setData(slices);

					});
					
				} else {
					//Fade Layers
					map.setPaintProperty('pizzas', 'fill-opacity', 0);
				}

			}
			//map.on('load', sliceHandler);
			map.on('style.load', sliceHandler);
			map.on('moveend', sliceHandler);


		</script>

	<?php } ?>



    <div class="col p-0 m-0 image-content">

		<!-- <span class="text-light" style="position:fixed;top:32;right:0;z-index:9999;float:right;margin:10px 20px 0 0">
            <a id="zoom-in" href="#zoom-in" style="display: inline-block; position: relative; pointer-events: auto;" title="Zoom in">Zoom In</a> 
            <a id="zoom-out" href="#zoom-out" style="display: inline-block; position: relative; pointer-events: auto;" title="Zoom out">Zoom Out</a>
            <a id="home" href="#home" style="display: inline-block; position: relative; pointer-events: auto;" title="Go home">Home</a> 
            <a id="full-page" href="#full-page" style="display: inline-block; position: relative; pointer-events: auto;" title="Toggle full page">Full Page</a> 
        </span> -->

		<div class="figure-thumb bg-primary openseadragon"  id="openseadragon2"></div>

		<img id="single-image" class="d-none" src="<?php echo get_the_post_thumbnail_url( $post->ID, 'full' ); ?>">

        <?php
			if ( ! is_page_template( 'page-templates/no-title.php' ) ) {
				the_title(
					'<header class="entry-header"><h1 class="p-3 entry-title">',
					'</h1></header><!-- .entry-header -->'
				);
			}
		?>

        <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

            <ul class="p-3 mb-3 list-unstyled small">

				<li><?php echo $date_view; ?></li>
				<li><?php echo get_the_term_list( $post->ID, 'image_creator', '', ', ' ); ?></li>

            </ul>

            <ul class="p-3 mb-3 list-unstyled small">
                <li><?php echo get_the_term_list( $post->ID, 'image_type', '<b>Tür:</b> ', ', ' ); ?></li>
                <li><?php echo get_the_term_list( $post->ID, 'image_subject', '<b>Etiket:</b> ', ', ' ); ?></li>
				<li>
					<?php echo get_the_term_list( $post->ID, 'image_source', '<b>Kaynak:</b> ', ', ' ); ?>
					<?php if( empty( $permalink ) ) : ?> <?php else: ?> → <a href="<?php echo $permalink; ?>" target="_blank"><?php if( empty( $id ) ) : ?>Bağlantı<?php else: ?><?php echo $id; ?><?php endif; ?></a><?php endif; ?>
				</li> 
                <li>
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
							echo '<b>Bölge:</b> ';
							echo  $terms;
						}
					?>
                </li>
            </ul>

            <div class="entry-content p-3">
                <?php
					the_content();
				?>

				<div class="card-columns card-columns-wide ke-gallery pt-3" id="mediaID"></div>


            </div>

			<div class="entry-meta py-3 mb-3 px-3 small border-top border-dark">

				<div class="p-0">
					<b>Editör </b>

					<span class="no-translate">
						<?php 
							if ( function_exists( 'coauthors_posts_links' ) ) {
								coauthors_posts_links();
							} else {
								the_author_posts_link();
							} 
						?>
					</span>

					<b>Son güncelleme </b>

					<span class="no-translate pb-3">
						<a href="<?php fa_modified_author_posts_url(); ?>"><?php the_modified_author();?></a> -
						<?php the_modified_time( 'j F Y' ); ?>
					</span>

				</div>

				<div class="pt-3">
					<b>FA No: </b>
					<span id="ke_ID"><?php echo get_the_ID() ?></span>
					<br>
					<b>Alıntı: </b>
					<span id="ke_citation">“<?php the_title(); ?>”, Foto Atlas, <?php the_modified_time( 'j F Y' ); ?>, <a href="<?php echo get_site_url(); ?>/i/?p=<?php echo get_the_ID() ?>"><?php echo get_site_url(); ?>/i/?p=<?php echo get_the_ID() ?></a>.</span>
				</div>

				<div class="pt-5">
					<a href="mailto:<?php echo get_bloginfo( 'admin_email' ); ?>?subject=İçerik Bildirimi: <?php the_title(); ?>&body=<?php the_title(); ?> - <?php echo get_permalink(); ?>"
						target="_blank">⚑ Hata bildir veya katkıda bulun</a>
					<div class="float-end"><b> <?php edit_post_link( __('Düzenle'), '', '', 0, 'iframe-popup' ); ?></b>
					</div>
				</div>
				
			</div>

			<?php // if( current_user_can('editor') || current_user_can('administrator') ) {  ?>
				<!-- <div class="comments px-3 border-top border-dark mt-5 pt-3">
					<?php
						// If comments are open or we have at least one comment, load up the comment template.
						//if ( comments_open() || get_comments_number() ) : comments_template(); 
						//endif;
					?>
				</div> -->
			<?php // } ?>

            <footer class="entry-footer">

                <?php //understrap_edit_post_link(); ?>

            </footer>

        </article>

    </div>

    <div class="d-none" id="imageTo"></div>

</div>

<script>

	<?php if( current_user_can('editor') || current_user_can('administrator') ) {  ?>
		readonly = false;
	<?php } else { ?>
		readonly = true;
	<?php } ?>

	//// Value sorunu
	// https://github.com/performant-software/recogito-semantic-tags/blob/f892dc79abdf7a63f631a3a1281623898539b5e7/src/index.jsx#L22


	window.onload = function() {

		fetch(`<?php echo $site_url; ?>/wp-json/fa/image/<?php the_ID(); ?>`)
		.then(response => response.json()).then(imageDetails => {

			const image = document.getElementById('single-image');

			featuredImage = imageDetails.featured_media.full;

			let viewer = OpenSeadragon({
				id: "openseadragon2",
				prefixUrl:     "https://cdn.jsdelivr.net/gh/Benomrans/openseadragon-icons@main/images/",
				tileSources: {
					type: "image", 
					url: `${featuredImage}`
				},
				showZoomControl: false,
				showHomeControl: false,
				showFullPageControl: false,
			});


			const widgetConfig = {
				// List of data sources, strings for built-in connectors.
				// Could support functions in the future, for more
				// customization
				dataSources: [
					'Wikidata',
					// { source: 'Wikidata', name: 'WD People', filter: 'Q5' } // "human"
					// { source: 'Wikidata', name: 'WD Works', filter: 'Q7725634' }, // "literary works"
				],
				language: 'auto', // Search language (default 'en', 'auto' uses browser locale)
				availableLanguages: [ 'tr', 'en', 'de' ],
				limit: 20,      // Search result page length (default 20)
			};

			const config = {
				image,
				locale: 'auto',

				//the configuration below will give you an editor with just a comment widget, but no tag widget.
				widgets: [
					'COMMENT',
					{ 	
						widget: 'TAG',    
						vocabulary: [ 
							{ label: 'Place', uri: 'http://www.example.com/ontology/place' },
							{ label: 'Person', uri: 'http://www.example.com/ontology/person' }, 
							{ label: 'Event', uri: 'http://www.example.com/ontology/event' }
						]  
					},
					// recogito.SemanticTags(widgetConfig)
					// recogito.SemanticTags({
					// 	dataSources: [{ source: MyDataSource }]
					// })
				],
				
				//crosshair:true
				//disableEditor: true
				readOnly: readonly
			};

			// Initialize the Annotorious plugin
			let anno = OpenSeadragon.Annotorious(viewer, config);

			// Firebase will auto-generate this config for you when you 
			// create your app. Just paste your own settings in here.
			const firebaseConfig = {
				apiKey: "AIzaSyDRDPlUkb3r6c7TsBwDlRXxYc3VdzOQndA",
				authDomain: "fotoatlas-711d1.firebaseapp.com",
				projectId: "fotoatlas-711d1",
				storageBucket: "fotoatlas-711d1.appspot.com",
				messagingSenderId: "48476899314",
				appId: "1:48476899314:web:c8f2956d48a2481a254d6b"
			};

			const settings = {
				annotationTarget: image.src, // mandatory
				collectionName: 'annotations', // optional (default = 'annotations')
			}

			recogito.FirebaseStorage(anno, firebaseConfig, settings);

			anno.on('clickAnnotation', function(annotation, element) {
				console.log(annotation.id);
				const annotations = anno.getAnnotations();
				console.log(annotations);
			});








			
			// Attached Media Items
			if (imageDetails.featured_media.large && imageDetails.featured_media.large != false) {
				fetch(`<?php echo $site_url; ?>/wp-json/wp/v2/media?parent=<?php the_ID(); ?>&_fields=title,source_url,caption,media_creator,media_date,media_details,media_source&per_page=20`)
					.then(response => response.json()).then(data => {

						let imageMediaList = data.map(x => `
					
							<figure class="card figure bg-primary" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">
								<a itemprop="contentUrl" 
								data-pswp-width="${x.media_details.width}" 
								data-pswp-height="${x.media_details.height}" 
								href="${x.source_url}" 
								title="${x.title.rendered}">
									<img class="figure-img img-fluid" 
									onerror="this.style.display='none'" 
									itemprop="thumbnail" 
									src="${x.media_details.sizes.medium.source_url}" 
									alt="${x.title.rendered}">
								</a>
								<figcaption class="figure-caption-single d-none" 
								itemprop="caption description">
									${x.caption.rendered} 
									${x.media_creator == 0 ? '' : ' • ' + x.media_creator } 
									${x.media_date == 0 ? '' : ' • ' + x.media_date }
									${x.media_source == 0 ? '' : '<a target="_blank" href="' + x.media_source + '"> • Kaynak</a>' } 
								</figcaption>
							</figure>

						`).join('');

						let imageMediaListResult = imageMediaList.replaceAll("<p>", "").replaceAll("</p>", "");

						let getElementMedia = document.getElementById('mediaID');
						getElementMedia.innerHTML = imageMediaListResult;

					});
			} else {
				console.log('media yok');
			}






			
		});

	}




</script>



<script type="text/javascript">
    
    let lightbox_single_modal = new PhotoSwipeLightbox({
        gallery: '.ke-gallery',
        children: 'figure',
        // dynamic import is not supported in UMD version
        pswpModule: PhotoSwipe 
    });

    lightbox_single_modal.on('uiRegister', function() {
        
        lightbox_single_modal.pswp.ui.registerElement({
            name: 'custom-caption2',
            order: 9,
            isButton: false,
            appendTo: 'root',
            html: 'Caption text',
            onInit: (el, pswp) => {
                lightbox_single_modal.pswp.on('change', () => {
                    const currSlideElement = lightbox_single_modal.pswp.currSlide.data.element;
                    let captionHTML = '';
                    if (currSlideElement) {
                    const hiddenCaption = currSlideElement.querySelector('.figure-caption-single');
                    if (hiddenCaption) {
                        // get caption from element with class hidden-caption-content
                        captionHTML = hiddenCaption.innerHTML;
                    } else {
                        // get caption from alt attribute
                        captionHTML = currSlideElement.querySelector('img').getAttribute('alt');
                    }
                    }
                    el.innerHTML = captionHTML || '';
                });
            }
        });

    });

    lightbox_single_modal.init();

</script>
