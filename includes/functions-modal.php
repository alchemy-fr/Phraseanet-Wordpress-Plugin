<?php

/**
 * Functions used in the modal
 */


/**
 * Phraseanet Requirements
 */
require_once( WPPSN_PLUGIN_VENDOR_PATH . 'autoload.php' );
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

	$search_query 	= stripslashes($_GET['params']['searchQuery']);
	

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


$guzzleAdapter = PhraseanetSDK\Http\GuzzleAdapter::create($wppsn_options['client_base_url'], []);

$guzzleAdapter->setExtended(TRUE);

$connectedGuzzleAdapter = new PhraseanetSDK\Http\ConnectedGuzzleAdapter($wppsn_options['client_token'], $guzzleAdapter);

$api_adapter = new PhraseanetSDK\Http\APIGuzzleAdapter($connectedGuzzleAdapter);

$em = new PhraseanetSDK\EntityManager($api_adapter);

	
 $recordRepository = $em->getRepository( 'Record' );

 
//  $params = [
// 	// We use base ids as collectioon ids due to phraseanet implementation
// 	// Bases field actually points to collections
// 	'bases' => array(),
// 	'offset_start' => '0',
// 	'per_page' => '50',
//   ];


$record_type = $record_type == 'video' ? '' : $record_type;

  $params = [

	'query' 		=> "$search_query",
	'bases' 		=> array(),
	'offset_start' 	=> $current_page * WPPSN_MODAL_LIST_MEDIA_PER_PAGE - WPPSN_MODAL_LIST_MEDIA_PER_PAGE,
	'per_page' 		=> WPPSN_MODAL_LIST_MEDIA_PER_PAGE,
	'record_type' 	=> $record_type,
	'search_type'	=> $search_type
  ];


   //$params['query'] = "$search_query";

	try {
		$query = $recordRepository->search($params);
		
	
	
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
		var_dump($e);
		$output['s'] = 'error';
		$output['sMsg'] = __( 'Error. There was a Exception thrown by the Phraseanet SDK.', 'wp-phraseanet' );
	} catch( \Exception $e ) {
		$output['s'] = 'error';
		$output['sMsg'] = __( 'Error. Something went wrong with the Phraseanet SDK.', 'wp-phraseanet' );
	}



	// If no error
	if ( $output['s'] != 'error' ) {

		
		$results = $query->getResults()->getRecords();


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
					'thumb'			=> ( $mediaThumb != null ) ? $mediaThumb->getPermalink()->getUrl() : WPPSN_PLUGIN_IMAGES_URL . 'no-preview/no-preview-' . $record->getPhraseaType() . '.png',
					'download'      => getUrl($record,$record->getPhraseaType()),
					'phraseaType'	=> $record->getPhraseaType(),
					'preview'		=> wppsn_get_media_preview( $record ),
					'duration'		=> getTechDetails($record,'Duration'),
					'subdef'		=> get_subdef_list($record)
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
	
  	header( "Content-Type: application/json" );
  	echo json_encode( $output );
	exit();

}

add_action( 'wp_ajax_wppsn-get-media-list', 'wppsn_ajax_get_media_list' );


function register_session() {
    if (!session_id())
        session_start();
}

add_action('init', 'register_session',1);


function get_subdef_list($record){


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

				
				
                foreach ($subDef as $i=> $sub) {
					
                
					array_push($preview_infos,array(
						'name'			=> $sub->getName(),
						'thumb_url'		=> $sub->getPermalink()->getUrl(),
						'width'			=> $sub->getWidth(),
						'height'		=> $sub->getHeight()
					));


                }
			}
			// No preview
			else {

				array_push($preview_infos,array(
					'name'			=> '',
					'thumb_url'		=> '',
					'width'			=> '',
					'height'		=> ''
				));
			}

			break;

		case 'video':

			// Try the Thumbnail subdef for the video poster
			try {
				$subDefThumb = $record->getSubDefs( 'thumbnail' );
			} catch( NotFoundException $e ) {
				$subDefThumb = null;
			}

            if ($subDefThumb) {
                foreach ($subDefThumb as $i=> $sub) {
                  
					array_push($preview_infos,array(
						'name'			=> $sub->getName(),
						'thumb_url'		=> $sub->getPermalink()->getUrl(),
						'width'			=> $sub->getWidth(),
						'height'		=> $sub->getHeight()
					));

                }
			}
			
			break;

			case 'audio':

				// Try the Thumbnail subdef for the video poster
				try {
					$subDefThumb = $record->getSubDefs( 'thumbnail' );
				} catch( NotFoundException $e ) {
					$subDefThumb = null;
				}
	
				if ($subDefThumb) {
					foreach ($subDefThumb as $i=> $sub) {

						array_push($preview_infos,array(
							'name'			=> $sub->getName(),
							'thumb_url'		=> $sub->getPermalink()->getUrl(),
							'width'			=> $sub->getWidth(),
							'height'		=> $sub->getHeight()
						));

					}
				}
				
	
				break;

	}





	return $preview_infos;
	

}

add_action( 'wp_ajax_get_subdef_list', 'get_subdef_list' );


function get_facets_list($query=''){

	register_session();


	

    if (empty($_SESSION['facets'])) {
        if (empty($query)) {
            $wppsn_options 	= get_option('wppsn_options');

            $guzzleAdapter = PhraseanetSDK\Http\GuzzleAdapter::create($wppsn_options['client_base_url'], []);

            $guzzleAdapter->setExtended(true);

            $connectedGuzzleAdapter = new PhraseanetSDK\Http\ConnectedGuzzleAdapter($wppsn_options['client_token'], $guzzleAdapter);

            $api_adapter = new PhraseanetSDK\Http\APIGuzzleAdapter($connectedGuzzleAdapter);

            $em = new PhraseanetSDK\EntityManager($api_adapter);

    
            $recordRepository = $em->getRepository('Record');


            $params = [
    // We use base ids as collectioon ids due to phraseanet implementation
    // Bases field actually points to collections
    'bases' => array(),
    'offset_start' => '0',
    'per_page' => '50',
    'query'=> ''
  ];


            $query = $recordRepository->search($params);
        }

        $build_query = [];

        foreach ($query->getFacets() as $facets) {
            $build_query[$facets->getName()] = [0];
         
            foreach ($facets->getValues() as $facet) {
                array_push($build_query[$facets->getName()], [$facet->getValue(),$facet->getQuery()]);
            }
        }

        $response = json_encode($build_query);
		$_SESSION['facets'] =  $response;
    }else{
		$response = $_SESSION['facets'];
	}
 


   header( "Content-Type: application/json" );
   echo $response;
 exit();
}

add_action( 'wp_ajax_get_facets_list', 'get_facets_list' );


function getTechDetails($record,$type){

	$details_array = [];
    foreach ($record->getTechnicalInformation() as $technical_detail) {

        if ($type==$technical_detail->getName()) {
           
			$details_array =  $technical_detail->getValue();
		//	 array_push($details_array, [$technical_detail->getName()=>$technical_detail->getValue()]);
        }
    }


return $details_array;
}


function getUrl($record,$type){

	$url = '';

	foreach ($record->getSubdefs() as $i=>$subdef) {


		if($type=='video'){

		

		

			if($subdef->getMimeType()=='video/mp4' OR $subdef->getMimeType()=='video/webm' OR $subdef->getMimeType()=='video/ogg'){


				$url = $subdef->getPermalink()->getUrl();
                break;

			}else{
				$url = ''; //Video not found
			}

	

		}else{
            if ($i==0) {
                $url = $subdef->getPermalink()->getUrl();
                break;
            }
        }
		


    }

	return $url;

}



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

				
				
                foreach ($subDef as $i=> $sub) {
					
                    $preview_infos = array(
                    'thumb_url'		=> $sub->getPermalink()->getUrl(),
                    'width'			=> $sub->getWidth(),
                    'height'		=> $sub->getHeight()
                );
                }
			}
			// No preview
			else {

				$preview_infos = array(
					'url'		=> WPPSN_PLUGIN_IMAGES_URL . 'no-preview/no-preview-image-big.png'
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

            if ($subDefThumb) {
                foreach ($subDefThumb as $i=> $sub) {
                    $preview_infos['thumb_url'] = ($sub != null) ? $sub->getPermalink()->getUrl() : '';
                }
			}
			
		
			
				if ( $subDef != null ) {

                    foreach ($subDef as $sub) {
						
						$mimeTypeArray = explode( '/', $sub->getMimeType() );

						$preview_infos[$mimeTypeArray[1]] = $sub->getPermalink()->getUrl();

                    }
				}
				// No preview
				else {
					$preview_infos['nopreview']	= WPPSN_PLUGIN_IMAGES_URL . 'no-preview/no-preview-video-big.png';
				}

	
			break;

			case 'audio':

				// Try the Thumbnail subdef for the video poster
				try {
					$subDefThumb = $record->getSubDefs( 'thumbnail' );
				} catch( NotFoundException $e ) {
					$subDefThumb = null;
				}
	
				if ($subDefThumb) {
					foreach ($subDefThumb as $i=> $sub) {
						$preview_infos['thumb_url'] = ($sub != null) ? $sub->getPermalink()->getUrl() : '';
					}
				}
				
			
				
					if ( $subDef != null ) {
	
						foreach ($subDef as $sub) {
							
							$mimeTypeArray = explode( '/', $sub->getMimeType() );
	
							$preview_infos[$mimeTypeArray[1]] = $sub->getPermalink()->getUrl();
	
						}
					}
					// No preview
					else {
						$preview_infos['nopreview']	= WPPSN_PLUGIN_IMAGES_URL . 'no-preview/no-preview-video-big.png';
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
	$nonce  = wp_create_nonce( 'wp_rest');
	$output = array( 'imgID' => 0,'link'=>0,'nonce'=>$nonce);


	if ( !empty( $_GET['url'] ) ) {
		$image_url = $_GET['url'];
		//var_dump($image_url);
		//exit(0);
		$post_id = $_GET['post_id'];

		// Add Featured Image to Post
		
		$image_name       = str_ireplace(" ","_",trim($_GET['title']));
		$upload_dir       = wp_upload_dir(); // Set upload folder
		$image_data       = file_get_contents($image_url); // Get image data



		//Extension

		$ext = pathinfo($image_url, PATHINFO_EXTENSION);
		$ext = explode('?',$ext);

		$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name.'.'.$ext[0] ); // Generate unique name
		$filename         = basename( $unique_file_name ); // Create image file name
	
		// Check folder permission and define file location
		if( wp_mkdir_p( $upload_dir['path'] ) ) {
			$file = $upload_dir['path'] . '/' . $filename;
		} else {
			$file = $upload_dir['basedir'] . '/' . $filename;
		}
	
		// Create the image  file on the server
		file_put_contents( $file, $image_data );
	
		// Check image file type
		$wp_filetype = wp_check_filetype( $filename, null );
	
		// Set attachment data
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
	
		// Create the attachment
		$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
	
		// Include image.php
		require_once(ABSPATH . 'wp-admin/includes/image.php');
	
		// Define attachment metadata
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
	
		// Assign metadata to attachment
		wp_update_attachment_metadata( $attach_id, $attach_data );
	
		// And finally assign featured image to post
		set_post_thumbnail( $post_id, $attach_id );

		$image_link = get_the_post_thumbnail_url($post_id);

		$output['imgID'] = $attach_id;
		$output['link'] = $image_link;

	}

	header( "Content-Type: application/json" );
	echo json_encode( $output );

	exit();
}

add_action( 'wp_ajax_wppsn-add-phraseanet-image-in-media-library', 'wppsn_add_phraseanet_image_in_media_library' );
