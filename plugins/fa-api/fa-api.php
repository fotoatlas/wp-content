<?php

/**
 * Plugin Name: FA images API
 * Plugin URI: https://fotoatlas.net
 * Description: Kültür Envanteri Custom Endpoint. Geomashup Plugin Requied https://fotoatlas.net/wp-json/fa/images
 * Version: 1.1
 * Author: A. Erdem Şentürk
 * Author URI: https://fotoatlas.net
 */

//// Get Posts of inside of the Radius
// Örnek: https://fotoatlas.local/wp-json/fa/images_radius?lat=36.78435243683097&lon=28.154621072674757&r=10

function distance($lat1, $lon1, $lat2, $lon2, $unit) {
	if (($lat1 == $lat2) && ($lon1 == $lon2)) {
	  return 0;
	}
	else {
	  $theta = $lon1 - $lon2;
	  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	  $dist = acos($dist);
	  $dist = rad2deg($dist);
	  $miles = $dist * 60 * 1.1515;
	  $unit = strtoupper($unit);
  
	  if ($unit == "K") {
		return ($miles * 1.609344);
	  } else if ($unit == "N") {
		return ($miles * 0.8684);
	  } else {
		return $miles;
	  }
	}
}

function fa_images_radius() {

    $str = $_SERVER['QUERY_STRING'];

    // LAT LONG
    parse_str($str, $output);
    $nearlat = $output['lat'];
    $nearlon = $output['lon'];
	$radius = $output['r'];

    //// Radius Query
    $locations = GeoMashupDB::get_object_locations( array(
        'object_name' => 'post',
        'near_lat' => $nearlat,
        'near_lng' => $nearlon, 
		'radius_km' => $radius,
		'sort' => 'distance_km ASC',
        'limit' => 36
    ));

    $geojson = array(
        'type'      => 'FeatureCollection',
        'features'  => array()
    );

    foreach( $locations as $location ) {

		$post = get_post( $location->object_id ); 
		//setup_postdata( $post );

        $lon = floatval(get_post_meta( $post->ID, 'geo_longitude', true ));
        $lat = floatval(get_post_meta( $post->ID, 'geo_latitude', true ));
        //$cat = strip_tags( get_the_term_list( $post->ID, 'image_group', '', ', ' ) );
        $img = get_the_post_thumbnail_url($post->ID,'medium'); 
		$title = get_the_title( $post->ID );

		$distance = distance($nearlat, $nearlon, $lat, $lon, "K");
		$distance_round = round($distance);
		
        $feature = array(
            'type' => 'Feature',
            'geometry' => array(
                'type' => 'Point',
                'coordinates' => [$lon,$lat]
            ),
            'properties' => array(
				'id' => $post->ID,
				'title' => $title,
				//'cat' => $cat,
				'img' => $img,
				'distance' => $distance_round
            )
        );
        // $geojson içindeki features'in içine göm
        array_push($geojson['features'], $feature);
    
    }

	return $geojson;

}

//// Get Posts of inside of the Bounding Box
// https://fotoatlas.local/wp-json/fa/images?e=28.978761817577606&w=28.97071806886771&n=41.01234315145069&s=41.00497834376654

function fa_images_boundingbox() {

    $str = $_SERVER['QUERY_STRING'];

    // // LAT LONG
    parse_str($str, $output);
    $maxlon = $output['e'];
    $minlon = $output['w'];
    $maxlat = $output['n'];
    $minlat = $output['s'];

    //// Bounding Box Query
    $locations = GeoMashupDB::get_object_locations( array(
        'object_name' => 'post',
        'minlat' => $minlat,
        'maxlat' => $maxlat, 
        'minlon' => $minlon, 
        'maxlon' => $maxlon,
        'limit' => 300
    ));

    $geojson = array(
        'type'      => 'FeatureCollection',
        'features'  => array()
    );

    foreach( $locations as $location ) {


		$post = get_post( $location->object_id ); 
		//setup_postdata( $post );

        $lon = floatval(get_post_meta( $post->ID, 'geo_longitude', true ));
        $lat = floatval(get_post_meta( $post->ID, 'geo_latitude', true ));
		$dir = floatval(get_post_meta( $post->ID, 'view_direction', true ));
		$dis = floatval(get_post_meta( $post->ID, 'view_distance', true ));
        $img = get_the_post_thumbnail_url($post->ID,'medium'); 
		$title = get_the_title( $post->ID );

        $feature = array(
            'type' => 'Feature',
            'geometry' => array(
                'type' => 'Point',
                'coordinates' => [$lon,$lat]
            ),
            'properties' => array(
				'id' => $post->ID,
				'title' => $title,
				'dir' => $dir,
				'cat' => 1,
				'dis' => $dis,
				'img' => $img
            )
        );

		// if($status==false){
		// 	unset($feature['properties']['status']);
		// }

        // $geojson içindeki features'in içine göm
        array_push($geojson['features'], $feature);
    
    }

	return $geojson;

}

//// Get last_modified posts
function fa_images_last_modified() {
    
	$args = [
		'numberposts' => 50,
		'post_type' => 'image',
		'orderby'  => 'modified',

		// 'cache_results'  => false,
		// 'no_found_rows' => true, // counts posts, remove if pagination required
		// 'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
		// 'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
	];

	$posts = get_posts($args);

	// Build GeoJSON feature collection array
	$geojson = array(
		'type'      => 'FeatureCollection',
		'features'  => array()
	);
	
	foreach($posts as $post) {

		  // Koordinatları ACF den al, sayı formatına dönüştür
		  
		  $lon = floatval(get_post_meta( $post->ID, 'geo_longitude', true ));
		  $lat = floatval(get_post_meta( $post->ID, 'geo_latitude', true ));
		  //$cat = strip_tags( get_the_term_list( $post->ID, 'image_group', '', ', ' ) );
		  $img = get_the_post_thumbnail_url($post->ID, 'medium');

		  //https://developer.wordpress.org/reference/functions/get_post_field/#user-contributed-notes
		  //https://wordpress.stackexchange.com/questions/238105/get-last-revision-author-author-link-and-date
		  		  
		  //$author_id = get_post_field('post_author', $post->ID);

		  $author_id = get_post_meta($post->ID, '_edit_last', true );
		  $modified_author = get_the_author_meta( 'display_name' , $author_id );
		  $modified_date = get_post_field( 'post_modified', $post->ID );

		  // https://www.php.net/manual/en/function.date.php
		  $date = new DateTime($modified_date);
		  //$modified_date_formatted = date_format($date, 'j F Y, g:i a');
		  $modified_date_formatted = date_format($date, 'd.m.y, H:i');

		  
		  $feature = array(
			'type' => 'Feature',
			'geometry' => array(
				'type' => 'Point',
				'coordinates' => [$lon,$lat]
			),
			'properties' => array(
				'id' => $post->ID,
				'title' => $post->post_title,
				//'cat' => $cat,
				'img' => $img,
				'modified_author' => $modified_author,
				'modified_date' => $modified_date_formatted
			  )
		  );
		  // $geojson içindeki features'in içine göm
		  array_push($geojson['features'], $feature);
	}

	return $geojson;
}

//// Get last_added posts
function fa_images_last_added() {
    
	$args = [
		'numberposts' => 36,
		'post_type' => 'image',
	];

	$posts = get_posts($args);

	// Build GeoJSON feature collection array
	$geojson2 = array(
		'type'      => 'FeatureCollection',
		'features'  => array()
	);
	
	foreach($posts as $post) {

		  // Koordinatları ACF den al, sayı formatına dönüştür
		  
		  $lon = floatval(get_post_meta( $post->ID, 'geo_longitude', true ));
		  $lat = floatval(get_post_meta( $post->ID, 'geo_latitude', true ));
		  //$cat = strip_tags( get_the_term_list( $post->ID, 'image_group', '', ', ' ) );
		  $img = get_the_post_thumbnail_url($post->ID, 'medium');

		  //https://developer.wordpress.org/reference/functions/get_post_field/#user-contributed-notes
		  //https://wordpress.stackexchange.com/questions/238105/get-last-revision-author-author-link-and-date
		  		  
		  //$author_id = get_post_field('post_author', $post->ID);

		  $author_id = get_post_meta($post->ID, '_edit_last', true );
		  $modified_author = get_the_author_meta( 'display_name' , $author_id );
		  $modified_date = get_post_field( 'post_modified', $post->ID );

		  // https://www.php.net/manual/en/function.date.php
		  $date = new DateTime($modified_date);
		  //$modified_date_formatted = date_format($date, 'j F Y, g:i a');
		  $modified_date_formatted = date_format($date, 'd.m.y, H:i');

		  
		  $feature = array(
			'type' => 'Feature',
			'geometry' => array(
				'type' => 'Point',
				'coordinates' => [$lon,$lat]
			),
			'properties' => array(
				'id' => $post->ID,
				'title' => $post->post_title,
				//'cat' => $cat,
				'img' => $img,
				'modified_author' => $modified_author,
				'modified_date' => $modified_date_formatted
			  )
		  );
		  // $geojson içindeki features'in içine göm
		  array_push($geojson2['features'], $feature);
	}

	return $geojson2;
}

// Örnek
// https://fotoatlas.net/wp-json/fa/image/1952

add_action('rest_api_init', function() {
    
	register_rest_route('fa', 'last_modified', [
		'methods' => 'GET',
		'callback' => 'fa_images_last_modified',
        'permission_callback' => '__return_true',
	]);

	register_rest_route('fa', 'last_added', [
		'methods' => 'GET',
		'callback' => 'fa_images_last_added',
        'permission_callback' => '__return_true',
	]);

	//// By Bounding Box Query Geo Mashup Plugin gereklidir
	register_rest_route('fa', 'images', [
		'methods' => 'GET',
		'callback' => 'fa_images_boundingbox',
        'permission_callback' => '__return_true',
	]);

	//// By Radius Query Geo Mashup Plugin gereklidir
	register_rest_route('fa', 'images_radius', [
		'methods' => 'GET',
		'callback' => 'fa_images_radius',
		'permission_callback' => '__return_true',
	]);

	//// By ID
	register_rest_route( 'fa', 'image/(?P<id>\d+)', array(
		'methods' => 'GET',
		'callback' => 'fa_post',
        'permission_callback' => '__return_true',
    ) );

	function fa_post( $slug ) {

		$args = [
			'name' => $slug['slug'],
			'post_type' => 'image',
			'include' => array($slug['id']),
		];

		$post = get_posts($args);

		$data['id'] = $post[0]->ID;
		$data['slug'] = $post[0]->post_name;
		$data['title'] = $post[0]->post_title;
		$data['content'] = apply_filters( 'the_content', $post[0]->post_content ); // Rendered Content

		$data['location']['lat'] = get_post_meta( $post[0]->ID,'geo_latitude', true );
		$data['location']['long'] = get_post_meta( $post[0]->ID,'geo_longitude', true );

		$data['featured_media']['thumbnail'] = get_the_post_thumbnail_url($post[0]->ID, 'thumbnail');
		$data['featured_media']['large'] = get_the_post_thumbnail_url($post[0]->ID, 'large');
		$data['featured_media']['full'] = get_the_post_thumbnail_url($post[0]->ID, 'full');

		$data['image_type'] = get_the_term_list($post[0]->ID, 'image_type', '', ', ');
		$data['image_creator'] = get_the_term_list($post[0]->ID, 'image_creator', '', ', ');

		$data['date']['min'] = get_post_meta( $post[0]->ID,'image_date_min', true );


		
		return $data;
	}

});