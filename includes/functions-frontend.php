<?php
/**
 * Frontend Functions
 */


/**
 * Add Scripts and CSS in Frontend
 */
function wppsn_wp_enqueue_scripts() {

	// Custom CSS
	wp_enqueue_style( 'wppsn_frontend_css', WPPSN_PLUGIN_CSS_URL . 'wppsn-frontend.css', array(), '1.0.0', 'all' );

	// JPlayer CSS
	wp_enqueue_style( 'wppsn_jplayer_css', WPPSN_PLUGIN_JPLAYER_URL . 'skins/blue.monday/jplayer.blue.monday.css', array(), '1.0.0', 'all' );

	// FlexSlider CSS
	wp_enqueue_style( 'wppsn_flexslider_css', WPPSN_PLUGIN_FLEXSLIDER_URL . 'flexslider.css', array(), '1.0.0', 'all' );

	// JPlayer JS
	// Register only : it will be enqueue if 'wppsn-video' shortcode is found
	wp_register_script( 'wppsn_jplayer_js', WPPSN_PLUGIN_JPLAYER_URL . 'jquery.jplayer.min.js', array(), '1.0.0', true );

	// Flexslider JS
	// Register only : it will be enqueue if 'wppsn-img-gallery' shortcode is found with carrousel mode
	wp_register_script( 'wppsn_flexslider_js', WPPSN_PLUGIN_FLEXSLIDER_URL . 'jquery.flexslider-min.js', array(), '1.0.0', true );

	// Custom JS for JPlayer
	// Register only : it will be enqueue if 'wppsn-video' shortcode is found
	wp_register_script( 'wppsn_frontend_video_js', WPPSN_PLUGIN_JS_URL . 'wppsn-frontend-video.js', array( 'jquery', 'wppsn_jplayer_js' ), '1.0.0', true );
	wp_localize_script( 'wppsn_frontend_video_js', 'wppsnGlobalVars', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'jplayerSwfUrl' => WPPSN_PLUGIN_JPLAYER_URL . 'Jplayer.swf'
	));

	// Custom JS for Flexslider
	// Register only : it will be enqueue if 'wppsn-img-gallery' shortcode is found with carrousel mode
	wp_register_script( 'wppsn_frontend_carrousel_js', WPPSN_PLUGIN_JS_URL . 'wppsn-frontend-carrousel.js', array( 'jquery', 'wppsn_flexslider_js' ), '1.0.0', true );

}

add_action( 'wp_enqueue_scripts', 'wppsn_wp_enqueue_scripts' );


/**
 * Define the shortcode 'wppsn-image'
 * @param  array $atts Array of attributes
 * @return html        HTML replacing the shortcode
 */
function wppsn_shortcode_single_image( $atts ) {

	$output = '';

	extract( shortcode_atts( array(
	    'title'				=> '',
	    'alt'				=> '',
	    'legend'			=> '',
	    'download'			=> 0,
	    'url'				=> ''
	  ), $atts ) );

	$title = trim( $title );
	$alt = trim( $alt );
	$legend = trim( $legend );
	$url = trim( $url );

	// Download link ?
	$downloadLink = ( $download == 1 ) ? '<a href="' . $url . '" target="_blank">' . __( 'Download', 'wp-phraseanet' ) . '</a>' : '';

	// Encapsulate the image in a div for responsive design
	$output .= '<div class="wppsn-image wp-caption">
					<a href="' . $url . '">
						<img class="wp-image-" src="' . $url . '" title="' . $title . '" alt="' . $alt . '">
					</a>';

	if ( $legend != '' || $downloadLink != '' ) {
		$output .= '	<p class="wp-caption-text">' . $legend . ( ( $legend != '' ) ? ' - ' : '' ) . $downloadLink . '</p>';
	}

	$output .= '</div>';

	return $output;

}

add_shortcode( 'wppsn-image', 'wppsn_shortcode_single_image' );


/**
 * Define the shortcode 'wppsn-image'
 * @param  array $atts Array of attributes
 * @return html        HTML replacing the shortcode
 */
function wppsn_shortcode_single_video( $atts ) {

	$output = '';
	$uniqueString = md5( microtime( true ) );
	$playerWrapperID = 'wppsn-video-player-wrapper-' . $uniqueString;
	$playerID = 'wppsn-video-player-' . $uniqueString;
	usleep(1);

	// Add JS to the footer
	wp_enqueue_script( 'wppsn_frontend_video_js' );

	// Get Attributes
	extract( shortcode_atts( array(
	    'title'				=> '',
	    'h264'				=> ''
	  ), $atts ) );

	$title = trim( $title );
	$h264 = trim( $h264 );

	$output .= '<div id="' . $playerWrapperID . '" class="wppsn-video-player-wrapper jp-video">
						
		            <div class="jp-type-single">
		                <div id="' . $playerID . '" class="jp-jplayer"></div>
		                <div class="jp-gui">
		                    <div class="jp-video-play">
		                        <a href="javascript:;" class="jp-video-play-icon" tabindex="1">play</a>
		                    </div>
		                    <div class="jp-interface">
		                        <div class="jp-progress">
		                            <div class="jp-seek-bar">
		                                <div class="jp-play-bar"></div>
		                            </div>
		                        </div>
		                        <div class="jp-current-time"></div>
		                        <div class="jp-duration"></div>
		                        <div class="jp-controls-holder">
		                            <ul class="jp-controls">
		                                <li><a href="javascript:;" class="jp-play" tabindex="1">' . __( 'play', 'wp-phraseanet' ) . '</a></li>
		                                <li><a href="javascript:;" class="jp-pause" tabindex="1">' . __( 'pause', 'wp-phraseanet' ) . '</a></li>
		                                <li><a href="javascript:;" class="jp-stop" tabindex="1">' . __( 'stop', 'wp-phraseanet' ) . '</a></li>
		                                <li><a href="javascript:;" class="jp-mute" tabindex="1" title="' . __( 'mute', 'wp-phraseanet' ) . '">' . __( 'mute', 'wp-phraseanet' ) . '</a></li>
		                                <li><a href="javascript:;" class="jp-unmute" tabindex="1" title="' . __( 'unmute', 'wp-phraseanet' ) . '">' . __( 'unmute', 'wp-phraseanet' ) . '</a></li>
		                                <li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="' . __( 'max volume', 'wp-phraseanet' ) . '">' . __( 'max volume', 'wp-phraseanet' ) . '</a></li>
		                            </ul>
		                            <div class="jp-volume-bar">
		                                <div class="jp-volume-bar-value"></div>
		                            </div>
		                            <ul class="jp-toggles">
		                                <li><a href="javascript:;" class="jp-full-screen" tabindex="1" title="' . __( 'full screen', 'wp-phraseanet' ) . '">' . __( 'full screen', 'wp-phraseanet' ) . '</a></li>
		                                <li><a href="javascript:;" class="jp-restore-screen" tabindex="1" title="' . __( 'restore screen', 'wp-phraseanet' ) . '">' . __( 'restore screen', 'wp-phraseanet' ) . '</a></li>
		                                <li><a href="javascript:;" class="jp-repeat" tabindex="1" title="' . __( 'repeat', 'wp-phraseanet' ) . '">' . __( 'repeat', 'wp-phraseanet' ) . '</a></li>
		                                <li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="' . __( 'repeat off', 'wp-phraseanet' ) . '">' . __( 'repeat off', 'wp-phraseanet' ) . '</a></li>
		                            </ul>
		                        </div>
		                        <div class="jp-title">
		                            <ul>
		                            	<li>' . $title . '</li>
		                            </ul>
		                            <ul class="jp-files-formats" style="display:none !important;">
		                            	<li class="jp-file-h264">' . $h264 . '</li>
		                            </ul>
		                        </div>
		                    </div>
		                </div>
		                <div class="jp-no-solution">
		                    <span>' . __( 'Update required', 'wp-phraseanet' ) . '</span>
		                    ' . __( 'To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.' , 'wp-phraseanet' ) . '
		                </div>
		            </div>

				</div>';

	return $output;

}

add_shortcode( 'wppsn-video', 'wppsn_shortcode_single_video' );


/**
 * Define the shortcode 'wppsn-img-gallery'
 * @param  array $atts Array of attributes
 * @return html        HTML replacing the shortcode
 */
function wppsn_shortcode_image_gallery( $atts ) {

	$output = '';

	extract( shortcode_atts( array(
		'display'			=> 'list',
	    'titles'			=> '',
	    'alts'				=> '',
	    'legends'			=> '',
	    'thumbs'			=> '',
	    'urls'				=> ''
	  ), $atts ) );

	// Explode Strings to Arrays
	$allTitles = explode( '||', $titles );
	$allAlts = explode( '||', $alts );
	$allLegends = explode( '||', $legends );
	$allThumbs = explode( '||', $thumbs );
	$allUrls = explode( '||', $urls );

	switch( $display ) {

		case 'grid':

			$cpt = 1;
			$galleryID = 'gallery-' . md5( microtime( true ) );
			usleep(1);

			$output .= '<style type="text/css">
							#' . $galleryID . ' {
								margin: auto;
							}
							#' . $galleryID . ' .gallery-item {
								float: left;
								margin-top: 10px;
								text-align: center;
								width: 33%;
							}
							#' . $galleryID . ' img {
								border: 2px solid #cfcfcf;
							}
							#' . $galleryID . ' .gallery-caption {
								margin-left: 0;
							}
						</style>';

			$output .= '<div id="' . $galleryID . '" class="gallery">';

			foreach( $allTitles as $i => $t ) {

				$title = trim( $t );
				$alt = trim( $allAlts[$i] );
				$legend = trim( $allLegends[$i] );
				$thumb = trim( $allThumbs[$i] );
				$url = trim( $allUrls[$i] );

				$output .= '<dl class="gallery-item">
								<dt class="gallery-icon">
									<a href="' . $url . '" title="' . $title . '">
										<img src="' . $thumb . '" class="attachment-thumbnail" alt="' . $alt . '">
									</a>
								</dt>';

				// Legend ?
				if ( $legend != '' ) {
					$output .= '<dd class="wp-caption-text gallery-caption">' . $legend . '</dd>';
				}
								
				$output .= '</dl>';

				// 3 columns break
				if ( $cpt % 3 == 0 ) {
					$output .= '<br style="clear:both;">';
				}

				$cpt++;

			}

			// To be sure :)
			$output .= '<br style="clear:both;">';

			$output .= '</div>';

			break;

		case 'carrousel':

			$uniqueString = md5( microtime( true ) );

			$sliderID = 'wppsn-slider-' . $uniqueString;
			$carrouselID = 'wppsn-carrousel-' . $uniqueString;
			usleep(1);

			// Add JS to the Footer
			wp_enqueue_script( 'wppsn_frontend_carrousel_js' );

			// Create the slider First
			
			$output .= '<div id="' . $sliderID . '" class="wppsn-slider flexslider">
		  					<ul class="slides">';

		  	foreach( $allTitles as $i => $t ) {

		  		$title = trim( $t );
				$alt = trim( $allAlts[$i] );
				$legend = trim( $allLegends[$i] );
				$thumb = trim( $allThumbs[$i] );
				$url = trim( $allUrls[$i] );

				$output .= '<li>
								<img src="' . $url . '" title="' . $title . '" alt="' . $alt . '">';

				// Legend ?
				if ( $legend != '' ) {
					$output	.= '<p class="flex-caption">' . $legend . '</p>';
				}

				$output .= '</li>';

		  	}

		  	$output .= '	</ul>
		  				</div>';
			

			// Create the carrousel Secondly

			$output .= '<div id="' . $carrouselID . '" class="wppsn-carrousel flexslider">
		  					<ul class="slides">';

		  	foreach( $allTitles as $i => $t ) {

		  		$title = trim( $t );
				$alt = trim( $allAlts[$i] );
				$legend = trim( $allLegends[$i] );
				$thumb = trim( $allThumbs[$i] );
				$url = trim( $allUrls[$i] );

				$output .= '<li>
								<img src="' . $url . '" title="' . $title . '" alt="' . $alt . '">
							</li>';

		  	}

		  	$output .= '	</ul>
		  				</div>';

			break;

		default: // List

			foreach( $allTitles as $i => $t ) {

				$title = trim( $t );
				$alt = trim( $allAlts[$i] );
				$legend = trim( $allLegends[$i] );
				$url = trim( $allUrls[$i] );

				$output .= '<div class="wppsn-image wp-caption">
								<a href="' . $url . '">
									<img class="wp-image-" src="' . $url . '" title="' . $title . '" alt="' . $alt . '">
								</a>';

				if ( $legend != '' ) {
					$output .= '	<p class="wp-caption-text">' . $legend . '</p>';
				}

				$output .= '</div>';

			}

			break;

	}

	return $output;

}

add_shortcode( 'wppsn-img-gallery', 'wppsn_shortcode_image_gallery' );