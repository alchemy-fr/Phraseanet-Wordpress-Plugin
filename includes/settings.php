<?php
/**
 * Settings Definition
 */


/**
 * Register settings
 */
function wppsn_register_settings() {
    register_setting( 'wppsn_options', 'wppsn_options', 'wppsn_validate_options' );

    // Main Section
    add_settings_section( 'wppsn_main_settings', __( 'Main settings', 'wp-phraseanet' ), 'wppsn_section_main_settings_text', 'wppsn_settings_page' );

    // Field : API token
    add_settings_field( 'wppsn_api_token', __( 'API token', 'wp-phraseanet' ), 'wppsn_field_api_token', 'wppsn_settings_page', 'wppsn_main_settings' );

}


/**
 * Main Settings section Text
 */
function wppsn_section_main_settings_text() {
    // Nothing usefull
}


/**
 * Field display/fill : wppsn_api_token
 */
function wppsn_field_api_token() {

    $wppsn_options = get_option( 'wppsn_options' );
    $wppsn_api_key = $wppsn_options['api_token'];

    echo '<input type="text" id="wppsn_api_key" class="regular-text" name="wppsn_options[wppsn_api_key]" value="' . $wppsn_api_key . '">';
    echo '<p class="description">' . __( 'Some description.', 'wp-phraseanet' ) . '</p>';

}


/**
 * Validate User inputs
 * @param  array $user_input  Input data submitted by the user ($_POST)
 * @return array              Sanitized user input data
 */
function wppsn_validate_options( $user_input ) {

    $wppsn_options = get_option( 'wppsn_options' );
    $valid = array();

    // **TODO** Check API token validity
    $valid['api_token'] = $user_input['api_token'];

    return $valid;

}