<?php

/**
 * Plugin Name:       Movie Plugin
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handles movie presentations.
 * Version:           0.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Jan-Niclas Gladbach
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       movie-plugin
 * Domain Path:       /languages
 */
foreach (glob(plugin_dir_path(__FILE__) . "/admin/*.php") as $filename) {
	include($filename);
}
// foreach (glob(plugin_dir_path(__FILE__) . "/blocks/*", GLOB_ONLYDIR) as $foldername) {
// 	foreach (glob($foldername . "/*.php") as $filename) {
// 		include $filename;
// 	}
// }
foreach (glob(plugin_dir_path(__FILE__) . "/crud/*.php") as $filename) {
	include $filename;
}
foreach (glob(plugin_dir_path(__FILE__) . "/shortcodes/*.php") as $filename) {
	include $filename;
}

// function wpbootstrap_enqueue_styles() {
// 	//wp_enqueue_style( 'bootstrap', plugin_dir_path(__FILE__) . 'style/bootstrap.min.css' );
// 	wp_enqueue_style( 'bootstrap', get_stylesheet_uri());	
// }
// add_action('wp_enqueue_scripts', 'wpbootstrap_enqueue_styles');

// add_action( 'wp_enqueue_scripts', 'prefix_add_my_stylesheet' );

// /**
//  * Enqueue plugin style-file
//  */
// function prefix_add_my_stylesheet() {
//     // Respects SSL, Style.css is relative to the current file
//     wp_register_style('bootstrap', plugin_dir_path(__FILE__) . 'assets/bootstrap.min.css');
// 	wp_enqueue_style( 'bootstrap');
// }

function movie_on_activation()
{
	global $wpdb;

	echo "create dbs";

	$movie_table_name = $wpdb->prefix . "movie_entries";
	$presentations_table_name = $wpdb->prefix . "presentations";

	# check https://codex.wordpress.org/Creating_Tables_with_Plugins#Creating_or_Updating_the_Table

	$charset_collate = $wpdb->get_charset_collate();

	$sql =
		<<<SQL
		CREATE TABLE $movie_table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			title tinytext NOT NULL,
			image tinytext,
			text text NOT NULL,
			url varchar(255) DEFAULT '' NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;

		CREATE TABLE $presentations_table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			presentation datetime NOT NULL,
			movie mediumint(9) NOT NULL,
			PRIMARY KEY  (id),
			FOREIGN KEY  (movie) REFERENCES $movie_table_name(id) ON DELETE CASCADE
		) $charset_collate;
SQL;

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}

register_activation_hook(__FILE__, 'movie_on_activation');
