<?php

ini_set('max_execution_time', 300);
ini_set('memory_limit', '2048M'); // or you could use 1G

/**
 * Wp Query to geojson 
 */

// return in JSON format
// header( 'Content-type: application/json; charset=utf-8' );

// Needed if you want to manually browse to this place for testing
// define('WP_USE_THEMES', false); // Needed if you want to manually browse to this place for testing
// https://stackoverflow.com/questions/36349568/make-wordpress-wp-api-faster-by-not-loading-theme-and-plugins
define('SHORTINIT', false);
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

// WP_Query arguments
$args = array (
  'post_type' => 'place',
  'post_status' => 'draft',
  'posts_per_page'  => 30000,

  'cache_results'  => false,
  'no_found_rows' => true, // counts posts, remove if pagination required
	// 'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
  // 'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
);

// Exclude terms
$args['tax_query'] = array(
  array(
      'taxonomy' => 'place_group',
      'terms' => array( 'dogal-varlik' ),
      'field' => 'slug',
      'operator' => 'NOT IN'
  ),
);

// The Query
$loop = new WP_Query( $args );

// Build GeoJSON feature collection array
$geojson = array(
  'type'      => 'FeatureCollection',
  'features'  => array()
);	

//the loop
while ( $loop->have_posts() ) : $loop->the_post();
  
  // Koordinatları ACF den al, sayı formatına dönüştür
  $lng = floatval(get_post_meta( $post->ID, 'geo_longitude', true ));
  $lat = floatval(get_post_meta( $post->ID, 'geo_latitude', true ));
  $cat = strip_tags( get_the_term_list( $post->ID, 'place_group', '', ', ' ) );

  $feature = array(
    'type' => 'Feature',
    // 'id' => get_the_ID(),
    'geometry' => array(
      'type' => 'Point',
      //'coordinates' => [substr($lng, 0, 10),substr($lat, 0, 10)]
      'coordinates' => array($lng, $lat)
    ),
    'properties' => array(
      'id' => get_the_ID(),
      'name' => get_the_title(),
      'cat' => $cat
      )
  );
  // $geojson içindeki features'in içine göm
  array_push($geojson['features'], $feature);

endwhile;

wp_reset_query();

// output
//echo json_encode( $locations );
//echo json_encode( $geojson );

// Create json file
// https://www.php.net/manual/en/function.file-put-contents.php
// $json = json_encode($geojson);
// file_put_contents('geo.json', $json);

$json = json_encode($geojson);
$r=file_put_contents('geo_draft.json', $json);

if ($r !==false){
  echo "[geo_draft.json] dosyasına $r byte yazıldı<br>\n";
}
else{
  echo "hata oluştu dosyaya yazılamadı<br>\n";
}

?>