<?php
/**
 * Functions used in the modal
 */


/**
 * Phraseanet Requirements
 */
require_once( WPPSN_PLUGIN_VENDOR_PATH . 'autoload.php' );

use PhraseanetSDK\EntityManager;
use PhraseanetSDK\Client;
use PhraseanetSDK\HttpAdapter\Guzzle as GuzzleAdapter;
use PhraseanetSDK\Exception\ExceptionInterface;
use PhraseanetSDK\Exception\RuntimeException;
use PhraseanetSDK\Exception\NotFoundException;
use PhraseanetSDK\Exception\UnauthorizedException;


/**
 * (AJAX) Get the Initial HTML content of the Modal
 * @return HTML
 */
function wppsn_ajax_get_modal_initial_content() {

    include_once( WPPSN_PLUGIN_TEMPLATES_PATH . 'tpl-modal-initial-content.php' );

    exit();

}

add_action( 'wp_ajax_wppsn-get-modal-initial-content', 'wppsn_ajax_get_modal_initial_content' );


/**
 * (AJAX) Get a List of media from Phraseanet
 * @return json List of media
 */
function wppsn_ajax_get_media_list() {

	$search_query 	= $_GET['params']['searchQuery'];
	$search_type  	= $_GET['params']['searchType'];
	$record_type	= $_GET['params']['recordType'];
	$current_page 	= ( $_GET['params']['pageNb'] < 1 ) ? 1 : $_GET['params']['pageNb'];

	$mediaList 		= array();
	$wppsn_options 	= get_option( 'wppsn_options' );

	// The output contains at least a status and the search query parameters
	$output 		= array(
		's' 			=> 'success',
		'searchQuery'	=> $search_query,
		'searchType'	=> $search_type,
		'recordType'	=> $record_type
	);

	// Create connection to the Phraseanet DB
	$HttpAdapter = GuzzleAdapter::create();
	$HttpAdapter->setBaseUrl( $wppsn_options['client_base_url'] );

	$client = new Client( $wppsn_options['client_id'], $wppsn_options['client_secret'], $HttpAdapter );
	$client->setAccessToken( $wppsn_options['client_token'] );

	$em = new EntityManager( $client );

	$recordRepository = $em->getRepository( 'Record' );

	try {
		$query = $recordRepository->search( array(
		    'query' 		=> $search_query,
		    'bases' 		=> array(),
		    'offset_start' 	=> $current_page * WPPSN_MODAL_LIST_MEDIA_PER_PAGE - WPPSN_MODAL_LIST_MEDIA_PER_PAGE,
		    'per_page' 		=> WPPSN_MODAL_LIST_MEDIA_PER_PAGE,
		    'record_type' 	=> $record_type,
		    'search_type'	=> $search_type
		));
	} catch( RuntimeException $e ) {
		$output['s'] = 'error';
		$output['sMsg'] = __( 'Error. You should check the Phraseanet Base URL in the plugin settings.', 'wp-phraseanet' );
	} catch( NotFoundException $e ) {
		$output['s'] = 'error';
		$output['sMsg'] = __( 'Entity or Controler not Found.', 'wp-phraseanet' );
	} catch( UnauthorizedException $e ) {
		$output['s'] = 'error';
		$output['sMsg'] = __( 'Error. You should check the Oauth Token in the plugin settings.', 'wp-phraseanet' );
	} catch( ExceptionInterface $e ) {
		$output['s'] = 'error';
		$output['sMsg'] = __( 'Error. There was a Exception thrown by the Phraseanet SDK.', 'wp-phraseanet' );
	} catch( \Exception $e ) {
		$output['s'] = 'error';
		$output['sMsg'] = __( 'Error. Something went wrong with the Phraseanet SDK.', 'wp-phraseanet' );
	}

	// If no error
	if ( $output['s'] != 'error' ) {

		$results = $query->getResults();

		// Is there some results ?
		if ( count( $results ) > 0 ) {

			$output['total'] = $query->getTotalResults();

			$total_pages = ceil( $output['total'] / WPPSN_MODAL_LIST_MEDIA_PER_PAGE );

			// Correct the current_page
			if ( $current_page > $total_pages ) {
				$current_page = $total_pages;
			}

			$output['pagination'] = wppsn_get_media_list_pagination_html( $total_pages, $current_page );

			// Loop through records
			foreach( $results as $record ) {
				$mediaThumb = $record->getThumbnail();

				$mediaList[] = array(
					'id' 			=> $record->getRecordId(),
					'title'			=> $record->getTitle(),
					'thumb'			=> ( $mediaThumb != null ) ? $mediaThumb->getPermalink()->getUrl() : 'http://placehold.it/200x133&text=+no+image',
					'phraseaType'	=> $record->getPhraseaType(),
					'preview'		=> wppsn_get_media_preview( $record )
				);
			}

			$output['mediaList'] = $mediaList;

		}
		// No results
		else {
			$output['s'] = 'no-results';
			$output['sMsg'] = __( 'No results.', 'wp-phraseanet' );
		}

	}
	
	// Return JSON encoded array
	$response = json_encode( $output );
  	header( "Content-Type: application/json" );
  	echo json_encode( $output );
	exit();

}

add_action( 'wp_ajax_wppsn-get-media-list', 'wppsn_ajax_get_media_list' );


/**
 * Get the Media Preview infos
 * @param  [Object] $record Phraseanet Record
 * @return [array]          All preview infos
 */
function wppsn_get_media_preview( $record ) {

	$preview_infos = array();

	// Subdef exists ?
	try {
		$subDef = $record->getSubDefs( 'preview' );
	} catch( NotFoundException $e ) {
		$subDef = null;
	}

	switch ( $record->getPhraseaType() ) {

		case 'image':

			if ( $subDef != null ) {

				$preview_infos = array(
					'url'		=> $subDef->getPermalink()->getUrl(),
					'width'		=> $subDef->getWidth(),
					'height'	=> $subDef->getHeight()
				);

			}
			// No preview
			else {

				$preview_infos = array(
					'url'		=> 'http://placehold.it/600x400&text=No+preview'
				);

			}

			break;

		case 'video':

			// Try the Thumbnail subdef for the video poster
			try {
				$subDefThumb = $record->getSubDefs( 'thumbnail' );
			} catch( NotFoundException $e ) {
				$subDefThumb = null;
			}

			$preview_infos['thumb'] = ( $subDefThumb != null ) ? $subDefThumb->getPermalink()->getUrl() : '';

			// Try the Videos SubDefs for 'screen' devices
			try {
				$subDefsMimeTypes = $record->getSubdefsByDevicesAndMimeTypes( array( 'screen' ), array( 'video/mp4', 'video/webm', 'video/ogg' ) );
			} catch( NotFoundException $e ) {
				$subDefsMimeTypes = null;
			}

			if ( $subDefsMimeTypes != null ) {

				foreach( $subDefsMimeTypes as $sdm ) {

					$mimeTypeArray = explode( '/', $sdm->getMimeType() );

					$preview_infos[$mimeTypeArray[1]] = $sdm->getPermalink()->getUrl();

				}

			}
			// Else take the standard preview SubDef
			else {

				if ( $subDef != null ) {
					$preview_infos['mp4'] = $subDef->getPermalink()->getUrl();
				}
				// No preview
				else {
					$preview_infos['nopreview']	= 'http://placehold.it/600x400&text=No+preview';
				}

			}

			break;

	}

	return $preview_infos;

}


/**
 * Create the Media List pagination
 * @param int $total_pages Total number pages
 * @param int $current_page Current Page number
 * @return string HTML of the list
 */
function wppsn_get_media_list_pagination_html( $total_pages, $current_page ) {

	$pagination = '';

	// Nb of pages displayed around a page number if in the middle of the list
	$adj_pages = 1;

	// Nb min of pages to get a truncature
	// it's calculated like this : First 2 pages + {truncature} + {adj_pages} + {current_page} + {adj_pages} + {truncature} + 2 last pages
	$min_pages_links = 2 + 1 + $adj_pages + 1 + $adj_pages + 1 + 2;

	if ( $total_pages > 1 ) {

		$pagination .= '<p>';

		// ========================================================
		// Total pages lower than $min_pages_links -> No Truncature
		// ========================================================
		if ( $total_pages < $min_pages_links ) {

			for ( $i = 1; $i <= $total_pages; $i++ ) {
				if ( $i == $current_page ) {
					$pagination .= '<a href="" class="current">' . $i . '</a> ';
				}
				else {
					$pagination .= '<a href="">' . $i . '</a> ';
				}
			}

		}
		// ================================================================
		// Total pages greater or equal than $min_pages_links -> Truncature
		// ================================================================
		else {

			// Page index max to consider the current_page in the first pages
			// Calculate : 2 first pages + {adj_pages} + {current_page}
			$page_index_max_for_first_pages = 2 + $adj_pages + 1;

			// Page index max to consider the current_page in the last pages
			// Calculate : {total_pages} - 2 last pages - {adj_pages} - {current_page} + 1 (because of the reverse count)
			$page_index_min_for_last_pages = $total_pages - 2 - $adj_pages - 1 + 1;

			// FIRST CASE : the current page is in the first pages
			if ( $current_page <= $page_index_max_for_first_pages ) {

				// First pages until the truncature
				// max number pages displayed is : First 2 pages + {adj_pages} + {current_page} + {adj_pages}
				for ( $i = 1; $i <= ( 2 + $adj_pages + 1 + $adj_pages ); $i++ ) {
					if ( $i == $current_page ) {
						$pagination .= '<a href="" class="current">' . $i . '</a> ';
					}
					else {
						$pagination .= '<a href="">' . $i . '</a> ';
					}
				}

				// The truncature and last 2 pages
				$pagination .= '<span>&hellip;</span> ';
				$pagination .= '<a href="">' . ( $total_pages - 1 ) . '</a> ';
				$pagination .= '<a href="">' . $total_pages . '</a> ';

			}
			// SECOND CASE : the current page is in the middle pages
			elseif ( ( $current_page > $page_index_max_for_first_pages ) && ( $current_page < $page_index_min_for_last_pages ) ) {

				// First 2 pages + truncature
				$pagination .= '<a href="">1</a> ';
				$pagination .= '<a href="">2</a> ';
				$pagination .= '<span>&hellip;</span> ';

				// Pages in the middle
				for ( $i = $current_page - $adj_pages; $i <= $current_page + $adj_pages; $i++ ) {
					if ( $i == $current_page ) {
						$pagination .= '<a href="" class="current">' . $i . '</a> ';
					}
					else {
						$pagination .= '<a href="">' . $i . '</a> ';
					}
				}

				// Truncature and last 2 pages
				$pagination .= '<span>&hellip;</span> ';
				$pagination .= '<a href="">' . ( $total_pages - 1 ) . '</a> ';
				$pagination .= '<a href="">' . $total_pages . '</a> ';

			}
			// THIRD CASE : the current page is in the last pages
			elseif ( $current_page >= $page_index_min_for_last_pages ) {

				// First 2 pages + truncature
				$pagination .= '<a href="">1</a> ';
				$pagination .= '<a href="">2</a> ';
				$pagination .= '<span>&hellip;</span> ';

				// Last pages
				for ( $i = $page_index_min_for_last_pages - $adj_pages; $i <= $total_pages; $i++ ) {
					if ( $i == $current_page ) {
						$pagination .= '<a href="" class="current">' . $i . '</a> ';
					}
					else {
						$pagination .= '<a href="">' . $i . '</a> ';
					}
				}

			}

		}

		$pagination .= '</p>';

	}

	return $pagination;

}


/**
 * Add external image to the Media Library
 * Code borrowed from wp-admin/includes/media.php function media_sideload_image()
 * @return json  Response
 */
function wppsn_add_phraseanet_image_in_media_library() {
	$output = array( 'imgID' => 0 );

	if ( !empty( $_GET['url'] ) ) {
		$file = $_GET['url'];

		$image_title = ( $_GET['title'] != '' ) ? $_GET['title'] : null;

		// Download file to temp location
		$tmp = download_url( $file );

		// Set variables for storage
		// fix file filename for query strings
		preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );
		$file_array['name'] = basename($matches[0]);
		$file_array['tmp_name'] = $tmp;

		// If error storing temporarily, unlink
		if ( is_wp_error( $tmp ) ) {
			@unlink( $file_array['tmp_name'] );
			$file_array['tmp_name'] = '';
		}
		else {

			// do the validation and storage stuff
			$id = media_handle_sideload( $file_array, 0, $image_title );

			// If error storing permanently, unlink
			if ( is_wp_error( $id ) ) {
				@unlink( $file_array['tmp_name'] );
			}
			else {
				$output['imgID'] = $id;
			}

		}

	}

	echo json_encode( $output );

	exit();
}

add_action( 'wp_ajax_wppsn-add-phraseanet-image-in-media-library', 'wppsn_add_phraseanet_image_in_media_library' );