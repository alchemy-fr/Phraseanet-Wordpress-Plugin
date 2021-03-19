<?php
/**
 * Content of the WYSIWYG Modal window
 */
?><!DOCTYPE html>
<html lang="">
	<head>
		<meta charset="utf-8">
		<title style="background-color: black;">Phraseanet</title>

		
		<?php /* Core WP CSS */ ?>
		<link rel="stylesheet" href="../../css/wp-core-css.css?ver=1.0.9">

		<?php /* Normalize.css */ ?>
		<link rel="stylesheet" href="../../css/normalize.css?ver=1.0.9">

	

		<?php /* WP Phraseanet Plugin CSS */ ?>
		<link rel="stylesheet" href="../../css/wysiwyg-modal.css?ver=2.0.0">


		<?php /* WP jQuery */ ?>
		<script src="../../../../../wp-includes/js/jquery/jquery.js?ver=1.0.9"></script>

		<?php /* TinyMCE Popup Lib and Register */ ?>
		<script src="../../../../../wp-includes/js/tinymce/tiny_mce_popup.js?ver=1.0.9"></script>
		<script src="../../js/modal-tinymce-popup.js?ver=2.0.0"></script>

		

		<?php /* Phraseanet Modal JS */ ?>
		<script src="../../js/modal.js?ver=<?php rand(1,9).'.'.rand(1,9).'.'.rand(1,9); ?>"></script>
		


	
	</head>
	<body class="wp-core-ui media-modal media-frame">

    <p>Loading....</p>
		

	</body>


<script>

window.onload = selectMenu();


function selectedOption(id){
	localStorage.removeItem('selected_option');
	localStorage.setItem('selected_option',id);
}

function selectMenu() {
//
}


</script>


</html>
