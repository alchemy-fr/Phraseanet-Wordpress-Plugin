<?php
/*
Plugin Name: WP Phraseanet
Description: Add possibility to insert content from a phraseanet database into the Wysiwyg editor
Version: 0.1.2
Author: Nicolas Derambure (Labomedia) and the Alchemy team
Author URI: https://github.com/alchemy-fr/Phraseanet-Wordpress-Plugin
Licence: GNU General Public License v3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/


/**
 * Necessary included Files
 */
require_once( plugin_dir_path( __FILE__ ) . 'includes/base.php' );
require( plugin_dir_path( __FILE__ ) . "vendor/autoload.php" );


/**
 * Plugin activation
 */
function wppsn_activate() {

	// Set default plugin options
	wppsn_set_default_options();

}

register_activation_hook( __FILE__, 'wppsn_activate' );


/**
 * Plugin Setup
 */
function wppsn_setup() {

	// Only if in ADMIN area
	if ( is_admin() ) {

		// Translations
		load_plugin_textdomain( 'wp-phraseanet', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// Functions
		require_once( WPPSN_PLUGIN_INCLUDES_PATH . 'functions-admin.php' );

	}
	// Only in FRONTEND area
	else {

		// Functions
		require_once( WPPSN_PLUGIN_INCLUDES_PATH . 'functions-frontend.php' );

	}

}

add_action( 'plugins_loaded', 'wppsn_setup' );


/**
 * Set default plugin options
 */
function wppsn_set_default_options() {

	$default_options = array(
		'client_base_url' 	=> '',
		'client_id'			=> '',
		'client_secret'		=> '',
		'client_token'		=> ''
	);

	add_option( 'wppsn_options', $default_options );

}
