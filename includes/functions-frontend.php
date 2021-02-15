<?php
/**
 * Frontend Functions
 */

/**
 * Add Scripts and CSS in Frontend
 */
function wppsn_wp_enqueue_scripts()
{

    /* CSS */

    // Custom CSS
    wp_enqueue_style('wppsn_frontend_css', WPPSN_PLUGIN_CSS_URL . 'wppsn-frontend.css', array() , '2.0.0', 'all');

    // Flowplayer CSS
    wp_enqueue_style('wppsn_flowplayer_css', WPPSN_PLUGIN_FLOWPLAYER_URL . 'skin/minimalist.css', array() , '2.0.0', 'all');

    // FlexSlider CSS
    wp_enqueue_style('wppsn_flexslider_css', WPPSN_PLUGIN_FLEXSLIDER_URL . 'flexslider.css', array() , '2.0.0', 'all');

    // Swipebox CSS
    wp_enqueue_style('wppsn_swipebox_css', WPPSN_PLUGIN_SWIPEBOX_URL . 'swipebox.css', array() , '2.0.0', 'all');

    /* JS */

    /**
     * Load FlowPlayer in head
     * It appears that loading it in footer causes problems
     */
    wp_enqueue_script('jquery');
    wp_enqueue_script('wppsn_flowplayer_js', WPPSN_PLUGIN_FLOWPLAYER_URL . 'flowplayer.js', array(
        'jquery'
    ) , '1.0.7', false);
    wp_localize_script('wppsn_flowplayer_js', 'wppsnFlowPlayerVars', array(
        'flashUrl' => WPPSN_PLUGIN_FLOWPLAYER_URL . 'flowplayer.swf'
    ));

    // Flexslider JS
    // Register only : it will be enqueue if 'wppsn-img-gallery' shortcode is found with carrousel mode
    wp_register_script('wppsn_flexslider_js', WPPSN_PLUGIN_FLEXSLIDER_URL . 'jquery.flexslider-min.js', array(
        'jquery'
    ) , '1.0.7', true);

    // Swipebox JS
    // Register only : it will be enqueue if 'wppsn-image' or 'wppsn-img-gallery' (with list or grid display mode) shortcodes are found
    wp_register_script('wppsn_swipebox_js', WPPSN_PLUGIN_SWIPEBOX_URL . 'jquery.swipebox.min.js', array(
        'jquery'
    ) , '1.0.7', true);
    wp_register_script('wppsn_swipebox_ios_orientation_fix_js', WPPSN_PLUGIN_SWIPEBOX_URL . 'ios-orientationchange-fix.js', array(
        'jquery'
    ) , '1.0.7', true);

    // Custom JS for Flexslider
    // Register only : it will be enqueue if 'wppsn-img-gallery' shortcode is found with carrousel mode
    wp_register_script('wppsn_frontend_carrousel_js', WPPSN_PLUGIN_JS_URL . 'wppsn-frontend-carrousel.js', array(
        'wppsn_flexslider_js'
    ) , '1.0.7', true);

    // Custom JS for Flowplayer and single videos
    // Register only : it will be enqueue if 'wppsn-video' shortcode is found
    wp_register_script('wppsn_frontend_video_js', WPPSN_PLUGIN_JS_URL . 'wppsn-frontend-video.js', array(
        'wppsn_flowplayer_js'
    ) , '1.0.7', true);

    // Custom JS for Flowplayer and video playlists
    // Register only : it will be enqueue if 'wppsn-video-playlist' shortcode is found
    wp_register_script('wppsn_frontend_video_playlist_js', WPPSN_PLUGIN_JS_URL . 'wppsn-frontend-video-playlist.js', array(
        'wppsn_flowplayer_js'
    ) , '1.0.7', true);

    // Custom JS for Swipebox
    // Register only : it will be enqueue if 'wppsn-image' or 'wppsn-img-gallery' (with list or grid display mode) shortcodes are found
    wp_register_script('wppsn_frontend_swipebox_js', WPPSN_PLUGIN_JS_URL . 'wppsn-frontend-swipebox.js', array(
        'wppsn_swipebox_js',
        'wppsn_swipebox_ios_orientation_fix_js'
    ) , '1.0.7', true);

}

add_action('wp_enqueue_scripts', 'wppsn_wp_enqueue_scripts');

add_action('wp_enqueue_scripts', 'image_model');

function image_model()
{
    wp_enqueue_script('image_model', WPPSN_PLUGIN_JS_URL . 'wppsn-frontend-modal.js');
}

add_action('wp_enqueue_scripts', 'image_model_css');

function image_model_css()
{
    wp_enqueue_style('image_model_css', WPPSN_PLUGIN_CSS_URL . 'wppsn-frontend-modal.css');
}

/**
 * Define the shortcode 'wppsn-image'
 * @param  array $atts Array of attributes
 * @return html        HTML replacing the shortcode
 */
function wppsn_shortcode_single_image($atts)
{
    global $post;

    $output = '';

    // Add Swipebox JS to the footer
    wp_enqueue_script('wppsn_frontend_swipebox_js');

    extract(shortcode_atts(array(
        'title' => '',
        'alt' => '',
        'legend' => '',
        'download' => 0,
        'url' => '',
        'full_url'=>'',
    ) , $atts));

    $title = trim($title);
    $alt = trim($alt);
    $legend = trim($legend);
    $url = trim($url);
	$downloadLink = '';
    $id = md5($url);
    $full_url = trim($full_url);

    //var_dump($url);
    // Download link ?
    if ($download == 1)
    {
        $downloadLink = '<a href="' . $full_url . ((false === strpos('?', $full_url)) ? '&download=1' : '&download') . '">' . __('Download', 'wp-phraseanet') . '</a>';
    }

    $output .= '<div class="wppsn-image wp-caption">

		<img class="single_image wp-image-" src="' . $full_url . '" alt="' . $alt . '" name="' . $title . '" id="'.$id.'"  onclick="openModel(this.id)"  style="cursor: pointer;" >
	'
;

    if ($legend != '' || $downloadLink != '')
    {
        $output .= '	<p class="wp-caption-text">' . $legend . (($legend != '') ? ' - ' : '') . $downloadLink . '</p>';
    }

    $output .= "<div id='myModal_$id' class='modal'><span class='close'>&times;</span><img class='modal-content' id='img_$id'><div id='caption_$id' class='caption' ></div></div></div>";

    return $output;

}

add_shortcode('wppsn-image', 'wppsn_shortcode_single_image');

/**
 * Define the shortcode 'wppsn-image'
 * @param  array $atts Array of attributes
 * @return html        HTML replacing the shortcode
 */
function wppsn_shortcode_single_video($atts)
{

    $output = '';
    // $uniqueString = md5( microtime( true ) );
    // $playerID = 'wppsn-video-player-' . $uniqueString;
    // usleep(1);
    // Add JS for loading Flowplayer to the footer
    wp_enqueue_script('wppsn_frontend_video_js');

    // Get Attributes
    extract(shortcode_atts(array(
        'title' => '',
        'mp4' => '',
        'webm' => '',
        'ogg' => '',
        'splash' => ''
    ) , $atts));

    $title = trim($title);
    $mp4 = trim($mp4);
    $webm = trim($webm);
    $ogg = trim($ogg);
    $splash = trim($splash);

    $styleSplash = ($splash != '') ? ' style="background-image:url(' . $splash . ')"' : '';

    $output .= '<div class="wppsn-video-player-wrapper">
					<div class="is-splash wppsn-video-player" ' . $styleSplash . '>
						<video>
							<source type="video/mp4" src="' . $mp4 . '">';

    if ($webm != '')
    {
        $output .= '<source type="video/webm" src="' . $webm . '">';
    }

    if ($ogg != '')
    {
        $output .= '<source type="video/ogg" src="' . $ogg . '">';
    }

    $output .= '		</video>
					</div>
					<p class="wppsn-video-player-title">' . $title . '</p>
				</div>';

    return $output;

}

add_shortcode('wppsn-video', 'wppsn_shortcode_single_video');

/**
 * Define the shortcode 'wppsn-img-gallery'
 * @param  array $atts Array of attributes
 * @return html        HTML replacing the shortcode
 */
function wppsn_shortcode_image_gallery($atts)
{
    global $post;

    $output = '';

    extract(shortcode_atts(array(
        'display' => 'list',
        'titles' => '',
        'alts' => '',
        'legends' => '',
        'thumbs' => '',
        'urls' => '',
        'download' => '',
        'full_url'=> ''
    ) , $atts));

    // Explode Strings to Arrays
    $allTitles = explode('||', $titles);
    $allAlts = explode('||', $alts);
    $allLegends = explode('||', $legends);
    $allThumbs = explode('||', $thumbs);
    $allUrls = explode('||', $urls);
    $allDownlods = explode('||', $download);
       
    switch ($display)
    {

        case 'grid':

            // Add Swipebox JS to the footer
            wp_enqueue_script('wppsn_frontend_swipebox_js');

            $cpt = 1;
            $galleryID = 'gallery-' . md5(microtime(true));
            usleep(1);

            $output .= '<style type="text/css">
							#' . $galleryID . ' {
								margin: auto;
							}
							#' . $galleryID . ' .gallery-item {
								float: left;
								margin-top: 10px;
								text-align: center;
								
								position: relative;
								width: 40%;
							}
							#' . $galleryID . ' img {
								border: 2px solid #cfcfcf;
							}
							#' . $galleryID . ' .gallery-caption {
								margin-left: 0;
							}

							  .image {
								opacity: 1;
								display: block;
								width: 100%;
								height: auto;
								transition: .5s ease;
								backface-visibility: hidden;
							  }
							  
							  .middle {
								transition: .5s ease;
								opacity: 0;
								position: absolute;
								top: 50%;
								left: 50%;
								transform: translate(-50%, -50%);
								-ms-transform: translate(-50%, -50%);
								text-align: center;
                              }
                              
                              .middle2{
                                width: 100%;
                                padding: 1px 1px 1px 1px;
                                text-align: center;
                              }
							  
							  .gallery-item:hover .image {
								opacity: 0.3;
							  }
							  
							  .gallery-item:hover .middle {
								opacity: 1;
							  }
							
							  .text {
								background-color: #06060638;
								color: white;
								font-size: 16px;
								padding: 16px 32px;
								
							  };
							  a:link {
								color: black;
							  }

							  a:hover {
								color: white;
								text-decoration: none;
							  }
							  
						

						</style>';

            $output .= '<div  id="' . $galleryID . '" class="wppsn-gallery gallery">';

            $slideIndex = 1;

            foreach ($allTitles as $i => $t)
            {

                $title = trim($t);
                $alt = trim($allAlts[$i]);
                $legend = trim($allLegends[$i]);
                $thumb = trim($allThumbs[$i]);
                $url = trim($allUrls[$i]);
                $download = trim($allDownlods[$i]);
   
                $download_url = '';
                if ($download == 'on')
                {

                    $download_url = "<a href='$url&download=1'>Download</a>";
                }

                //Navigation
                $nav = "<a class='prev' onclick='plusSlides($slideIndex,0)'>&#10094;</a><a class='next' onclick='plusSlides($slideIndex,1)'>&#10095;</a>";

                $output .= "<div class='gallery-item'>
  <img src='$url' alt='Avatar' class='image' >
  <div id='middle_text' class='middle'>
  
  <div class='text'>
  
  
  <a href='javascript:void(0)'  id='$slideIndex'  rel='$url' name='$title' onclick='openModel($slideIndex)' >View</a> 
  <br>

  $download_url

  </div></div></div><div id='myModal_$slideIndex' class='modal'><span class='close'>&times;</span><img class='modal-content' id='img_$slideIndex'>
  <div id='nav_$slideIndex' > 
			$nav
    </div>                                  
  <div id='caption_$slideIndex' class='caption' >
  
  </div>
  
 
  </div>
  
 ";

                $output .= '</dl>';
                $slideIndex = $slideIndex + 1; //inc the counter
                // 3 columns break
                if ($cpt % 3 == 0)
                {
                    $output .= '<br style="clear:both;">';
                }

                $cpt++;

            }

            // To be sure :)
            $output .= '<br style="clear:both;">';

            $output .= '</div>';

        break;

        case 'carrousel':

            $uniqueString = md5(microtime(true));

            $sliderID = 'wppsn-slider-' . $uniqueString;
            $carrouselID = 'wppsn-carrousel-' . $uniqueString;
            usleep(1);

            // Add Flexslider JS to the Footer
            wp_enqueue_script('wppsn_frontend_carrousel_js');

            // Create the slider First
            $output .= '<div id="' . $sliderID . '" class="wppsn-slider flexslider">
		  					<ul class="slides">';

            foreach ($allTitles as $i => $t)
            {

                $title = trim($t);
                $alt = trim($allAlts[$i]);
                $legend = trim($allLegends[$i]);
                $thumb = trim($allThumbs[$i]);
                $url = trim($allUrls[$i]);

                $output .= '<li>
								<img src="' . $url . '" title="' . $title . '" alt="' . $alt . '">';

                // Legend ?
                if ($title != '')
                {
                    $output .= '<p style="padding-left:40%" class="flex-caption">' . $title . '</p>';
                }

                $output .= '</li>';

            }

            $output .= '	</ul>
		  				</div>';

            // Create the carrousel Secondly
            $output .= '<div id="' . $carrouselID . '" class="wppsn-carrousel flexslider">
		  					<ul class="slides">';

            foreach ($allTitles as $i => $t)
            {

                $title = trim($t);
                $alt = trim($allAlts[$i]);
                $legend = trim($allLegends[$i]);
                $thumb = trim($allThumbs[$i]);
                $url = trim($allUrls[$i]);

                $output .= '<li>
								<img src="' . $url . '" title="' . $title . '" alt="' . $alt . '">
							</li>';

            }

            $output .= '	</ul>
		  				</div>';

        break;

        default: // List
            // Add Swipebox JS to the footer
            wp_enqueue_script('wppsn_frontend_swipebox_js');

            foreach ($allTitles as $i => $t)
            {

                $title = trim($t);
                $alt = trim($allAlts[$i]);
                $legend = trim($allLegends[$i]);
                $url = trim($allUrls[$i]);
                $id = md5($url);
                $download = trim($allDownlods[$i]);

                $downloadLink = "<a href='$url&download=1'>Download</a>";

                $output .= '<div class="wppsn-image wp-caption">
								<a href="javascript:void(0)" title="' . $title . '" class="wppsn-image-post-' . $post->ID . '">
									<img class="wp-image-" src="' . $url . '" alt="' . $alt . '" name="' . $title . '" id="'.$id.'"  onclick="openModel(this.id)"  style="cursor: pointer;" >
								</a>';


                                if ($legend != '' || $download == 'on')
                                {
                                    $output .= '	<p class="wp-caption-text">' . $legend . (($legend != '') ? ' - ' : '') . $downloadLink . '</p>';
                                }
                           

                $output .= "</div> <div id='myModal_$id' class='modal'><span class='close'>&times;</span><img class='modal-content' id='img_$id'><div id='caption_$id' class='caption' ></div></div>";

        
        

            }

        break;

    }

    return $output;

}

add_shortcode('wppsn-img-gallery', 'wppsn_shortcode_image_gallery');

/**
 * Define the shortcode 'wppsn-video-playlist'
 * @param  array $atts Array of attributes
 * @return html        HTML replacing the shortcode
 */
function wppsn_shortcode_video_playlist($atts)
{

    $output = '';
    // $uniqueString = md5( microtime( true ) );
    // $playerID = 'wppsn-video-playlist-player-' . $uniqueString;
    // usleep(1);
    // Add JS for loading Flowplayer to the footer
    wp_enqueue_script('wppsn_frontend_video_playlist_js');

    // Get Attributes
    extract(shortcode_atts(array(
        'titles' => '',
        'mp4s' => '',
        'splash' => ''
    ) , $atts));

    // Explode Strings to Arrays
    $allTitles = explode('||', $titles);
    $allMp4s = explode('||', $mp4s);
    $splash = trim($splash);

    $styleSplash = ($splash != '') ? ' style="background-image:url(' . $splash . ')"' : '';

    $output .= '<div class="wppsn-video-playlist-player-wrapper">
					<div class="is-splash wppsn-video-playlist-player"' . $styleSplash . '>
						<video>
							<source type="video/mp4" src="' . trim($allMp4s[0]) . '">
						</video>
						<div class="fp-playlist">';

    foreach ($allTitles as $i => $t)
    {
        $output .= '<a href="' . trim($allMp4s[$i]) . '">' . trim($t) . '</a>';
    }

    $output .= '		</div>
					</div>
				</div>';

    return $output;

}

add_shortcode('wppsn-videoplaylist', 'wppsn_shortcode_video_playlist');

