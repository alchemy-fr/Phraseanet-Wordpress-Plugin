<?php
/**
 * Base Variables
 */

/* Plugin Name */

$wppsn_plugin_dir_array = explode( '/', str_replace( WP_PLUGIN_DIR . '/', '', dirname( __FILE__ ) ) );
define( 'WPPSN_PLUGIN_FOLDER_NAME', $wppsn_plugin_dir_array[0] );
define( 'WPPSN_PLUGIN_MENU_NAME', 'WP Phraseanet' );


/* PATHS */

define( 'WPPSN_PLUGIN_PATH', WP_PLUGIN_DIR . '/' . WPPSN_PLUGIN_FOLDER_NAME . '/' );
define( 'WPPSN_PLUGIN_INCLUDES_PATH', WPPSN_PLUGIN_PATH . 'includes/' );
define( 'WPPSN_PLUGIN_TEMPLATES_PATH', WPPSN_PLUGIN_INCLUDES_PATH . 'templates/' );
define( 'WPPSN_PLUGIN_VENDOR_PATH', WPPSN_PLUGIN_PATH . 'vendor/' );


/* URLS */

define( 'WPPSN_PLUGIN_URL', WP_PLUGIN_URL . '/' . WPPSN_PLUGIN_FOLDER_NAME . '/' );
define( 'WPPSN_PLUGIN_CSS_URL', WPPSN_PLUGIN_URL . 'css/' );
define( 'WPPSN_PLUGIN_INCLUDES_URL', WPPSN_PLUGIN_URL . 'includes/' );
define( 'WPPSN_PLUGIN_TEMPLATES_URL', WPPSN_PLUGIN_INCLUDES_URL . 'templates/' );
define( 'WPPSN_PLUGIN_IMAGES_URL', WPPSN_PLUGIN_URL . 'images/' );
define( 'WPPSN_PLUGIN_JS_URL', WPPSN_PLUGIN_URL . 'js/' );
define( 'WPPSN_PLUGIN_LIBS_URL', WPPSN_PLUGIN_URL . 'libs/' );
define( 'WPPSN_PLUGIN_FLOWPLAYER_URL', WPPSN_PLUGIN_LIBS_URL . 'flowplayer/' );
define( 'WPPSN_PLUGIN_FLEXSLIDER_URL', WPPSN_PLUGIN_LIBS_URL . 'flexslider/' );

/* Others */
define( 'WPPSN_MODAL_LIST_MEDIA_PER_PAGE', 24 ); // Multiple of 2, 3 and 4 (for rows of media in responsive design)