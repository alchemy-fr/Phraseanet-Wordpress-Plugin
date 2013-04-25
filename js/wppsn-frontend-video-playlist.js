/**
 * JS for videos Playlists
 */

jQuery(document).ready(function() {
	
	var playlistPlayerWrappers = jQuery( '.wppsn-video-playlist-player-wrapper' );

	if ( playlistPlayerWrappers.length > 0 ) {

		playlistPlayerWrappers.each(function(){
			var currentWrapper = jQuery( this );
			var currentPlayer = currentWrapper.find( '.wppsn-video-playlist-player' );
			var currentPlaylist = currentWrapper.find( '.fp-playlist' );

			// Load Player
			currentPlayer.flowplayer({
				swf: wppsnFlowPlayerVars.flashUrl
			});
			
			// Adjust Playlist CSS below the player
			playlistPlayerWrappers.css( 'padding-bottom', currentPlaylist.height() + 5 );
			currentPlaylist.css( 'bottom', - currentPlaylist.height() - 5 );

		});

	}

});