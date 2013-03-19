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

    // Field : Client Base URL
    add_settings_field( 'wppsn_client_base_url', __( 'Your Phraseanet base URL', 'wp-phraseanet' ), 'wppsn_field_client_base_url', 'wppsn_settings_page', 'wppsn_main_settings' );

    // Field : Client ID
    add_settings_field( 'wppsn_client_id', __( 'Client ID', 'wp-phraseanet' ), 'wppsn_field_client_id', 'wppsn_settings_page', 'wppsn_main_settings' );

    // Field : Client Secret
    add_settings_field( 'wppsn_client_secret', __( 'Client Secret', 'wp-phraseanet' ), 'wppsn_field_client_secret', 'wppsn_settings_page', 'wppsn_main_settings' );

    // Field : Client Token
    add_settings_field( 'wppsn_client_token', __( 'Oauth Token', 'wp-phraseanet' ), 'wppsn_field_client_token', 'wppsn_settings_page', 'wppsn_main_settings' );

}


/**
 * Main Settings section Text
 */
function wppsn_section_main_settings_text() {
    // Nothing usefull
}


/**
 * Field display/fill : wppsn_client_base_url
 */
function wppsn_field_client_base_url() {

    $wppsn_options = get_option( 'wppsn_options' );
    $wppsn_client_base_url = $wppsn_options['client_base_url'];

    echo '<input type="text" id="wppsn_client_base_url" class="regular-text" name="wppsn_options[client_base_url]" value="' . $wppsn_client_base_url . '">';

}


/**
 * Field display/fill : wppsn_client_id
 */
function wppsn_field_client_id() {

    $wppsn_options = get_option( 'wppsn_options' );
    $wppsn_client_id = $wppsn_options['client_id'];

    echo '<input type="text" id="wppsn_client_id" class="regular-text" name="wppsn_options[client_id]" value="' . $wppsn_client_id . '">';

}

/**
 * Field display/fill : wppsn_client_secret
 */
function wppsn_field_client_secret() {

    $wppsn_options = get_option( 'wppsn_options' );
    $wppsn_client_secret = $wppsn_options['client_secret'];

    echo '<input type="text" id="wppsn_client_secret" class="regular-text" name="wppsn_options[client_secret]" value="' . $wppsn_client_secret . '">';

}


/**
 * Field display/fill : wppsn_client_token
 */
function wppsn_field_client_token() {

    $wppsn_options = get_option( 'wppsn_options' );
    $wppsn_client_token = $wppsn_options['client_token'];

    echo '<input type="text" id="wppsn_client_token" class="regular-text" name="wppsn_options[client_token]" value="' . $wppsn_client_token . '">';

}


/**
 * Validate User inputs
 * @param  array $user_input  Input data submitted by the user ($_POST)
 * @return array              Sanitized user input data
 */
function wppsn_validate_options( $user_input ) {

    $wppsn_options = get_option( 'wppsn_options' );
    $valid = array();

    // Check required empty fields
    if ( $user_input['client_base_url'] == '' || $user_input['client_id'] == '' || $user_input['client_secret'] == '' || $user_input['client_token'] == '' ) {
        add_settings_error(
            'wppsn_client_base_url',
            'wppsn_client_base_url_error',
            __( 'The fields "Phraseanet base URL", Client ID", "Client Secret", "Oauth token" are required.', 'wp-phraseanet' ),
            'error'
        );        
    }

    /* Check Base URL */

    $valid['client_base_url'] = $user_input['client_base_url'];

    if ( !filter_var( $valid['client_base_url'], FILTER_VALIDATE_URL ) ) {
        add_settings_error(
            'wppsn_client_base_url',
            'wppsn_client_base_url_error',
            __( 'The Phraseanet base URL is apparently bad formatted.', 'wp-phraseanet' ),
            'error'
        );
    }
    
    /* Check Client ID */

    $valid['client_id'] = preg_replace( '/[^A-Za-z0-9]/', '', $user_input['client_id'] );

    // If not alphanumeric, register error
    if ( $valid['client_id'] != $user_input['client_id'] ) {
        add_settings_error(
            'wppsn_client_id',
            'wppsn_client_id_error',
            __( 'The Client ID is not valid. It must contain only alphanumeric characters.', 'wp-phraseanet' ),
            'error'
        );
    }
    // If not 32 characters long, register error
    elseif ( $valid['client_id'] != '' && strlen( $valid['client_id'] ) != 32 ) {
        add_settings_error(
            'wppsn_client_id',
            'wppsn_client_id_error',
            __( 'The Client ID is not valid. It must contain at least 32 characters.', 'wp-phraseanet' ),
            'error'
        );
    }

    /* Check Client Secret */

    $valid['client_secret'] = preg_replace( '/[^A-Za-z0-9]/', '', $user_input['client_secret'] );

    // If not alphanumeric, register error
    if ( $valid['client_secret'] != $user_input['client_secret'] ) {
        add_settings_error(
            'wppsn_client_secret',
            'wppsn_client_secret_error',
            __( 'The Client Secret is not valid. It must contain only alphanumeric characters.', 'wp-phraseanet' ),
            'error'
        );
    }
    // If not 32 characters long, register error
    elseif ( $valid['client_secret'] != '' && strlen( $valid['client_secret'] ) != 32 ) {
        add_settings_error(
            'wppsn_client_secret',
            'wppsn_client_secret_error',
            __( 'The Client Secret is not valid. It must contain at least 32 characters.', 'wp-phraseanet' ),
            'error'
        );
    }
    
    /* Check Token */

    $valid['client_token'] = preg_replace( '/[^A-Za-z0-9]/', '', $user_input['client_token'] );

    // If not alphanumeric, register error
    if ( $valid['client_token'] != $user_input['client_token'] ) {
        add_settings_error(
            'wppsn_client_token',
            'wppsn_client_token_error',
            __( 'The Oauth Token is not valid. It must contain only alphanumeric characters.', 'wp-phraseanet' ),
            'error'
        );
    }
    // If not 32 characters long, register error
    elseif ( $valid['client_token'] != '' && strlen( $valid['client_token'] ) != 32 ) {
        add_settings_error(
            'wppsn_client_token',
            'wppsn_client_token_error',
            __( 'The Oauth Token is not valid. It must contain at least 32 characters.', 'wp-phraseanet' ),
            'error'
        );
    }

    return $valid;

}


