/**
 * Init Swipeboxes in the page
 */

jQuery(document).ready(function() {
	
	/*
	First, get all differents groups of images regarding there post ID
	Then, init swipebox on each group
	 */
	
	var groupsClasses = new Array();

	// Loop through standalone images
	jQuery( '.wppsn-image > a' ).each(function(){
		var linkClass = jQuery( this ).attr( 'class' );
		if ( jQuery.inArray( linkClass, groupsClasses ) === -1 ) {
			groupsClasses.push( linkClass );
		}
	});

	// Loop through gallery images
	jQuery( '.wppsn-gallery .gallery-icon a' ).each(function(){
		var linkClass = jQuery( this ).attr( 'class' );
		if ( jQuery.inArray( linkClass, groupsClasses ) === -1 ) {
			groupsClasses.push( linkClass );
		}
	});

	// Apply Swipebox to each group of images
	for ( var i in groupsClasses ) {
		jQuery( '.' + groupsClasses[i] ).swipebox();
	}

});