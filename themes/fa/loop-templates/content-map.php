<?php
/**
 * Partial template for content in page.php
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// $site_url = get_site_url();
// $themepath = get_stylesheet_directory_uri();

$mapbox_token = 'pk.eyJ1IjoiYmlydGFraW1zZXlsZXIiLCJhIjoiY2tpdDZtcDhtMm4xdTJ2cWpobWJxMjBzZSJ9.wWKNyeoOUADTIPjL0vUrNg';

$pluginpath = plugins_url();

?>








<link href="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>


<div class="map" id="fa_map">
	<div id="sub-menu-right" class="col-4 mt-5 me-3 pt-2 text-dark fixed-top-right">
		<button title="Katmanlar" style="position:relative;z-index:9999;" class="btn btn-sm rounded-pill btn-primary float-end mb-3 ms-2" data-bs-toggle="modal" data-bs-target="#button_options"><svg height="16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="layer-group" class="svg-inline--fa fa-layer-group fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M12.41 148.02l232.94 105.67c6.8 3.09 14.49 3.09 21.29 0l232.94-105.67c16.55-7.51 16.55-32.52 0-40.03L266.65 2.31a25.607 25.607 0 0 0-21.29 0L12.41 107.98c-16.55 7.51-16.55 32.53 0 40.04zm487.18 88.28l-58.09-26.33-161.64 73.27c-7.56 3.43-15.59 5.17-23.86 5.17s-16.29-1.74-23.86-5.17L70.51 209.97l-58.1 26.33c-16.55 7.5-16.55 32.5 0 40l232.94 105.59c6.8 3.08 14.49 3.08 21.29 0L499.59 276.3c16.55-7.5 16.55-32.5 0-40zm0 127.8l-57.87-26.23-161.86 73.37c-7.56 3.43-15.59 5.17-23.86 5.17s-16.29-1.74-23.86-5.17L70.29 337.87 12.41 364.1c-16.55 7.5-16.55 32.5 0 40l232.94 105.59c6.8 3.08 14.49 3.08 21.29 0L499.59 404.1c16.55-7.5 16.55-32.5 0-40z"></path></svg></button>
	</div>
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

<script>

    // Check Hash at first
    if(window.location.hash) {
        checkhash = 1; 
        //console.log('hash exist ' + checkhash);
    } else {
        checkhash = 0; 
        //console.log('hash does not exist '  + checkhash);
    }

	const pluginpath = '<?php echo $pluginpath; ?>';
	const siteurl = '<?php echo get_site_url(); ?>';
	
	data = pluginpath + '/fa-geojson/geo.json';

	let slices = [];

	mapboxgl.accessToken = '<?php print($mapbox_token); ?>';
	
	const map = new mapboxgl.Map({
		container: 'fa_map',
		hash: true,
		style: 'mapbox://styles/mapbox/light-v11', // style URL
		center: [35.28, 38.94],
		zoom: 5,
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

	// Check if hashtag exist
	if (checkhash === 0) {
		//console.log('Hash yok');
		// Local Storage Center
		if (localStorage.getItem('center') === null) {

			localStorage.setItem('center', map.getCenter().toArray());

		} else {

			let center = localStorage.getItem('center');
			let latLng = center.split(','); 
			map.setCenter(latLng);

		}
		// Local Storage Zoom
		if (localStorage.getItem('zoom') === null) {

			localStorage.setItem('zoom', map.getZoom());
	
		} else {

			let center = localStorage.getItem('zoom');
			//let latLng = center.split(","); 
			map.zoomTo(center);

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
			
		//Slices
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

		//Points
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



	// fetch('https://fotoatlas.local/wp-content/plugins/fa-geojson/geo.json')
	// .then(response => response.json())
	// .then(data => {

	// 	console.log('test');



	// 	data.features.forEach(marker => {
	// 		const markerSize = "12", markerRotation = marker.properties["dis"], markerFill = "red";
	// 		const markerData = "<svg width='" + markerSize + "' height='" + markerSize + "' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg' version='1.1'><polygon fill='" + markerFill + "' stroke='black' stroke-width='1' points='20,90 50,10 80,90 50,70' transform='rotate(" + markerRotation + " 50 50)'/></svg>"; // arrowhead

	// 		new mapboxgl.Marker(markerImage(markerData, markerSize), {offset: [-markerSize / 2, -markerSize / 2], rotationAlignment: 'map', pitchAlignment: 'map'})
	// 			.setLngLat(marker.geometry.coordinates)
	// 			.addTo(map);

	// 	});

	// 	function markerImage(markerData, markerSize) {
	// 		const el = document.createElement('div');
	// 		el.className = 'marker';
	// 		el.style.backgroundImage = "url(data:image/svg+xml;base64," + btoa(markerData) + ')';
	// 		el.style.width = markerSize + 'px';
	// 		el.style.height = markerSize + 'px';
	// 		return el;
	// 	}
		
	// });








	
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
		console.log("coordinates: " + coordinates);

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

	map.on('style.load', sliceHandler);
	map.on('moveend', sliceHandler);







	// // Listen for styledata event (layer switch)
	// map.on('styledata', function() {
	// 	// Check the current active layers or perform any other actions
	// 	// This is where you can add logic to handle layer switches

	// 	// For example, change circle color to red after switching a layer

	// 	const currentStyle = map.getStyle();

	// 	// Function to change circle color
	// 	function changeCircleColor(newColor) {
	// 		map.setPaintProperty('points', 'circle-color', newColor);
	// 	}

	// 	// Check if the loaded style has a specific ID ('your-specific-style-id')
	// 	if (currentStyle && currentStyle.id === 'google_satellite') {
	// 		// Perform actions specific to the loaded style
	// 		changeCircleColor('red');
	// 	} else {
	// 		// Perform other actions or handle the absence of the specific style
	// 		changeCircleColor('black');
	// 	}

		
	// });



	const specificStyleIds = ['google_satellite', 'esri_satellite', 'mapbox_satellite'];

	// Function to perform actions when a specific style is loaded
	function onStyleLoad() {

		let currentStyle = localStorage.getItem('layer_id');

		// Check if the loaded style has a specific ID ('your-specific-style-id')
		if (specificStyleIds.includes(currentStyle) ) {
			// Perform actions specific to the loaded style
			console.log('Specific style loaded:', currentStyle);
			map.setPaintProperty('points', 'circle-color', 'red');
		} else {
			// Perform other actions or handle the absence of the specific style
			console.log('Different style loaded or no style ID found.');
			map.setPaintProperty('points', 'circle-color', 'black');

		}
	}

	// Listen for the 'style.load' event
	map.on('style.load', onStyleLoad);


</script>