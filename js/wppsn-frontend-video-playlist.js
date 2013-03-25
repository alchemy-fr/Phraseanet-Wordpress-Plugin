/**
 * JS for videos Playlists
 * (Put playlists bellow the players)
 */

jQuery(document).ready(function() {
	
	var playlistPlayerWrappers = jQuery( '.wppsn-video-playlist-player-wrapper' );

	if ( playlistPlayerWrappers.length > 0 ) {

		playlistPlayerWrappers.each(function(){

			var currentPlaylist = jQuery(this).find( '.fp-playlist' );

			playlistPlayerWrappers.css( 'padding-bottom', currentPlaylist.height() );
			currentPlaylist.css( 'bottom', - currentPlaylist.height() );

		});

	}

});