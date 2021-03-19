<?php
/**
 * Initial HTML Content of the Modal
 */


// Check if Client ID / Secret / Token / Base URL are set
$wppsn_options = get_option( 'wppsn_options' );

if ( isset( $wppsn_options['client_base_url'] ) && $wppsn_options['client_base_url'] != ''
	&& isset( $wppsn_options['client_id'] ) && $wppsn_options['client_id'] != ''
	&& isset( $wppsn_options['client_secret'] ) && $wppsn_options['client_secret'] != ''
	&& isset( $wppsn_options['client_token'] ) && $wppsn_options['client_token'] != '' ) :

	?>


	<script type="text/javascript">
	/**
	 * Internationalisation for the Modal JS
	 */
	var wppsnModali18n = {
		buttonDetails: '<?php _e( 'Details', 'wp-phraseanet' ); ?>',
		buttonSelect:  '<?php _e( 'Select', 'wp-phraseanet' ); ?>',
		buttonUnselect:  '<?php _e( 'Unselect', 'wp-phraseanet' ); ?>',
		linkDetails: '<?php _e( 'details', 'wp-phraseanet' ); ?>',
		linkDelete: '<?php _e( 'remove', 'wp-phraseanet' ); ?>',
		mediaTitleLabel: '<?php _e( 'Title', 'wp-phraseanet' ); ?>',
		mediaAltTextLabel: '<?php _e( 'Alternate Text', 'wp-phraseanet' ); ?>',
		mediaLegendLabel: '<?php _e( 'Legend', 'wp-phraseanet' ); ?>'
	};

	/**
	 * True if WP is 3.5+ : say if we can set Featured Image via wp.media functions
	 */
	var wppsnCanSetFeaturedImageAutomatically = <?php echo ( version_compare( get_bloginfo( 'version' ), '3.5.0' ) != -1 ) ? 'true' : 'false'; ?>;
	</script>


	<div id="wppsn-sidebar">


	<div id="wppsn-menu"> 



	
	<div id="" style="width: 100%;position: fixed;z-index: 100;left: 245px; grid-row: 1 / span 2;display: inline-flex;">
	
	<div class="newButton3" id="button-3"><div id="circle"></div>
	<a style=" grid-row: 1 / span 2;" href="" onclick="selectedOption(this.id)" id="wppsn-menu-medias" class="page-title-action wppsn-menu-item current-menu"><?php _e( 'Insert a Media', 'wp-phraseanet' ) ?></a>
</div>

<div class="newButton3" id="button-3"><div id="circle"></div>
<a style=" grid-row: 1 / span 2;" href="" onclick="selectedOption(this.id)" id="wppsn-menu-images" class="page-title-action wppsn-menu-item"><?php _e( 'Create an Image Gallery', 'wp-phraseanet' ) ?></a>
</div>

<div class="newButton3" id="button-3"><div id="circle"></div>
<a style=" grid-row: 1 / span 2;" href="" onclick="selectedOption(this.id)" id="wppsn-menu-videos" class="page-title-action wppsn-menu-item"><?php _e( 'Create an Audio/Video Playlist', 'wp-phraseanet' ) ?></a>
</div>
	
			
			
			
			
	
		
		</div>
	
	
		<div id="select_facets">
	
		</div>

<div data-role="main" class="ui-content">
		<div id="load_facets" data-role="collapsible">

		<div id="loading" class="wppsn-media-list list-loading"></div>

		</div>

</div>

	
	</div>

	

	
	
	
	</div>


	
	<div id="wppsn-main" style="margin-top: 4%;">



		<div id="wppsn-pan-medias" class="wppsn-main-pan">

		

			<div style="background-color:#464646;color:white" class="wppsn-main-pan-header">
			
			


			<h1 style="color:white" ><?php _e( 'Insert a Media', 'wp-phraseanet' ); ?></h1>
			
				

				<h2 class="wppsn-main-pan-tabs">
					<a href="" class="wppsn-main-pan-tab-media"><?php _e( 'Medias', 'wp-phraseanet' ); ?></a>
					<a href="" class="wppsn-main-pan-tab-basket"><?php _e( 'Baskets', 'wp-phraseanet' ); ?></a>
				</h2>

			</div>

			<div class="wppsn-media-list-wrapper">

				<div class="wppsn-media-list-header">
					
					<input type="text" name="wppsn-search-field" class="search wppsn-search-field" value="" placeholder="<?php _e( 'Search', 'wp-phraseanet' ); ?>">

					<label>
						<input type="radio" name="wppsn-single-media-search-type" class="wppsn-search-type" value="0" checked="checked">
						<span><?php _e( 'Documents', 'wp-phraseanet' ); ?></span>
					</label>
					<label>
						<input type="radio" name="wppsn-single-media-search-type" class="wppsn-search-type" value="1">
						<span><?php _e( 'Stories', 'wp-phraseanet' ); ?></span>
					</label>

					<p class="clearfix">
						<span class="wppsn-media-counter"><strong>0</strong> <?php _e( 'Medias', 'wp-phraseanet' ); ?></span>
						<select name="wppsn-single-media-record-type" class="wppsn-record-type">
							<option value="all"><?php _e( 'All', 'wp-phraseanet' ); ?></option>
							<option value="image"><?php _e( 'Images', 'wp-phraseanet' ); ?></option>
							<option value="video"><?php _e( 'Videos', 'wp-phraseanet' ); ?></option>
							<option value="audio"><?php _e( 'Audio', 'wp-phraseanet' ); ?></option>
							<option value="document"><?php _e( 'Documents', 'wp-phraseanet' ); ?></option>
							<option value="flash"><?php _e( 'Flash', 'wp-phraseanet' ); ?></option>
						</select>
					</p>
				</div> 
				
				<div class="wppsn-media-list"></div>

				<div class="wppsn-media-list-pagination"></div>

			</div>

			<div class="wppsn-baskets-wrapper"></div>

			<div id="wppsn-single-media-insert-wrapper">

				<div id="wppsn-single-media-insert-image" class="wppsn-single-media-insert-pan">
					
					<h2><?php _e( 'Insertion informations', 'wp-phraseanet' ); ?></h2>

					<p id="wppsn-single-media-insert-image-buttons">
						<!-- <a href="" class="media-details-button button">&lt; <?php _e( 'Details', 'wp-phraseanet' ); ?></a> -->

						<div class="newButton" id="button-2"><div id="slide"></div><a class="media-details-button xbutton" href="" >&lt; <?php _e( 'Details', 'wp-phraseanet' ); ?></a></div>
					
					</p>

					<div id="wppsn-single-media-insert-image-thumb"></div>

					<div class="wppsn-set-featured-image-wrapper">


						<div id="single_media_subdef">
						
					
						
						</div>	

						<br>


						<a href="" class="wppsn-set-featured-image button"><?php _e( 'Set as Featured image', 'wp-phraseanet' ); ?></a>
						<span class="wppsn-loader visuallyhidden"></span>
						<p class="wppsn-error visuallyhidden"><?php _e( 'There was a problem when adding the image in Media Library.', 'wp-phraseanet' ); ?></p>
						<p class="wppsn-success-partial visuallyhidden"><?php _e( 'The image has been added to the Media Library with success.<br><br><strong>Note :</strong> since you are using a Wordpress version inferior than 3.5.0, we can\'t set the image as Featured automatically. You may do this by the traditionnal way, in the widget on the sidebar of the post form, choosing the added image in your Media Library.', 'wp-phraseanet' ); ?></p>
						<p class="wppsn-success visuallyhidden"><?php _e( 'The image has been added to the Media Library and set as a Featured Image with success.', 'wp-phraseanet' ); ?></p>
					</div>

					<p>
						<label for="wppsn-single-media-insert-image-title"><?php _e( 'Title', 'wp-phraseanet' ); ?></label>
						<input type="text" name="wppsn-single-media-insert-image-title" id="wppsn-single-media-insert-image-title" class="input-text">
					</p>

					<p>
						<label for="wppsn-single-media-insert-image-alt"><?php _e( 'Alternate text', 'wp-phraseanet' ); ?></label>
						<input type="text" name="wppsn-single-media-insert-image-alt" id="wppsn-single-media-insert-image-alt" class="input-text">
					</p>

					<p>
						<label for="wppsn-single-media-insert-image-legend"><?php _e( 'Legend', 'wp-phraseanet' ); ?></label>
						<textarea name="wppsn-single-media-insert-image-legend" id="wppsn-single-media-insert-image-legend" rows="5" class="input-text"></textarea>
					</p>

					<p>
						<label><?php _e( 'Download setting', 'wp-phraseanet' ); ?></label>
						<label class="input-radio">
							<input type="radio" name="wppsn-single-media-insert-image-download-link" class="wppsn-single-media-insert-image-download-link" value="0" checked="checked">
							<span><?php _e( 'Without download link', 'wp-phraseanet' ); ?></span>
						</label>
						<label class="input-radio">
							<input type="radio" name="wppsn-single-media-insert-image-download-link" class="wppsn-single-media-insert-image-download-link" value="1">
							<span><?php _e( 'With download link', 'wp-phraseanet' ); ?></span>
						</label>
					</p>

				</div>

				<div id="wppsn-single-media-insert-video" class="wppsn-single-media-insert-pan">
					
					<h2><?php _e( 'Insertion informations', 'wp-phraseanet' ); ?></h2>

					<p id="wppsn-single-media-insert-video-buttons">
						<!-- <a href="" class="media-details-button button">&lt; <?php //_e( 'Details', 'wp-phraseanet' ); ?></a> -->
						<div class="newButton" id="button-2"><div id="slide"></div><a class="media-details-button xbutton" href="" >&lt; <?php _e( 'Details', 'wp-phraseanet' ); ?></a></div>
					</p>

					<div id="wppsn-single-media-insert-video-thumb"></div>




					<div class="wppsn-set-featured-image-wrapper">


					<div id="single_media_subdef_video">
						
						<select></select>
						
						</div>	

						<br>


						<a href="" class="wppsn-set-featured-image button"><?php _e( 'Set as Featured image', 'wp-phraseanet' ); ?></a>
						<span class="wppsn-loader visuallyhidden"></span>
						<p class="wppsn-error visuallyhidden"><?php _e( 'There was a problem when adding the image in Media Library.', 'wp-phraseanet' ); ?></p>
						<p class="wppsn-success-partial visuallyhidden"><?php _e( 'The image has been added to the Media Library with success.<br><br><strong>Note :</strong> since you are using a Wordpress version inferior than 3.5.0, we can\'t set the image as Featured automatically. You may do this by the traditionnal way, in the widget on the sidebar of the post form, choosing the added image in your Media Library.', 'wp-phraseanet' ); ?></p>
						<p class="wppsn-success visuallyhidden"><?php _e( 'The image has been added to the Media Library and set as a Featured Image with success.', 'wp-phraseanet' ); ?></p>
					</div>

					<p>
						<label for="wppsn-single-media-insert-video-title"><?php _e( 'Title', 'wp-phraseanet' ); ?></label>
						<input type="text" name="wppsn-single-media-insert-video-title" id="wppsn-single-media-insert-video-title" class="input-text">
					</p>

				</div>


				<div id="wppsn-single-media-insert-audio" class="wppsn-single-media-insert-pan">
					
					<h2><?php _e( 'Insertion informations', 'wp-phraseanet' ); ?></h2>

					<p id="wppsn-single-media-insert-audio-buttons">
						<!-- <a href="" class="media-details-button button">&lt; <?php //_e( 'Details', 'wp-phraseanet' ); ?></a> -->
						<div class="newButton" id="button-2"><div id="slide"></div><a class="media-details-button xbutton" href="" >&lt; <?php _e( 'Details', 'wp-phraseanet' ); ?></a></div>
					</p>

					<div id="wppsn-single-media-insert-video-thumb"></div>

					<div class="wppsn-set-featured-image-wrapper">
						<a href="" class="wppsn-set-featured-image button"><?php _e( 'Set as Featured image', 'wp-phraseanet' ); ?></a>
						<span class="wppsn-loader visuallyhidden"></span>
						<p class="wppsn-error visuallyhidden"><?php _e( 'There was a problem when adding the image in Media Library.', 'wp-phraseanet' ); ?></p>
						<p class="wppsn-success-partial visuallyhidden"><?php _e( 'The image has been added to the Media Library with success.<br><br><strong>Note :</strong> since you are using a Wordpress version inferior than 3.5.0, we can\'t set the image as Featured automatically. You may do this by the traditionnal way, in the widget on the sidebar of the post form, choosing the added image in your Media Library.', 'wp-phraseanet' ); ?></p>
						<p class="wppsn-success visuallyhidden"><?php _e( 'The image has been added to the Media Library and set as a Featured Image with success.', 'wp-phraseanet' ); ?></p>
					</div>

					<p>
						<label for="wppsn-single-media-insert-video-title"><?php _e( 'Title', 'wp-phraseanet' ); ?></label>
						<input type="text" name="wppsn-single-media-insert-video-title" id="wppsn-single-media-insert-video-title" class="input-text">
					</p>

				</div>

				<p id="wppsn-single-media-insert-buttons">
					<a href="" class="button-primary"><?php _e( 'Insert this media', 'wp-phraseanet' ); ?></a>
				</p>
					
			</div>

			<div class="wppsn-media-preview-wrapper">

				<p>
					<a href="" class="media-preview-close button">&lt; <?php _e( 'Back', 'wp-phraseanet'); ?></a>
				</p>
				
				<h2><?php _e( 'Media Details', 'wp-phraseanet' ); ?></h2>

				<div id="wppsn-single-media-preview-image" class="wppsn-single-media-preview-pan">
					
					<h3 class="wppsn-media-preview-title"></h3>

					<div class="wppsn-media-preview-thumb"></div>

				</div>

				<div id="wppsn-single-media-preview-video" class="wppsn-single-media-preview-pan">
					
					<h3 class="wppsn-media-preview-title"></h3>

					<div id="wppsn-single-media-preview-video-player-wrapper"></div>

				</div>

				<div id="wppsn-single-media-preview-audio" class="wppsn-single-media-preview-pan">
					
					<h3 class="wppsn-media-preview-title"></h3>

					<div id="wppsn-single-media-preview-audio-player-wrapper"></div>

				</div>

			</div>

		</div>

		<div id="wppsn-pan-images" class="wppsn-main-pan">

			<div style="background-color:#464646;color:white"  class="wppsn-main-pan-header">

				<h1 style="color:white" ><?php _e( 'Create an Images Gallery', 'wp-phraseanet' ); ?></h1>

				<h2 class="wppsn-main-pan-tabs">
					<a href="" class="wppsn-main-pan-tab-media"><?php _e( 'Medias', 'wp-phraseanet' ); ?></a>
					<a href="" class="wppsn-main-pan-tab-basket"><?php _e( 'Baskets', 'wp-phraseanet' ); ?></a>
				</h2>

			</div>

			<div class="wppsn-media-list-wrapper">

				<div class="wppsn-media-list-header">

					<input type="text" name="wppsn-search-field" class="search wppsn-search-field" value="" placeholder="<?php _e( 'Search', 'wp-phraseanet' ); ?>">

					<label>
						<input type="radio" name="wppsn-img-gallery-search-type" class="wppsn-search-type" value="0" checked="checked">
						<span><?php _e( 'Documents', 'wp-phraseanet' ); ?></span>
					</label>
					<label>
						<input type="radio" name="wppsn-img-gallery-search-type" class="wppsn-search-type" value="1">
						<span><?php _e( 'Stories', 'wp-phraseanet' ); ?></span>
					</label>

					<p class="clearfix">
						<span class="wppsn-media-counter"><strong>0</strong> <?php _e( 'Medias', 'wp-phraseanet' ); ?></span>
					</p>
				</div> 
				
				<div class="wppsn-media-list"></div>

				<div class="wppsn-media-list-pagination"></div>

			</div>

			<div class="wppsn-baskets-wrapper"></div>

			<div class="wppsn-media-selected-list-wrapper">
				
				<h2><strong class="wppsn-media-selection-counter">0</strong> <?php _e( 'images selected', 'wp-phraseanet' ); ?></h2>

				<p class="wppsn-note"><?php _e( 'Minimum : 2', 'wp-phraseanet' ); ?></p>

				<p class="wppsn-selected-media-list-delete-all">
					<a href=""><?php _e( 'remove all', 'wp-phraseanet' ); ?></a>
				</p>

				<div class="wppsn-selected-media-list">
					<p class="wppsn-media-no-selection"><?php _e( 'No images selected', 'wp-phraseanet' ); ?></p>
					<ul></ul>
				</div>

				<p class="wppsn-selected-media-list-buttons">
					<a href="" class="button-primary"><?php _e( 'Create the gallery', 'wp-phraseanet' ); ?></a>
				</p>

			</div>

			<div class="wppsn-media-preview-wrapper">

				<p>
					<a href="" class="media-preview-close button">&lt; <?php _e( 'Back', 'wp-phraseanet'); ?></a>
				</p>
				
				<h2><?php _e( 'Media Details', 'wp-phraseanet' ); ?></h2>

				<h3 class="wppsn-media-preview-title"></h3>

				<div class="wppsn-media-preview-thumb"></div>

			</div>

			<div id="wppsn-img-gallery-create-gallery-step1">
				
				<p>
					<a href="" class="create-gallery-step1-close button">&lt; <?php _e( 'Back', 'wp-phraseanet'); ?></a>
				</p>
				<p>With download link <input type="checkbox" name="select-all" id="select-all"  /> </p>
				
				<h2><?php _e( 'Images informations', 'wp-phraseanet' ); ?></h2>

				<p class="wppsn-note">
					<?php _e( 'Fill the fields of each image to allow better Search Engine Optimisation.', 'wp-phraseanet' ); ?>
					<br>
				</p>

				<div id="wppsn-img-gallery-list-media-fields"></div>

				<p id="wppsn-img-gallery-create-step1-next-step">
					<a href="" class="create-gallery-step1-next button-primary"><?php _e( 'Next step', 'wp-phraseanet'); ?> &gt;</a>
				</p>

			</div>

			<div id="wppsn-img-gallery-create-gallery-step2">
				
				<p>
					<a href="" class="create-gallery-step2-close button">&lt; <?php _e( 'Back', 'wp-phraseanet'); ?></a>

			
				</p>
				
				<h2><?php _e( 'Display settings', 'wp-phraseanet' ); ?></h2>

				<p class="wppsn-note">
					<?php _e( 'Choose the way the gallery is displayed.', 'wp-phraseanet' ); ?>
				</p>

				<ul id="create-image-gallery-display-setting">
					<li id="create-image-gallery-display-list" class="clearfix">
						<img src="<?php echo WPPSN_PLUGIN_IMAGES_URL . 'list.jpg' ?>">
						<div>
							<h3><?php _e( 'List', 'wp-phraseanet' ); ?></h3>
							<p class="wppsn-note"><?php _e( 'The images are displayed one above the other.', 'wp-phraseanet' ); ?></p>
							<a href="" class="button-primary"><?php _e( 'Create the gallery', 'wp-phraseanet' ); ?> &gt;</a>
						</div>
					</li>
					<li id="create-image-gallery-display-grid" class="clearfix">
						<img src="<?php echo WPPSN_PLUGIN_IMAGES_URL . 'grid.jpg' ?>">
						<div>
							<h3><?php _e( 'Grid', 'wp-phraseanet' ); ?></h3>
							<p class="wppsn-note"><?php _e( 'The images are displayed as a grid of thumbnails.', 'wp-phraseanet' ); ?></p>
							<a href="" class="button-primary"><?php _e( 'Create the gallery', 'wp-phraseanet' ); ?> &gt;</a>
						</div>
					</li>
					<li id="create-image-gallery-display-carrousel" class="clearfix">
						<img src="<?php echo WPPSN_PLUGIN_IMAGES_URL . 'carrousel.jpg' ?>">
						<div>
							<h3><?php _e( 'Carrousel', 'wp-phraseanet' ); ?></h3>
							<p class="wppsn-note"><?php _e( 'The images are displayed in a carrousel.', 'wp-phraseanet' ); ?></p>
							<a href="" class="button-primary"><?php _e( 'Create the gallery', 'wp-phraseanet' ); ?> &gt;</a>
						</div>
					</li>
				</ul>

			</div>

		</div>

		<div id="wppsn-pan-videos" class="wppsn-main-pan">

			<div style="background-color:#464646;color:white"  class="wppsn-main-pan-header">

				<h1 style="color:white"  ><?php _e( 'Create a Audio/Video Playlist', 'wp-phraseanet' ); ?></h1>

				<h2 class="wppsn-main-pan-tabs">
					<a href="" class="wppsn-main-pan-tab-media"><?php _e( 'Medias', 'wp-phraseanet' ); ?></a>
					<a href="" class="wppsn-main-pan-tab-basket"><?php _e( 'Baskets', 'wp-phraseanet' ); ?></a>
				</h2>

			</div>

			<div class="wppsn-media-list-wrapper">

				<div class="wppsn-media-list-header">

					<input type="text" name="wppsn-search-field" class="search wppsn-search-field" value="" placeholder="<?php _e( 'Search', 'wp-phraseanet' ); ?>">

					<label>
						<input type="radio" name="wppsn-video-playlist-search-type" class="wppsn-search-type" value="0" checked="checked">
						<span><?php _e( 'Documents', 'wp-phraseanet' ); ?></span>
					</label>
					<label>
						<input type="radio" name="wppsn-video-playlist-search-type" class="wppsn-search-type" value="1">
						<span><?php _e( 'Stories', 'wp-phraseanet' ); ?></span>
					</label>

					<p class="clearfix">
						<span class="wppsn-media-counter"><strong>0</strong> <?php _e( 'Medias', 'wp-phraseanet' ); ?></span>
					</p>
				</div> 
				
				<div class="wppsn-media-list"></div>

				<div class="wppsn-media-list-pagination"></div>

			</div>

			<div class="wppsn-baskets-wrapper"></div>

			<div class="wppsn-media-selected-list-wrapper">
				
				<h2><strong class="wppsn-media-selection-counter">0</strong> <?php _e( 'Audio / Videos selected', 'wp-phraseanet' ); ?></h2>

				<p class="wppsn-note"><?php _e( 'Minimum : 2', 'wp-phraseanet' ); ?></p>

				<p class="wppsn-selected-media-list-delete-all">
					<a href=""><?php _e( 'remove all', 'wp-phraseanet' ); ?></a>
				</p>

				<div class="wppsn-selected-media-list">
					<p class="wppsn-media-no-selection"><?php _e( 'No Audio / Videos selected', 'wp-phraseanet' ); ?></p>
					<ul></ul>
				</div>

				<p class="wppsn-selected-media-list-buttons">
					<a href="" class="button-primary"><?php _e( 'Create the playlist', 'wp-phraseanet' ); ?></a>
				</p>

			</div>

			<div class="wppsn-media-preview-wrapper">

				<p>
					<a href="" class="media-preview-close button">&lt; <?php _e( 'Back', 'wp-phraseanet'); ?></a>
				</p>
				
				<h2><?php _e( 'Media Details', 'wp-phraseanet' ); ?></h2>

				<h3 class="wppsn-media-preview-title"></h3>

				<div class="wppsn-media-preview-video-player-wrapper">
					
				</div>

			</div>

			<div id="wppsn-video-playlist-create-playlist-step1">
				
				<p>
					<a href="" class="create-playlist-step1-close button">&lt; <?php _e( 'Back', 'wp-phraseanet'); ?></a>
				</p>
				
				<h2><?php _e( 'Videos informations', 'wp-phraseanet' ); ?></h2>

				<p class="wppsn-note">
					<?php _e( 'Fill the fields of each video to allow better Search Engine Optimisation.', 'wp-phraseanet' ); ?>
				</p>

				<div id="wppsn-video-playlist-list-media-fields"></div>

				<p id="wppsn-video-playlist-insert-button">
					<a href="" class="button-primary"><?php _e( 'Insert Playlist', 'wp-phraseanet'); ?></a>
				</p>

			</div>

		</div>

	</div>

	<div id="wppsn-clonable-elements">
		
		<div class="wppsn-set-featured-image-wrapper">


			<div class="single_media_subdef"></div>	
	
			<a href="" class="wppsn-set-featured-image button"><?php _e( 'Set as Featured image', 'wp-phraseanet' ); ?></a>
			<span class="wppsn-loader visuallyhidden"></span>
			<p class="wppsn-error visuallyhidden"><?php _e( 'There was a problem when adding the image in Media Library.', 'wp-phraseanet' ); ?></p>
			<p class="wppsn-success-partial visuallyhidden"><?php _e( 'The image has been added to the Media Library with success.<br><br><strong>Note :</strong> since you are using a Wordpress version inferior than 3.5.0, we can\'t set the image as Featured automatically. You may do this by the traditionnal way, in the widget on the sidebar of the post form, choosing the added image in your Media Library.', 'wp-phraseanet' ); ?></p>
			<p class="wppsn-success visuallyhidden"><?php _e( 'The image has been added to the Media Library and set as a Featured Image with success.', 'wp-phraseanet' ); ?></p>
		</div>

	</div>

<?php
else :
?>
	<p id="plugin-not-set"><?php _e( 'The Phraseanet Plugin must be configured in the Settings section before using it.', 'wp-phraseanet' ); ?></p>
<?php
endif;
?>
<script>

jQuery("#select-all").click(function (event) {
   
    if (this.checked) {
      // Iterate each checkbox
      jQuery(":checkbox").each(function () {
		this.checked = true;
		this.value = 'on'
      });
    } else {
      jQuery(":checkbox").each(function () {
		this.checked = false;
		this.value = 'off'
      });
    }
  });


</script>
