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
		linkDelete: '<?php _e( 'delete', 'wp-phraseanet' ); ?>',
		mediaTitleLabel: '<?php _e( 'Title', 'wp-phraseanet' ); ?>',
		mediaAltTextLabel: '<?php _e( 'Alternate Text', 'wp-phraseanet' ); ?>',
		mediaLegendLabel: '<?php _e( 'Legend', 'wp-phraseanet' ); ?>'
	};
	</script>

	<div id="wppsn-sidebar">

		<div id="wppsn-menu">
			<a href="" id="wppsn-menu-single-media" class="wppsn-menu-item current-menu"><?php _e( 'Insert a Media', 'wp-phraseanet' ) ?></a>
			<a href="" id="wppsn-menu-img-gallery" class="wppsn-menu-item"><?php _e( 'Create an Image Gallery', 'wp-phraseanet' ) ?></a>
			<!-- <a href="" id="wppsn-menu-video-playlist" class="wppsn-menu-item"><?php _e( 'Create a Video Playlist', 'wp-phraseanet' ) ?></a> -->
		</div>

		<div id="wppsn-credits">
			Crédits Phraseanet / Labomedia
		</div>

	</div>

	<div id="wppsn-main">

		<div id="wppsn-pan-single-media" class="wppsn-main-pan">

			<div id="wppsn-single-media-header">

				<h1><?php _e( 'Insert a Media', 'wp-phraseanet' ); ?></h1>

				<h2 id="wppsn-single-media-tabs">
					<a href="" id="wppsn-single-media-tab-media"><?php _e( 'Medias', 'wp-phraseanet' ); ?></a>
					<a href="" id="wppsn-single-media-tab-basket"><?php _e( 'Baskets', 'wp-phraseanet' ); ?></a>
				</h2>

			</div>

			<div id="wppsn-single-media-list-medias-wrapper">

				<div id="wppsn-single-media-list-medias-header">
					
					<input type="text" name="wppsn-single-media-search" id="wppsn-single-media-search" class="search" value="" placeholder="<?php _e( 'Search', 'wp-phraseanet' ); ?>">

					<label>
						<input type="radio" name="wppsn-single-media-search-type" class="wppsn-single-media-search-type" id="wppsn-single-media-search-type-0" value="0" checked="checked">
						<span><?php _e( 'Documents', 'wp-phraseanet' ); ?></span>
					</label>
					<label>
						<input type="radio" name="wppsn-single-media-search-type" class="wppsn-single-media-search-type" id="wppsn-single-media-search-type-1" value="1">
						<span><?php _e( 'Coverages', 'wp-phraseanet' ); ?></span>
					</label>

					<p class="clearfix">
						<span id="wppsn-single-media-counter"><strong>0</strong> <?php _e( 'Medias', 'wp-phraseanet' ); ?></span>
						<select name="wppsn-single-media-record-type" id="wppsn-single-media-record-type">
							<option value="all"><?php _e( 'All', 'wp-phraseanet' ); ?></option>
							<option value="image"><?php _e( 'Images', 'wp-phraseanet' ); ?></option>
							<option value="video"><?php _e( 'Videos', 'wp-phraseanet' ); ?></option>
							<option value="audio"><?php _e( 'Audio', 'wp-phraseanet' ); ?></option>
							<option value="document"><?php _e( 'Documents', 'wp-phraseanet' ); ?></option>
							<option value="flash"><?php _e( 'Flash', 'wp-phraseanet' ); ?></option>
						</select>
					</p>
				</div> 
				
				<div id="wppsn-single-media-list-medias"></div>

				<div id="wppsn-single-media-list-pagination"></div>

			</div>

			<div id="wppsn-single-media-list-baskets-wrapper">

				<div id="wppsn-single-media-list-baskets" class="list-loading">

				</div>

			</div>

			<div id="wppsn-single-media-insert-wrapper">

				<div id="wppsn-single-media-insert-image" class="wppsn-single-media-insert-pan">
					
					<h2><?php _e( 'Insertion informations', 'wp-phraseanet' ); ?></h2>

					<p id="wppsn-single-media-insert-image-buttons">
						<a href="" class="media-details-button button">&lt; <?php _e( 'Details', 'wp-phraseanet' ); ?></a>
					</p>

					<div id="wppsn-single-media-insert-image-thumb"></div>

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
						<input type="text" name="wppsn-single-media-insert-image-legend" id="wppsn-single-media-insert-image-legend" class="input-text">
					</p>

					<p>
						<label><?php _e( 'Display setting', 'wp-phraseanet' ); ?></label>
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
						<a href="" class="media-details-button button">&lt; <?php _e( 'Details', 'wp-phraseanet' ); ?></a>
					</p>

					<div id="wppsn-single-media-insert-video-thumb"></div>

					<p>
						<label for="wppsn-single-media-insert-video-title"><?php _e( 'Title', 'wp-phraseanet' ); ?></label>
						<input type="text" name="wppsn-single-media-insert-video-title" id="wppsn-single-media-insert-video-title" class="input-text">
					</p>

				</div>

				<p id="wppsn-single-media-insert-buttons">
					<a href="" class="button-primary"><?php _e( 'Insert this media', 'wp-phraseanet' ); ?></a>
				</p>
					
			</div>

			<div id="wppsn-single-media-preview-wrapper">

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

					<div id="wppsn-single-media-preview-video-player-wrapper" class="jp-video">
						
			            <div class="jp-type-single">
			                <div id="wppsn-single-media-preview-video-player" class="jp-jplayer"></div>
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
			                                <li><a href="javascript:;" class="jp-play" tabindex="1"><?php _e( 'play', 'wp-phraseanet' ); ?></a></li>
			                                <li><a href="javascript:;" class="jp-pause" tabindex="1"><?php _e( 'pause', 'wp-phraseanet' ); ?></a></li>
			                                <li><a href="javascript:;" class="jp-stop" tabindex="1"><?php _e( 'stop', 'wp-phraseanet' ); ?></a></li>
			                                <li><a href="javascript:;" class="jp-mute" tabindex="1" title="<?php _e( 'mute', 'wp-phraseanet' ); ?>"><?php _e( 'mute', 'wp-phraseanet' ); ?></a></li>
			                                <li><a href="javascript:;" class="jp-unmute" tabindex="1" title="<?php _e( 'unmute', 'wp-phraseanet' ); ?>"><?php _e( 'unmute', 'wp-phraseanet' ); ?></a></li>
			                                <li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="<?php _e( 'max volume', 'wp-phraseanet' ); ?>"><?php _e( 'max volume', 'wp-phraseanet' ); ?></a></li>
			                            </ul>
			                            <div class="jp-volume-bar">
			                                <div class="jp-volume-bar-value"></div>
			                            </div>
			                            <ul class="jp-toggles">
			                                <li><a href="javascript:;" class="jp-full-screen" tabindex="1" title="<?php _e( 'full screen', 'wp-phraseanet' ); ?>"><?php _e( 'full screen', 'wp-phraseanet' ); ?></a></li>
			                                <li><a href="javascript:;" class="jp-restore-screen" tabindex="1" title="<?php _e( 'restore screen', 'wp-phraseanet' ); ?>"><?php _e( 'restore screen', 'wp-phraseanet' ); ?></a></li>
			                                <li><a href="javascript:;" class="jp-repeat" tabindex="1" title="<?php _e( 'repeat', 'wp-phraseanet' ); ?>"><?php _e( 'repeat', 'wp-phraseanet' ); ?></a></li>
			                                <li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="<?php _e( 'repeat off', 'wp-phraseanet' ); ?>"><?php _e( 'repeat off', 'wp-phraseanet' ); ?></a></li>
			                            </ul>
			                        </div>
			                        <div class="jp-title">
			                            <ul>
			                                <li></li>
			                            </ul>
			                        </div>
			                    </div>
			                </div>
			                <div class="jp-no-solution">
			                    <span><?php _e( 'Update required', 'wp-phraseanet' ); ?></span>
			                    <?php _e( 'To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.' , 'wp-phraseanet' ); ?>
			                </div>
			            </div>

					</div>

					<div class="wppsn-media-preview-thumb"></div>

				</div>

			</div>

		</div>

		<div id="wppsn-pan-img-gallery" class="wppsn-main-pan">

			<div id="wppsn-img-gallery-header">

				<h1><?php _e( 'Create an Images Gallery', 'wp-phraseanet' ); ?></h1>

				<h2 id="wppsn-img-gallery-tabs">
					<a href="" id="wppsn-img-gallery-tab-media"><?php _e( 'Medias', 'wp-phraseanet' ); ?></a>
					<a href="" id="wppsn-img-gallery-tab-basket"><?php _e( 'Baskets', 'wp-phraseanet' ); ?></a>
				</h2>

			</div>

			<div id="wppsn-img-gallery-list-medias-wrapper">

				<div id="wppsn-img-gallery-list-medias-header">

					<input type="text" name="wppsn-img-gallery-search" id="wppsn-img-gallery-search" class="search" value="" placeholder="<?php _e( 'Search', 'wp-phraseanet' ); ?>">

					<label>
						<input type="radio" name="wppsn-img-gallery-search-type" class="wppsn-img-gallery-search-type" id="wppsn-img-gallery-search-type-0" value="0" checked="checked">
						<span><?php _e( 'Documents', 'wp-phraseanet' ); ?></span>
					</label>
					<label>
						<input type="radio" name="wppsn-img-gallery-search-type" class="wppsn-img-gallery-search-type" id="wppsn-img-gallery-search-type-1" value="1">
						<span><?php _e( 'Coverages', 'wp-phraseanet' ); ?></span>
					</label>

					<p class="clearfix">
						<span id="wppsn-img-gallery-counter"><strong>0</strong> <?php _e( 'Medias', 'wp-phraseanet' ); ?></span>
					</p>
				</div> 
				
				<div id="wppsn-img-gallery-list-medias"></div>

				<div id="wppsn-img-gallery-list-pagination"></div>

			</div>

			<div id="wppsn-img-gallery-list-baskets-wrapper">

				<div id="wppsn-img-gallery-list-baskets" class="list-loading">

				</div>

			</div>

			<div id="wppsn-img-gallery-selected-list-wrapper">
				
				<h2><strong id="wppsn-img-gallery-selection-counter">0</strong> <?php _e( 'images selected', 'wp-phraseanet' ); ?></h2>

				<p class="wppsn-note"><?php _e( 'Minimum : 2', 'wp-phraseanet' ); ?></p>

				<p id="wppsn-img-gallery-selection-delete-all">
					<a href=""><?php _e( 'delete all', 'wp-phraseanet' ); ?></a>
				</p>

				<div id="wppsn-img-gallery-selected-list">
					<p id="wppsn-img-gallery-no-selection"><?php _e( 'No images selected', 'wp-phraseanet' ); ?></p>
					<ul></ul>
				</div>

				<p id="wppsn-img-gallery-selected-list-buttons">
					<a href="" class="button-primary"><?php _e( 'Create the gallery', 'wp-phraseanet' ); ?></a>
				</p>

			</div>

			<div id="wppsn-img-gallery-preview-wrapper">

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
				
				<h2><?php _e( 'Images informations', 'wp-phraseanet' ); ?></h2>

				<p class="wppsn-note">
					<?php _e( 'Fill the fields of each image to allow better Search Engine Optimisation.', 'wp-phraseanet' ); ?>
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
						<img src="http://placehold.it/70x70">
						<div>
							<h3><?php _e( 'List', 'wp-phraseanet' ); ?></h3>
							<p class="wppsn-note"><?php _e( 'The images are displayed one above the other.', 'wp-phraseanet' ); ?></p>
							<a href="" class="button-primary"><?php _e( 'Create the gallery', 'wp-phraseanet' ); ?> &gt;</a>
						</div>
					</li>
					<li id="create-image-gallery-display-grid" class="clearfix">
						<img src="http://placehold.it/70x70">
						<div>
							<h3><?php _e( 'Grid', 'wp-phraseanet' ); ?></h3>
							<p class="wppsn-note"><?php _e( 'The images are displayed as a grid of thumbnails.', 'wp-phraseanet' ); ?></p>
							<a href="" class="button-primary"><?php _e( 'Create the gallery', 'wp-phraseanet' ); ?> &gt;</a>
						</div>
					</li>
					<li id="create-image-gallery-display-carrousel" class="clearfix">
						<img src="http://placehold.it/70x70">
						<div>
							<h3><?php _e( 'Carrousel', 'wp-phraseanet' ); ?></h3>
							<p class="wppsn-note"><?php _e( 'The images are displayed in a carrousel.', 'wp-phraseanet' ); ?></p>
							<a href="" class="button-primary"><?php _e( 'Create the gallery', 'wp-phraseanet' ); ?> &gt;</a>
						</div>
					</li>
				</ul>

			</div>

		</div>

		<!-- <div id="wppsn-pan-video-playlist" class="wppsn-main-pan">

			

		</div> -->

	</div>

<?php
else :
?>
	<p id="plugin-not-set"><?php _e( 'The Phraseanet Plugin must be configured in the Settings section before using it.', 'wp-phraseanet' ); ?></p>
<?php
endif;