<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.brewery.org
 * @since             1.0.0
 * @package           Brewery
 *
 * @wordpress-plugin
 * Plugin Name:       Brewery
 * Plugin URI:        http://www.brewery.org
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Leonard Gonzales
 * Author URI:        http://www.brewery.org
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       brewery
 * Domain Path:       /languages
 */

 
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'BREWERY_VERSION', '1.0.0' );

function activate_brewery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-brewery-activator.php';
	Brewery_Activator::activate_brewery_post();
	Brewery_Activator::register_taxonomy();
	
	$count_brewery = wp_count_posts('brewery');
	$total_brewery = $count_brewery->publish;

	if($total_brewery == 0) {
		Brewery_Activator::import_breweries();
	}
}

function deactivate_brewery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-brewery-deactivator.php';
	Brewery_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_brewery' );
register_deactivation_hook( __FILE__, 'deactivate_brewery' );
add_action( 'init', 'activate_brewery');

require plugin_dir_path( __FILE__ ) . 'includes/class-brewery.php';

function get_brewery($atts) {    
	global $post; 
	
	extract( shortcode_atts( array(
		'meta' => ''
	), $atts ) );


	$value = get_post_meta($post->ID, $meta, true); 
	return $value; 
} 

add_shortcode('brewery', 'get_brewery');

function run_brewery() {

	$plugin = new Brewery();
	$plugin->run();

}

run_brewery();
