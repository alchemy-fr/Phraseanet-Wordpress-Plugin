<?php
	/*
	Plugin Name: WP Phraseanet 2
	Description: Add possibility to insert content from a phraseanet database into the editor - compatible with gutenberg and classic editor
	Version: 2.0.0
	Author: Nicolas Derambure (Labomedia) and the Alchemy team
	Author URI: https://github.com/alchemy-fr/Phraseanet-Wordpress-Plugin
	Licence: GNU General Public License v3 or later
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
	*/

// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	/**
	 * Block Initializer.
	 */
	require_once plugin_dir_path( __FILE__ ) . 'src/init.php';

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

		} // Only in FRONTEND area
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
			'client_base_url' => '',
			'client_id'       => '',
			'client_secret'   => '',
			'client_token'    => ''
		);

		add_option( 'wppsn_options', $default_options );

	}


	/**
	 * In plugin list, add links to settings and help pages
	 *
	 * @param array $links Original links array
	 * @param string $file Current plugin file loading
	 *
	 * @return array            Array of links
	 */
	function wppsn_add_plugin_settings_link( $links, $file ) {
		if ( $file == plugin_basename( plugin_basename( __FILE__ ) ) ) {
			$links[] = '<a href="' . admin_url() . 'admin.php?page=wppsn_settings_page">' . __( 'Settings' ) . '</a>';
			$links[] = '<a href="' . admin_url() . 'admin.php?page=wppsn_help_credits_page">' . __( 'Help' ) . '</a>';
		}

		return $links;
	}

	add_filter( 'plugin_action_links', 'wppsn_add_plugin_settings_link', 10, 2 );

