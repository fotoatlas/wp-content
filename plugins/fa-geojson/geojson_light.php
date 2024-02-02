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

// The Geojson
$geojson = array(
  'type'      => 'FeatureCollection',
  'features'  => array()
);	

// WP_Query ALL
$all_places = array (
  'post_type' => 'place',
  'post_status' => 'publish',
  'posts_per_page'  => 30000,

  'cache_results'  => false,
  'no_found_rows' => true, // counts posts, remove if pagination required
	// 'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
  // 'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
);

// Exclude terms
$all_places['tax_query'] = array(
  array(
      'taxonomy' => 'place_group',
      'terms' => array( 'dogal-varlik','yer-adlari'),
      'field' => 'slug',
      'operator' => 'NOT IN'
  ),
);


$loop_all_places = new WP_Query( $all_places );
//the loop
while ( $loop_all_places->have_posts() ) : $loop_all_places->the_post();

  $lng = floatval(get_post_meta( $post->ID, 'geo_longitude', true ));
  $lat = floatval(get_post_meta( $post->ID, 'geo_latitude', true ));

  $feature = array(
    'type' => 'Feature',
    'geometry' => array(
      'type' => 'Point',
      'coordinates' => array($lng, $lat)
    ),
    'properties' => array(
      'id' => get_the_ID(),
      // 'name' => get_the_title(),
      // 'cat' => $cat
    )
  );
  array_push($geojson['features'], $feature);

endwhile;

wp_reset_postdata();

$json = json_encode($geojson);
$r=file_put_contents('geo_light.json', $json);

if ($r !==false){
  echo "[geo_light.json] dosyasına $r byte yazıldı<br>\n";
}
else{
  echo "hata oluştu dosyaya yazılamadı<br>\n";
}

?>