/**
 * Init JPlayers in the page
 */

jQuery(document).ready(function($) {
	
	var wppsnPlayers = $( '.wppsn-video-player-wrapper' );

	if ( wppsnPlayers.length > 0 ) {

		wppsnPlayers.each(function(){

			var currentPlayer = $( this );
			var fileFormats = new Array();
			var fileUrls = {};

			// Get ID of the player container and the player
			var containerID = '#' + currentPlayer.attr( 'id' );
			var playerID = '#' + currentPlayer.find( '.jp-jplayer' ).attr( 'id' );

			// Get h264 file url
			var fileH264 = currentPlayer.find( '.jp-file-h264' );

			if ( fileH264.length > 0 ) {
				fileFormats.push( 'm4v' );
				fileUrls.m4v = fileH264.text();
			}

			$( playerID ).jPlayer({
	            ready: function() { 
	                $( this ).jPlayer( "setMedia", fileUrls );
	            },
	            cssSelectorAncestor: containerID,
	            swfPath: wppsnGlobalVars.jplayerSwfUrl,
	            supplied: fileFormats.join( ',' ),
	            solution: "html,flash"
	        });

		});

	}

});