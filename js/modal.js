/**
 * All the modal behavior
 */

/**
 * Global var used for storing constant values
 */
var wppsnGlobals = {
    ajaxUrl: '../../../../../wp-admin/admin-ajax.php'
};


/**
 * Create the Modal Main Object
 */
var WppsnModal = function() {

	this.bodyElt = jQuery('body');
    this.singleMediaListInfos = ''; // Current Single Media List infos in JSON
    this.imgGalleryMediaListInfos = ''; // Current Img Gallery Media List infos in JSON
    this.imgGallerySelectedImgIds = new Array();

};


/**
 * Modal Initialisation
 */
WppsnModal.prototype.init = function() {

    // Get initial HTML content
    jQuery.ajax({
        url: wppsnGlobals.ajaxUrl,
        data: { action: 'wppsn-get-modal-initial-content' },
        context: this,
        success: function( resp ) {
            this.bodyElt.html( resp );

            // If plugin is completely configured (the interface is shown, else an error paragraph is shown)
            if ( this.bodyElt.find( '#wppsn-sidebar' ).length > 0 ) {
            	this.prepareInitialContent();
            }
        },
        dataType: 'html'
    });

};


/**
 * Prepare initial Content
 */
WppsnModal.prototype.prepareInitialContent = function() {

    // Put in cache some DOM elements
    this.domPanSidebar = jQuery( '#wppsn-sidebar' );
    this.domMenu = this.domPanSidebar.find( '#wppsn-menu' );

    this.domMainPans = jQuery( '.wppsn-main-pan' );
    
    this.domPanSingleMedia = jQuery( '#wppsn-pan-single-media' );
    this.domSingleMediaTabs = this.domPanSingleMedia.find( '#wppsn-single-media-tabs' );
    this.domSingleMediaListMediasWrapper = this.domPanSingleMedia.find( '#wppsn-single-media-list-medias-wrapper' );
    this.domSingleMediaListMediasHeader = this.domPanSingleMedia.find( '#wppsn-single-media-list-medias-header' );
    this.domSingleMediaSearchInput = this.domSingleMediaListMediasHeader.find( '#wppsn-single-media-search' );
    this.domSingleMediaSearchType = this.domSingleMediaListMediasHeader.find( '.wppsn-single-media-search-type' );
    this.domSingleMediaRecordType = this.domSingleMediaListMediasHeader.find( '#wppsn-single-media-record-type' );
    this.domSingleMediaCounter = this.domSingleMediaListMediasHeader.find( '#wppsn-single-media-counter strong' );
    this.domSingleMediaListMedias = this.domPanSingleMedia.find( '#wppsn-single-media-list-medias' );
    this.domSingleMediaListPagination = this.domPanSingleMedia.find( '#wppsn-single-media-list-pagination' );
    this.domPanSingleMediaInsert = this.domPanSingleMedia.find( '#wppsn-single-media-insert-wrapper' );
    this.domSingleMediaInsertPans = this.domPanSingleMediaInsert.find( '.wppsn-single-media-insert-pan' );
    this.domSingleMediaInsertButtons = this.domPanSingleMediaInsert.find( '#wppsn-single-media-insert-buttons' );
    this.domPanSingleMediaPreview = this.domPanSingleMedia.find( '#wppsn-single-media-preview-wrapper' );
    this.domSingleMediaPreviewPans = this.domPanSingleMediaPreview.find( '.wppsn-single-media-preview-pan' );
    this.domSingleMediaListBasketsWrapper = this.domPanSingleMedia.find( '#wppsn-single-media-list-baskets-wrapper' );

    this.domPanImgGallery = jQuery( '#wppsn-pan-img-gallery' );
    this.domImgGalleryTabs = this.domPanImgGallery.find( '#wppsn-img-gallery-tabs' );
    this.domImgGalleryListMediasWrapper = this.domPanImgGallery.find( '#wppsn-img-gallery-list-medias-wrapper' );
    this.domImgGalleryListMediasHeader = this.domPanImgGallery.find( '#wppsn-img-gallery-list-medias-header' );
    this.domImgGallerySearchInput = this.domImgGalleryListMediasHeader.find( '#wppsn-img-gallery-search' );
    this.domImgGallerySearchType = this.domImgGalleryListMediasHeader.find( '.wppsn-img-gallery-search-type' );
    this.domImgGalleryCounter = this.domImgGalleryListMediasHeader.find( '#wppsn-img-gallery-counter strong' );
    this.domImgGalleryListMedias = this.domPanImgGallery.find( '#wppsn-img-gallery-list-medias' );
    this.domImgGalleryListPagination = this.domPanImgGallery.find( '#wppsn-img-gallery-list-pagination' );
    this.domImgGallerySelectedListWrapper = this.domPanImgGallery.find( '#wppsn-img-gallery-selected-list-wrapper' );
    this.domImgGallerySelectedList = this.domImgGallerySelectedListWrapper.find( '#wppsn-img-gallery-selected-list' );
    this.domImgGallerySelectedListButtons = this.domImgGallerySelectedListWrapper.find( '#wppsn-img-gallery-selected-list-buttons' );
    this.domPanImgGalleryPreview = this.domPanImgGallery.find( '#wppsn-img-gallery-preview-wrapper' );
    this.domPanImgGalleryPreview = this.domPanImgGallery.find( '#wppsn-img-gallery-preview-wrapper' );
    this.domPanImgGalleryCreateStep1 = this.domPanImgGallery.find( '#wppsn-img-gallery-create-gallery-step1' );
    this.domImgGalleryCreateStep1MediaList = this.domPanImgGalleryCreateStep1.find( '#wppsn-img-gallery-list-media-fields' );
    this.domPanImgGalleryCreateStep2 = this.domPanImgGallery.find( '#wppsn-img-gallery-create-gallery-step2' );
    this.domImgGalleryListBasketsWrapper = this.domPanImgGallery.find( '#wppsn-img-gallery-list-baskets-wrapper' );

    this.domPanVideoPlaylist = jQuery( '#wppsn-pan-video-playlist' );
    
    // Hide what should be hidden at first load
    this.domSingleMediaTabs.hide();
    this.domSingleMediaListBasketsWrapper.hide();
    this.domSingleMediaInsertPans.hide();
    this.domSingleMediaInsertButtons.hide();
    this.domPanSingleMediaPreview.hide();
    this.domSingleMediaPreviewPans.hide();

    this.domImgGallerySelectedListButtons.hide();
    this.domPanImgGallery.hide();
    this.domImgGalleryTabs.hide();
    this.domImgGalleryListBasketsWrapper.hide();
    this.domPanImgGalleryPreview.hide();
    this.domPanImgGalleryCreateStep1.hide();
    this.domPanImgGalleryCreateStep2.hide();

    // Init various events (buttons, search fields...)
    this.initMainMenuEvents();
    this.initSingleMediaPanEvents();
    this.initImgGalleryPanEvents();

    // Load the Single Media List
    this.getSingleMediaList();

};


/**
 * Init the Main menu links
 */
WppsnModal.prototype.initMainMenuEvents = function() {

	var _this = this;

	// [click] Main Menu Links
    this.domMenu.on( 'click', 'a', function(e){
    	var curLink = jQuery( this );
        var linkIdArray = curLink.attr( 'id' ).split( '-' );
        var requestedPan = jQuery( '#wppsn-pan-' + linkIdArray[2] + '-' + linkIdArray[3] );

        // Display Pan if not visible
        if ( !curLink.hasClass( 'current-menu' ) ) {
            _this.domMainPans.hide();
            _this.domMenu.find( 'a' ).removeClass( 'current-menu' );
            requestedPan.show();
            curLink.addClass( 'current-menu' );

            // Get Media List
            switch( linkIdArray[2] + '-' + linkIdArray[3] ) {

                case 'single-media':
                    _this.getSingleMediaList();
                    break;

                case 'img-gallery':
                    _this.getImgGalleryMediaList();
                    break;

            }
        }
        e.preventDefault();
    });

};


/**
 * Init the Single Media Pan events (buttons, search field...)
 */
WppsnModal.prototype.initSingleMediaPanEvents = function() {

	var _this = this;

	// [press ENTER] Search Field 
    this.domSingleMediaSearchInput.keypress(function(e) {
        var keycode = ( e.keyCode ? e.keyCode : e.which );
        if ( keycode == '13' ) {
            _this.getSingleMediaList();
        }
    });

    // [click] Search Type Radio Buttons : Documents OR Coverages
    this.domSingleMediaSearchType.on( 'change', function(e){
        _this.getSingleMediaList();
        e.preventDefault();
    });

    // [click] Record Type dropdown list
    this.domSingleMediaRecordType.on( 'change', function(e){
        _this.getSingleMediaList();
        e.preventDefault();
    });

    // [click] List Pagination 
    this.domSingleMediaListPagination.on( 'click', 'a', function(e){
        _this.getSingleMediaList( jQuery(this).text() );
        e.preventDefault();
    });

    // [click] Insert Pan "Details" button
    this.domPanSingleMediaInsert.find( '.media-details-button' ).on( 'click', function(e){
        if ( !_this.domPanSingleMediaPreview.is(':visible') ) {
            _this.showSingleMediaPreviewPan( jQuery(this).parents( '.wppsn-single-media-insert-pan' ).data( 'mediaInfos' ) );
        }
        e.preventDefault();
    });

    // [click] Preview Pan Close button
    this.domPanSingleMediaPreview.find( '.media-preview-close' ).on( 'click', function(e){
        _this.domPanSingleMediaPreview.hide();
        _this.domSingleMediaPreviewPans.hide();
        e.preventDefault();
    });

    // [click] Insert button
    this.domSingleMediaInsertButtons.find( 'a' ).on( 'click', function(e){
        _this.insertSingleMedia();
        e.preventDefault();
    });

};


/**
 * Get the Single Media List regarding current filters
 * @param {int} pageNb Page number of pagination
 */
WppsnModal.prototype.getSingleMediaList = function( pageNb ) {

    // If not already loading
    if ( !this.domSingleMediaListMedias.hasClass( 'list-loading' ) ) {

        // Loading animation
        this.domSingleMediaListMedias.empty().addClass( 'list-loading' );

        // Hide Insert and Preview Pans if opened
        this.domSingleMediaInsertPans.hide();
        this.domSingleMediaInsertButtons.hide();
        this.domPanSingleMediaPreview.hide();
        this.domSingleMediaPreviewPans.hide();

    	// Get current Filters (search query, search type, record type and page of pagination)
        var mediaFilters = {
            searchQuery: this.domSingleMediaSearchInput.val() || '',
            searchType: jQuery( '.wppsn-single-media-search-type:checked' ).val() || 0,
            recordType: this.domSingleMediaRecordType.val() || 'all',
            pageNb: ( typeof( pageNb ) == "undefined" ) ? 1 : pageNb
        };

        // Disable search types radio buttons and record types dropdown list
        this.domSingleMediaSearchType.attr( 'disabled', 'disabled' );
        this.domSingleMediaRecordType.attr( 'disabled', 'disabled' );

        // Request Media List Infos
        jQuery.ajax({
            url: wppsnGlobals.ajaxUrl,
            data: { action: 'wppsn-get-media-list', params: mediaFilters },
            context: this,
            success: function( resp ) {

                // Save response
                this.singleMediaListInfos = resp;

                // Prepare the list
                this.prepareSingleMediaList();

            },
            dataType: 'json'
        });

    }

};


/**
 * Prepare the Single Media List
 */
WppsnModal.prototype.prepareSingleMediaList = function() {

    // Remove pagination
    this.domSingleMediaListPagination.empty();

    // No results
    if ( this.singleMediaListInfos.s == 'no-results' ) {
        this.domSingleMediaListMedias
            .removeClass( 'list-loading' )
            .append( '<p class="wppsn-no-results">' + this.singleMediaListInfos.sMsg + '</p>' );
        this.domSingleMediaCounter.text( '0' );
    }
    // Error
    else if ( this.singleMediaListInfos.s == 'error' ) {
        this.domSingleMediaListMedias
            .removeClass( 'list-loading' )
            .append( '<p class="wppsn-error">' + this.singleMediaListInfos.sMsg + '</p>' );
        this.domSingleMediaCounter.text( '0' );
    }
    // Success
    else if ( this.singleMediaListInfos.s == 'success' ) {

        var _this = this;

    	// Update Media Counter
    	this.domSingleMediaCounter.text( this.singleMediaListInfos.total );

    	// Create the HTML Media List and add it to DOM
    	var mediaList = this.singleMediaListInfos.mediaList;
    	var mediaListElt = jQuery( '<ul class="media-list"></ul>' );

    	for ( var i in mediaList ) {

            var mediaEltInfos = mediaList[i];

            // Media Element "Details" button
            var mediaEltDetailsButton = jQuery( '<a href="" class="media-details-button button">' + wppsnModali18n.buttonDetails + '</a>' )
                                            .on( 'click', function(){
                                                // Click on "details" button open the sidebar insert Pan and the preview Pan
                                                var mInfos = jQuery( this ).parents( 'li' ).data( 'mediaInfos' );
                                                _this.showSingleMediaInsertInfos( mInfos );
                                                _this.showSingleMediaPreviewPan( mInfos );
                                                return false;
                                            });

            // Media Element HTML appended to list element
    		jQuery( '<li></li>' )
    			.data( 'mediaInfos', mediaEltInfos )
    			.append( jQuery('<div class="media-item"></div>')
    						.append( '<div class="media-thumb"><img src="' + mediaEltInfos.thumb + '"></div>' )
    						.append( '<p class="media-title">' + mediaEltInfos.title + '</p>' )
    						.append( jQuery( '<p class="media-buttons"></p>' ).append( mediaEltDetailsButton ) ) 
    			)
                .on( 'click', function(e){
                    // Click anywhere in the media item <li> open only the sidebar
                    _this.showSingleMediaInsertInfos( jQuery( this ).data( 'mediaInfos' ) );
                    e.preventDefault();
                })
                .appendTo( mediaListElt );

    	}

        // Remove old Media List and append the new one
    	this.domSingleMediaListMedias
            .removeClass( 'list-loading' )
            .empty()
            .append( mediaListElt );

        // Append Pagination
        this.domSingleMediaListPagination.append( this.singleMediaListInfos.pagination );

    }

    // Re-enable search type radio buttons and record types dropdown list
    this.domSingleMediaSearchType.removeAttr( 'disabled' );
    this.domSingleMediaRecordType.removeAttr( 'disabled' );


};


/**
 * Show Single Media Insert Infos Pan
 * @param {object} mediaInfos Media Infos to use for fullfill the fields
 */
WppsnModal.prototype.showSingleMediaInsertInfos = function( mediaInfos ) {

    // Use the right pan (one for each phraseaType of media)
    var insertPan = this.domPanSingleMediaInsert.find( '#wppsn-single-media-insert-' + mediaInfos.phraseaType );

    // Check if the opened pan is the one we need with the same media requested : if yes, do nothing
    if ( insertPan.is( ':visible' ) ) {
        var currentMediaInfos = insertPan.data( 'mediaInfos' );
        if ( currentMediaInfos.id == mediaInfos.id ) { return; }
    }

    // Hide all media pans
    this.domSingleMediaInsertPans.hide();

    // Store media Infos in the current pan
    insertPan.data( 'mediaInfos', mediaInfos );

    // Fullfill the pan's fields
    switch ( mediaInfos.phraseaType ) {

        case 'image':

            // Thumb
            insertPan
                .find( '#wppsn-single-media-insert-image-thumb' )
                .empty()
                .append( '<img src="' + mediaInfos.thumb + '">' );

            // Title
            insertPan
                .find( '#wppsn-single-media-insert-image-title' )
                .val( mediaInfos.title );

            break;

        case 'video':

            // Thumb
            insertPan
                .find( '#wppsn-single-media-insert-video-thumb' )
                .empty()
                .append( '<img src="' + mediaInfos.thumb + '">' );

            // Title
            insertPan
                .find( '#wppsn-single-media-insert-video-title' )
                .val( mediaInfos.title );

            break;

    }

    // Show Pan and button
    insertPan.show();
    this.domSingleMediaInsertButtons.show();

};


/**
 * Show Single Media Preview Pan
 * @param {object} mediaInfos Media Infos to use for fullfill the fields
 */
WppsnModal.prototype.showSingleMediaPreviewPan = function( mediaInfos ) {

    // Hide all preview Pans
    this.domSingleMediaPreviewPans.hide();

    // Use the right pan (one for each phraseaType of media)
    var previewPan = this.domPanSingleMediaPreview.find( '#wppsn-single-media-preview-' + mediaInfos.phraseaType );

    // Show Pan
    this.domPanSingleMediaPreview.show();
    previewPan.show();

    // Fullfill the pan's fields
    
    previewPan
        .find( '.wppsn-media-preview-title' )
        .text( mediaInfos.title );

    switch ( mediaInfos.phraseaType ) {

        case 'image':
            
            previewPan
                .find( '.wppsn-media-preview-thumb' )
                .empty()
                .append( '<img src="' + mediaInfos.preview.url + '">' );

            break;

        case 'video':

            // Is there a preview ? then build the video player with all its sources
            if ( typeof( mediaInfos.preview.nopreview ) == "undefined" ) {

                // Show player container
                previewPan
                    .find( '#wppsn-single-media-preview-video-player-wrapper' )
                    .show();

                // Hide Video Thumbnail
                previewPan
                    .find( '.wppsn-media-preview-thumb' )
                    .hide();

                var videoPlayer = jQuery( '#wppsn-single-media-preview-video-player' );

                // Destroy and reInit the Player
                videoPlayer.jPlayer( "destroy" );
                 
                videoPlayer.jPlayer({
                    ready: function() { 
                        jQuery( this )
                            .jPlayer( "setMedia", {
                                m4v: mediaInfos.preview.h264
                            })
                            .jPlayer( "play" );
                    },
                    cssSelectorAncestor: "#wppsn-single-media-preview-video-player-wrapper",
                    swfPath: "../../libs/jplayer/Jplayer.swf",
                    supplied: "m4v",
                    solution: "html,flash"
                });

            }
            // No preview
            else {

                // Hide video player container
                previewPan
                    .find( '#wppsn-single-media-preview-video-player-wrapper' )
                    .hide();

                // Show Video thumbnail
                previewPan
                    .find( '.wppsn-media-preview-thumb' )
                    .empty()
                    .append( '<img src="' + mediaInfos.preview.nopreview + '">' )
                    .show();
            }

            break;       

    }

};


/**
 * Insert the Single Media Shortcode into the TinyMCE
 */
WppsnModal.prototype.insertSingleMedia = function() {

    // Get the currently visible Insert Pan and so the media
    var currentInsertPan = this.domPanSingleMediaInsert.find( '.wppsn-single-media-insert-pan:visible' );
    var currentMedia = currentInsertPan.attr( 'id' ).split( '-' )[4];

    // Get The original media Infos
    var mediaInfos = currentInsertPan.data( 'mediaInfos' );

    // Build the media shortcode
    switch( currentMedia ) {

        case 'image':

            // Begin Shortcode Output
            var output = '[wppsn-image ';

            // Title
            output += 'title="' + currentInsertPan.find( '#wppsn-single-media-insert-image-title' ).val().replace( /\"/g, '&quot;' ).replace( /\[/g, '' ).replace( /\]/g, '' ) + '" ';

            // Alt Text
            output += 'alt="' + currentInsertPan.find( '#wppsn-single-media-insert-image-alt' ).val().replace( /\"/g, '&quot;' ).replace( /\[/g, '' ).replace( /\]/g, '' ) + '" ';

            // Legend
            output += 'legend="' + currentInsertPan.find( '#wppsn-single-media-insert-image-legend' ).val().replace( /\"/g, '&quot;' ).replace( /\[/g, '' ).replace( /\]/g, '' ) + '" ';

            // Download Link
            output += 'download="' + currentInsertPan.find( '.wppsn-single-media-insert-image-download-link:checked' ).val() + '" ';

            // Url
            output += 'url="' + mediaInfos.preview.url + '"';

            // Close Shortcode
            output += ']';

            break;

        case 'video':

            // Begin Shortcode Output
            var output = '[wppsn-video ';

            // Title
            output += 'title="' + currentInsertPan.find( '#wppsn-single-media-insert-video-title' ).val().replace( /\"/g, '&quot;' ).replace( /\[/g, '' ).replace( /\]/g, '' ) + '" ';

            output += 'h264="' + mediaInfos.preview.h264 + '" ';

            // Close Shortcode
            output += ']';

            break;

        default:

            output = '';

            break;

    }

    // Insert Shortcode into TinyMCE
    wppsnDialog.insert( wppsnDialog.local_ed, output );

};


/**
 * Init the Img Gallery Pan events (buttons, search field...)
 */
WppsnModal.prototype.initImgGalleryPanEvents = function() {

    var _this = this;

    // [press ENTER] Search Field 
    this.domImgGallerySearchInput.keypress(function(e) {
        var keycode = ( e.keyCode ? e.keyCode : e.which );
        if ( keycode == '13' ) {
            _this.getImgGalleryMediaList();
        }
    });

    // [click] Search Type Radio Buttons : Documents OR Coverages
    this.domImgGallerySearchType.on( 'change', function(e){
        _this.getImgGalleryMediaList();
        e.preventDefault();
    });

    // [click] List Pagination 
    this.domImgGalleryListPagination.on( 'click', 'a', function(e){
        _this.getImgGalleryMediaList( jQuery(this).text() );
        e.preventDefault();
    });

    // [click] Preview Pan Close button
    this.domPanImgGalleryPreview.find( '.media-preview-close' ).on( 'click', function(e){
        _this.domPanImgGalleryPreview.hide();
        e.preventDefault();
    });

    // [click] Selected Images list delete all
    this.domImgGallerySelectedListWrapper.find( '#wppsn-img-gallery-selection-delete-all a' ).on( 'click', function(e){
        _this.domImgGallerySelectedList.find( '.selected-img-delete' ).each(function(){
            jQuery( this ).trigger( 'click' );
        });
        e.preventDefault();
    })

    // [click] Create Image Gallery Step 1 button
    this.domImgGallerySelectedListButtons.find( 'a' ).on( 'click', function(e){
        _this.createImgGalleryStep1();
        e.preventDefault();
    });

    // [click] Create Image Gallery Step 1 Close Button
    this.domPanImgGalleryCreateStep1.find( '.create-gallery-step1-close' ).on( 'click', function(e){
        _this.domPanImgGalleryCreateStep1.hide();
        e.preventDefault();
    });

    // [click] Create Image Gallery Step 1 to step 2 button
    this.domPanImgGalleryCreateStep1.find( '.create-gallery-step1-next' ).on( 'click', function(e){
        _this.createImgGalleryStep2();
        e.preventDefault();
    });

    // [click] Create Image Gallery Step 2 Close Button
    this.domPanImgGalleryCreateStep2.find( '.create-gallery-step2-close' ).on( 'click', function(e){
        _this.domPanImgGalleryCreateStep2.hide();
        e.preventDefault();
    });

    // [click] Insert Image Gallery Buttons
    this.domPanImgGalleryCreateStep2.on( 'click', '.button-primary', function(e){
        _this.insertImgGallery( jQuery( this ).parents( 'li' ).attr( 'id' ).split( '-' )[4] );
        e.preventDefault();
    });

};


/**
 * Get the Img Gallery Media List regarding current filters
 * @param {int} pageNb Page number of pagination
 */
WppsnModal.prototype.getImgGalleryMediaList = function( pageNb ) {

    // If not already loading
    if ( !this.domImgGalleryListMedias.hasClass( 'list-loading' ) ) {

        // Loading animation
        this.domImgGalleryListMedias.empty().addClass( 'list-loading' );

        // Hide Pans : preview, create gallery step 1 and 2
        this.domPanImgGalleryPreview.hide();
        this.domPanImgGalleryCreateStep1.hide();
        this.domPanImgGalleryCreateStep2.hide();

        // Get current Filters (search query, search type and page of pagination)
        var mediaFilters = {
            searchQuery: this.domImgGallerySearchInput.val() || '',
            searchType: jQuery( '.wppsn-img-gallery-search-type:checked' ).val() || 0,
            recordType: 'image',
            pageNb: ( typeof( pageNb ) == "undefined" ) ? 1 : pageNb
        };

        // Disable search types radio buttons
        this.domSingleMediaSearchType.attr( 'disabled', 'disabled' );

        // Request Media List Infos
        jQuery.ajax({
            url: wppsnGlobals.ajaxUrl,
            data: { action: 'wppsn-get-media-list', params: mediaFilters },
            context: this,
            success: function( resp ) {

                // Save response
                this.imgGalleryMediaListInfos = resp;

                // Prepare the list
                this.prepareImgGalleryMediaList();

            },
            dataType: 'json'
        });

    }

};


/**
 * Prepare the Img Gallery Media List
 */
WppsnModal.prototype.prepareImgGalleryMediaList = function() {

    // Remove pagination
    this.domImgGalleryListPagination.empty();

    // No results
    if ( this.imgGalleryMediaListInfos.s == 'no-results' ) {
        this.domImgGalleryListMedias
            .removeClass( 'list-loading' )
            .append( '<p class="wppsn-no-results">' + this.imgGalleryMediaListInfos.sMsg + '</p>' );
        this.domImgGalleryCounter.text( '0' );
    }
    // Error
    else if ( this.imgGalleryMediaListInfos.s == 'error' ) {
        this.domImgGalleryListMedias
            .removeClass( 'list-loading' )
            .append( '<p class="wppsn-error">' + this.imgGalleryMediaListInfos.sMsg + '</p>' );
        this.domImgGalleryCounter.text( '0' );
    }
    // Success
    else if ( this.imgGalleryMediaListInfos.s == 'success' ) {

        var _this = this;

        // Update Media Counter
        this.domImgGalleryCounter.text( this.imgGalleryMediaListInfos.total );

        // Create the HTML Media List and add it to DOM
        var mediaList = this.imgGalleryMediaListInfos.mediaList;
        var mediaListElt = jQuery( '<ul class="media-list"></ul>' );

        for ( var i in mediaList ) {

            var mediaEltInfos = mediaList[i];
            var mediaIsSelected = ( jQuery.inArray( mediaEltInfos.id, this.imgGallerySelectedImgIds ) != -1 ) ? true : false;

            var mediaEltDetailsButton = jQuery( '<a href="" class="media-details-button button">' + wppsnModali18n.buttonDetails + '</a>' )
                                            .on( 'click', function(){
                                                // Click on "details" button open the the preview Pan
                                                _this.showImgGalleryMediaPreviewPan( jQuery( this ).parents( 'li' ).data( 'mediaInfos' ) );
                                                return false;
                                            });

            var mediaEltSelectButton = jQuery( '<a href="" class="media-select-button button-primary">' + wppsnModali18n.buttonSelect + '</a> ' )
	                                        .on( 'click', function(){
	                                            // Add this img to the selection
	                                            _this.addToImgGallerySelection( jQuery( this ).parents( 'li' ).data( 'mediaInfos' ) );
	                                            return false;
	                                        });

            if ( mediaIsSelected ) {
                mediaEltSelectButton.css( 'display', 'none' );
            }

            var mediaEltUnselectButton = jQuery( '<a href="" class="media-unselect-button button-primary">' + wppsnModali18n.buttonUnselect + '</a> ' )
                                            .on( 'click', function(){
                                                // Remove this img from the selection
                                                _this.deleteFromImgGallerySelection( jQuery( this ).parents( 'li' ).data( 'mediaInfos' ) );
                                                return false;
                                            });

            if ( !mediaIsSelected ) {
                mediaEltUnselectButton.css( 'display', 'none' );
            }

            // Media Element HTML
            jQuery( '<li class="media-item-' + mediaEltInfos.id + '"></li>' )
                .data( 'mediaInfos', mediaEltInfos )
                .append( jQuery('<div class="media-item"></div>')
                            .append( '<div class="media-thumb"><img src="' + mediaEltInfos.thumb + '"></div>' )
                            .append( '<p class="media-title">' + mediaEltInfos.title + '</p>' )
                            .append(
                            	jQuery( '<p class="media-buttons"></p>' )
                                    .append( mediaEltDetailsButton )
                                    .append( mediaEltSelectButton )
                                    .append( mediaEltUnselectButton )
                            ) 
                )
                .appendTo( mediaListElt );

        }

        // Remove old Media List and append the new one
        this.domImgGalleryListMedias
            .removeClass( 'list-loading' )
            .empty()
            .append( mediaListElt );

        // Append Pagination
        this.domImgGalleryListPagination.append( this.imgGalleryMediaListInfos.pagination );

    }

    // Re-enable search type radio buttons
    this.domSingleMediaSearchType.removeAttr( 'disabled' );


};


/**
 * Show Img Gallery Media Preview Pan
 * @param {object} mediaInfos Media Infos to use for fullfill the fields
 */
WppsnModal.prototype.showImgGalleryMediaPreviewPan = function( mediaInfos ) {

    // Fullfill the pan's fields
    this.domPanImgGalleryPreview
        .find( '.wppsn-media-preview-title' )
        .text( mediaInfos.title );
    this.domPanImgGalleryPreview
        .find( '.wppsn-media-preview-thumb' )
        .empty()
        .append( '<img src="' + mediaInfos.preview.url + '">' );

    // Show Pan
    this.domPanImgGalleryPreview.show();

};


/**
 * Add an Image to the Image Gallery Selection
 * @param {object} mediaInfos Media Infos
 */
WppsnModal.prototype.addToImgGallerySelection = function( mediaInfos ) {

	var _this = this;

	// Check if the image is not already selected
    if ( jQuery.inArray( mediaInfos.id, this.imgGallerySelectedImgIds ) == -1 ) {

		
		var imgSelectedDetailsLink = jQuery( '<a href="" class="selected-img-details">' + wppsnModali18n.linkDetails + '</a>' )
										.on( 'click', function(e){
											_this.showImgGalleryMediaPreviewPan( mediaInfos );
											return false;
										});

		var imgSelectedDeleteLink = jQuery( '<a href="" class="selected-img-delete">' + wppsnModali18n.linkDelete + '</a>' )
										.on( 'click', function(e){
											_this.deleteFromImgGallerySelection( mediaInfos );
											return false;
										});

		var imgSelected = jQuery( '<li class="selected-img-' + mediaInfos.id + ' clearfix"></li>' )
							.data( 'mediaInfos', mediaInfos )
							.append( '<div class="selected-img-thumb"><img src="' + mediaInfos.thumb + '"></div>' )
							.append( '<p class="selected-img-title">' + mediaInfos.title + '</p>' )
							.append(
								jQuery( '<p class="selected-img-buttons"></p>' )
									.append( imgSelectedDetailsLink )
									.append( imgSelectedDeleteLink )
							);

        // Add image in the list and in the array of Img Ids
        this.domImgGallerySelectedList.find( 'ul' ).append( imgSelected );
        this.imgGallerySelectedImgIds.push( mediaInfos.id );

        // Switch "select" and "unselect" buttons in the main media list
        var mediaItem = this.domImgGalleryListMedias.find( '.media-item-' + mediaInfos.id );
        mediaItem.find( '.media-select-button' ).hide();
        mediaItem.find( '.media-unselect-button' ).show();

        // Nb of images in the selection
        var nbImagesSelected = this.imgGallerySelectedImgIds.length;
        this.domImgGallerySelectedListWrapper.find( '#wppsn-img-gallery-selection-counter' ).text( nbImagesSelected );

		// First image to add ? then hide the "no selection" message
		if ( nbImagesSelected == 1 ) {
			this.domImgGallerySelectedList.find( '#wppsn-img-gallery-no-selection' ).hide();
		}

        // Show the Create gallery button when at least 2 images are selected
        if ( nbImagesSelected > 1 ) {
            this.domImgGallerySelectedListButtons.show();
        }

	}

}


/**
 * Remove an Image from the Image Gallery Selection
 * @param {object} mediaInfos Media Infos
 */
WppsnModal.prototype.deleteFromImgGallerySelection = function( mediaInfos ) {

	// Remove image from the list and from the array of Img Ids
    var removeID = mediaInfos.id; 
	this.domImgGallerySelectedList.find( '.selected-img-' + removeID ).remove();
    this.imgGallerySelectedImgIds = jQuery.grep( this.imgGallerySelectedImgIds, function( v ){
        return v != removeID;
    });

    // Switch "select" and "unselect" buttons in the main media list
    var mediaItem = this.domImgGalleryListMedias.find( '.media-item-' + mediaInfos.id );
    mediaItem.find( '.media-select-button' ).show();
    mediaItem.find( '.media-unselect-button' ).hide();

    // Nb of images left in the selection
    var nbImagesSelected = this.imgGallerySelectedImgIds.length;
    this.domImgGallerySelectedListWrapper.find( '#wppsn-img-gallery-selection-counter' ).text( nbImagesSelected );

    // If less than 2 images in the selection, hide the Create gallery button
    if ( nbImagesSelected < 2 ) {
        this.domImgGallerySelectedListButtons.hide();
    }

	// If no more images in the selection, display the msg
	if ( nbImagesSelected == 0 ) {
		this.domImgGallerySelectedList.find( '#wppsn-img-gallery-no-selection' ).show();
	}

}


/**
 * Prepare the Images Gallery creation step 1
 */
WppsnModal.prototype.createImgGalleryStep1 = function() {

    var mediaList = jQuery( '<ul></ul>' );

    // Show Pan
    this.domPanImgGalleryCreateStep1.show();

    // List Media
    this.domImgGallerySelectedList.find( 'li' ).each(function(){

        // Get Media Infos
        var mediaInfos = jQuery( this ).data( 'mediaInfos' );

        // Build the li element
        var mediaElt = jQuery( '<li class="clearfix"></li>' )
                        .data( 'mediaInfos', mediaInfos )
                        .append( '<div class="media-thumb"><img src="' + mediaInfos.thumb + '"></div>' )
                        .append(
                            jQuery( '<div class="media-fields"></div>' )
                                .append(
                                    jQuery( '<p></p>' )
                                        .append( '<label>' + wppsnModali18n.mediaTitleLabel + '</label>' )
                                        .append( '<input type="text" name="wppsn-create-img-gallery-img-title" value="' + mediaInfos.title + '" class="wppsn-create-img-gallery-img-title input-text">' )
                                )
                                .append(
                                    jQuery( '<p></p>' )
                                        .append( '<label>' + wppsnModali18n.mediaAltTextLabel + '</label>' )
                                        .append( '<input type="text" name="wppsn-create-img-gallery-img-alt-text" value="" class="wppsn-create-img-gallery-img-alt-text input-text">' )
                                )
                                .append(
                                    jQuery( '<p></p>' )
                                        .append( '<label>' + wppsnModali18n.mediaLegendLabel + '</label>' )
                                        .append( '<input type="text" name="wppsn-create-img-gallery-img-legend" value="" class="wppsn-create-img-gallery-img-legend input-text">' )
                                )
                        );

        // Add media element in the UL
        mediaList.append( mediaElt );

    });

    // Add UL in the DOM
    this.domImgGalleryCreateStep1MediaList
        .empty()
        .append( mediaList );

}


/**
 * Prepare the Image Gallery creation Step 2
 */
WppsnModal.prototype.createImgGalleryStep2 = function() {

    // Show Pan
    this.domPanImgGalleryCreateStep2.show();

}


/**
 * Insert the Image Gallery Shortcode into the TinyMCE
 * @param {string} displayStyle Style of gallery display (list, grid, carrousel)
 */
WppsnModal.prototype.insertImgGallery = function( displayStyle ) {

    var displayStyle = ( typeof( displayStyle ) == "undefined" ) ? 'list' : displayStyle;

    var allTitles = new Array();
    var allAlts = new Array();
    var allLegends = new Array();
    var allThumbs = new Array();
    var allUrls = new Array();

    // Get All media Infos
    this.domImgGalleryCreateStep1MediaList.find( 'li' ).each(function(){

        var currentMediaElt = jQuery( this );
        var currentMediaInfos = currentMediaElt.data( 'mediaInfos' );

        allTitles.push( currentMediaElt.find( '.wppsn-create-img-gallery-img-title' ).val().replace( /\"/g, '&quot;' ).replace( /\[/g, '' ).replace( /\]/g, '' ) );
        allAlts.push( currentMediaElt.find( '.wppsn-create-img-gallery-img-alt-text' ).val().replace( /\"/g, '&quot;' ).replace( /\[/g, '' ).replace( /\]/g, '' ) );
        allLegends.push( currentMediaElt.find( '.wppsn-create-img-gallery-img-legend' ).val().replace( /\"/g, '&quot;' ).replace( /\[/g, '' ).replace( /\]/g, '' ) );
        allThumbs.push( currentMediaInfos.thumb );
        allUrls.push( currentMediaInfos.preview.url );

    });

    // Build the shortcode
    var output = '[wppsn-img-gallery display="' + displayStyle + '" ';

    // Titles
    output += 'titles="' + allTitles.join( ' || ' ) + '" ';

    // Alt Texts
    output += 'alts="' + allAlts.join( ' || ' ) + '" ';

    // Legend
    output += 'legends="' + allLegends.join( ' || ' ) + '" ';

    // Thumbs
    output += 'thumbs="' + allThumbs.join( ' || ' ) + '" ';

    // Urls
    output += 'urls="' + allUrls.join( ' || ' ) + '" ';

    // Close shortcode
    output += ']';

    // Insert Shortcode into TinyMCE
    wppsnDialog.insert( wppsnDialog.local_ed, output );

}


/**
 * On DOM Ready
 */
jQuery(document).ready(function($) {
	
	var wppsnModal = new WppsnModal();
    wppsnModal.init();

});