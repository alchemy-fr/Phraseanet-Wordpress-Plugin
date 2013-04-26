/**
 * JS for Single Videos
 */

jQuery(document).ready(function() {
	
	var videoPlayers = jQuery( '.wppsn-video-player' );

	if ( videoPlayers.length > 0 ) {

		videoPlayers.each(function(){
			
			// Load Player
			jQuery( this ).flowplayer({
				swf: wppsnFlowPlayerVars.flashUrl
			});
			
		});

	}

});