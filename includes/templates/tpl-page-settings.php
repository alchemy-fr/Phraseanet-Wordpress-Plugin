<?php
/**
 * Template of the settings page
 */
?>

<div id="wppsn_settings" class="wrap">
      
    <div id="icon-options-general" class="icon32"><br></div>
    
    <h2><?php _e( 'WP Phraseanet Settings', 'wp-phraseanet' ); ?></h2>

    <form action="options.php" method="post">

        <?php
        settings_fields( 'wppsn_options' );
        do_settings_sections( 'wppsn_settings_page' );
        ?>

        <p class="submit">
          <input type="submit" name="Submit" value="<?php _e( 'Save changes', 'wp-phraseanet' ); ?>" class="button-primary">
        </p>

    </form>

</div>