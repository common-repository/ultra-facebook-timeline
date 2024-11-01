<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
/*
Plugin Name: Ultra Facebook Timeline
Plugin URI: http://www.photontechs.com
Description: It allows to show facebook timeline news feed.
Version: 1.0
Author: daniyalahmedk
Author URI: http://www.photontechs.com
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
/*
Copyright 2015  daniyalahmedk  (email : support@photontechs.com)
*/

define('aft_VERSION', '3.0');
define('aft_FILE', basename(__FILE__));
define('aft_NAME', str_replace('.php', '', aft_FILE));
define('aft_PATH', plugin_dir_path(__FILE__));
define('aft_URL', plugin_dir_url(__FILE__));
define('aft_HOMEPAGE', 'http://www.photontechs.com');

// GET FEATURED IMAGE
function ST4_get_featured_image($post_ID) {
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);
    if ($post_thumbnail_id) {
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'featured_preview');
        return $post_thumbnail_img[0];
    }
}
// ADD NEW COLUMN
function ST4_columns_head($defaults) {
    $defaults['featured_image'] = 'Featured Image';
    return $defaults;
}

// SHOW THE FEATURED IMAGE
function ST4_columns_content($column_name, $post_ID) {
    if ($column_name == 'featured_image') {
        $post_featured_image = ST4_get_featured_image($post_ID);
        if ($post_featured_image) {
            echo '<img src="' . $post_featured_image . '" />';
        }
    }
}

add_filter('manage_facebook-timeline_columns', 'ST4_columns_head');
add_action('manage_facebook-timeline_custom_column', 'ST4_columns_content', 10, 2);

function aft_custom_wp_admin_style() {
        wp_register_style( 'aft_wp_admin_css',  plugin_dir_url(__FILE__) . 'css/admin_style.css', false, rand() ); // NEBS
          wp_enqueue_style( 'aft_wp_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'aft_custom_wp_admin_style' );

function aft_custom_wp_admin_scripts() {
        wp_register_script( 'aft_wp_admin_script',  plugin_dir_url(__FILE__) . 'js/aft_admin_script.js', false, rand() ); // NEBS
          wp_enqueue_script( 'aft_wp_admin_script' );
}
add_action( 'admin_enqueue_scripts', 'aft_custom_wp_admin_scripts' );


add_action( 'admin_enqueue_scripts', 'aft_color_picker' );
function aft_color_picker( $hook ) {
if( is_admin() ) {

		// Add the color picker css file
		wp_enqueue_style( 'wp-color-picker' );

		// Include our custom jQuery file with WordPress Color Picker dependency
		//wp_enqueue_script( 'custom-script-handle', plugins_url( 'custom-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
}
function aft_load_admin_things() {
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
}

add_action( 'admin_enqueue_scripts', 'aft_load_admin_things' );

if(!class_exists('ultra_facebook_timeline'))
{
	class ultra_facebook_timeline
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// Initialize Settings
			require_once(sprintf("%s/aft-settings.php", dirname(__FILE__)));
			// Including Shortcode genrator
			require_once(sprintf("%s/inc/shortcode-gen.php", dirname(__FILE__)));
			$ultra_facebook_timeline_settings = new ultra_facebook_timeline_settings();

			$plugin = plugin_basename(__FILE__);
			add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));

		} // END public function __construct

		/**
		 * Activate the plugin
		 */
		public static function activate()
		{
			// Do nothing
		} // END public static function activate

		/**
		 * Deactivate the plugin
		 */
		public static function deactivate()
		{
			// Do nothing
		} // END public static function deactivate

		// Add the settings link to the plugins page
		function plugin_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=ultra_facebook_timeline">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}
	} // END class ultra_facebook_timeline
} // END if(!class_exists('ultra_facebook_timeline'))

if(class_exists('ultra_facebook_timeline'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('ultra_facebook_timeline', 'activate'));
	register_deactivation_hook(__FILE__, array('ultra_facebook_timeline', 'deactivate'));

	// instantiate the plugin class
	$ultra_facebook_timeline = new ultra_facebook_timeline();
}
// Color picker
add_action( 'admin_enqueue_scripts', 'aft_add_color_picker' );
function aft_add_color_picker( $hook ) {

    if( is_admin() ) {

        // Add the color picker css file
        wp_enqueue_style( 'wp-color-picker' );

        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( 'custom-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
    }
}
