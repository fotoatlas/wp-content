<?php

/**
 * Plugin Name: FA Geojson API
 * Plugin URI: https://fotoatlas.net
 * Description: Geojson Fetch
 * Version: 1.0
 * Author: A. Erdem Şentürk
 * Author URI: https://birtakimseyler.com
 */

add_action('wp_dashboard_setup', 'fa_geojson_widget');

	function fa_geojson_widget() {
	global $wp_meta_boxes;
	wp_add_dashboard_widget('geojson_widget', 'FA Geojson', 'fa_geojson');

}
 
function fa_geojson() {

	echo '<p>Veritabanındaki tüm noktaları geojson dosyasına çevirir. Düzenli aralıklarla Geojson dosyası yenilenir.</p>';

	$user = wp_get_current_user();
	$allowed_roles = array( 'administrator');
	
	$json_generate = plugins_url( 'fa-geojson/geojson.php' );
	$json_light_generate = plugins_url( 'fa-geojson/geojson_light.php' );

	$json_draft_generate = plugins_url( 'fa-geojson/geojson_draft.php' );


	$test_link = plugins_url( 'fa-geojson/index.html' );
	$json_path = plugin_dir_path(__DIR__) . 'fa-geojson/geo.json';
	$json_path_draft = plugin_dir_path(__DIR__) . 'fa-geojson/geo_draft.json';

	if (file_exists($json_path)) {
		echo "Son Güncelleme: " . date("d F Y @H:i:s.", filemtime($json_path));
		echo '<p><a href="' . $test_link . '" target="_blank">Harita üzerinde görüntüle</a></p>';
	}

	if (file_exists($json_path_draft)) {
		echo "Draft Son Güncelleme: " . date("d F Y @H:i:s.", filemtime($json_path_draft));
		//echo '<p><a href="' . $test_link . '" target="_blank">Harita üzerinde görüntüle</a></p>';
	}

	if ( array_intersect( $allowed_roles, $user->roles ) ) {
		echo '<p><a href="' . $json_generate . '" target="_blank">Geojson dosyasını yenile</a><p>';
		//echo '<p><a href="' . $json_light_generate . '" target="_blank">Geojson Light dosyasını yenile</a><p>';
		//echo '<p><a href="' . $json_draft_generate . '" target="_blank">Geojson Draft dosyasını yenile</a><p>';
	}

}