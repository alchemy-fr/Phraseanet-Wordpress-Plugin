<?php
/**
 * Admin Functions
 */


/**
 * Plugin functions attached to 'admin_init' action
 * - Add a new button in the wysiwyg for opening the Phraseanet Modal
 * - Define and register plugin settings page
 */
function wppsn_admin_init() {

    // Add button in wysiwyg for author / editor / admin, only in the rich view
    if ( current_user_can( 'upload_files' ) && get_user_option('rich_editing') == 'true' ) {

        // Register javascript file of the button
        add_filter( 'mce_external_plugins', 'wppsn_mce_external_plugins' );

        // Register the button
        add_filter( 'mce_buttons', 'wppsn_mce_buttons' );

    }

    // Register settings
    require_once( WPPSN_PLUGIN_INCLUDES_PATH . 'settings.php' );
    wppsn_register_settings();

}

add_action( 'admin_init', 'wppsn_admin_init' );


/**
 * Javascript added to the admin area <head>
 * @param  string $hook Current hook used
 */
function wppsn_admin_enqueue_scripts( $hook ) {

    // Admin CSS
    wp_enqueue_style( 'wppsn-admin-css', WPPSN_PLUGIN_CSS_URL . 'admin.css', array(), '1.0.0', 'all' );

    // Some i18n and global vars
    wp_localize_script( 'jquery', 'wppsnInfosWysiwyg', array(
        'buttonText'                 => __( 'Insert a Phraseanet Content', 'wp-phraseanet' ),    // Text of the WYSIWYG button
        'buttonUrl'                  => WPPSN_PLUGIN_IMAGES_URL . 'phraseanet-button.png',       // Icon of the WYSIWYG button
        'buttonModalContentFileUrl'  => WPPSN_PLUGIN_TEMPLATES_URL . 'tpl-modal-container.php'   // File called in the modal window
    ));

}

add_action( 'admin_enqueue_scripts', 'wppsn_admin_enqueue_scripts' );


/**
 * Add plugin option page in Main Settings Menu
 */
function wppsn_admin_menu() {

    // Top Level menu
    add_menu_page( __( 'WP Phraseanet - Settings', 'wp-phraseanet' ), WPPSN_PLUGIN_MENU_NAME, 'publish_posts', 'wppsn_settings_page', 'wppsn_settings_page_content', 'div', 100);
    
    // Submenus
    add_submenu_page( 'wppsn_settings_page', __( 'WP Phraseanet - Help - Credits', 'wp-phraseanet' ), __( 'Help - Credits', 'wp-phraseanet' ), 'publish_posts', 'wppsn_help_credits_page', 'wppsn_help_credits_page_content' );
    
    // Fix the title of the 1st submenu which is the same link than the top level but not the same text
    global $submenu;
    
    if ( isset($submenu['wppsn_settings_page']) ) {
        $submenu['wppsn_settings_page'][0][0] = __( 'Settings', 'lm-maps-manager' );
    }


    // add_options_page( __( 'WP Phraseanet Settings', 'wp-phraseanet' ), __( 'WP Phraseanet', 'wp-phraseanet' ), 'manage_options', 'wppsn_settings_page', 'wppsn_settings_page_content' );
}

add_action( 'admin_menu', 'wppsn_admin_menu' );


/**
 * Settings page Content
 */
function wppsn_settings_page_content() {
    require_once( WPPSN_PLUGIN_TEMPLATES_PATH . 'tpl-page-settings.php' );
}


/**
 * Help - Credits page Content
 */
function wppsn_help_credits_page_content() {
    require_once( WPPSN_PLUGIN_TEMPLATES_PATH . 'tpl-page-help-credits.php' );
}


/**
 * Register javascript file for the button in the WYSIWYG
 * @param array $plugins_array Array of active plugins in WYSIWYG
 * @return array Array of active plugins
 */
function wppsn_mce_external_plugins( $plugins_array ) {
    $plugins_array['wppsn_phraseanet_button'] = WPPSN_PLUGIN_JS_URL . 'register-wysiwyg-button.js';
    return $plugins_array;
}


/**
 * Register the button in the WYSIWYG
 * @param array $buttons Array of displayed buttons in WYSIWYG
 * @return array Array of displayed buttons
 */
function wppsn_mce_buttons( $buttons ) {
    array_push( $buttons, '|', 'wppsn_phraseanet_button' );
    return $buttons;
}


/**
 * Require Functions for the Modal
 */
require_once( 'functions-modal.php' );