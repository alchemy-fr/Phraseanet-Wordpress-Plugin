<?php
/**
 * Content of the WYSIWYG Modal window
 */
?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>WP Phraseanet</title>

		
		<?php
		/* Core WP CSS */

		$css_buttons = '../../../../../wp-includes/css/buttons.min.css';
		$css_buttons_old = '../../../../../wp-admin/css/wp-admin.css';

		if ( file_exists( $css_buttons ) ) {
			?><link rel="stylesheet" href="<?php echo $css_buttons; ?>?ver=1.0.5"><?php
		}
		else {
			?><link rel="stylesheet" href="<?php echo $css_buttons_old; ?>?ver=1.0.5"><?php
		}
		?>
		<link rel="stylesheet" href="../../../../../wp-includes/css/media-views.min.css?ver=1.0.5">

		<?php /* Phraseanet CSS + Normalize */ ?>
		<link rel="stylesheet" href="../../css/normalize.css?ver=1.0.5">

		<?php /* FlowPlayer CSS */ ?>
		<link rel="stylesheet" href="../../libs/flowplayer/skin/minimalist.css?ver=1.0.5">

		<?php /* WP Phraseanet Plugin CSS */ ?>
		<link rel="stylesheet" href="../../css/wysiwyg-modal.css?ver=1.0.5">


		<?php /* jQuery */ ?>
		<script src="../../../../../wp-includes/js/jquery/jquery.js?ver=1.0.5"></script>

		<?php /* TinyMCE Popup Lib and Register */ ?>
		<script src="../../../../../wp-includes/js/tinymce/tiny_mce_popup.js?ver=1.0.5"></script>
		<script src="../../js/modal-tinymce-popup.js?ver=1.0.5"></script>

		<?php /* FLowPlayer JS */ ?>
		<script src="../../libs/flowplayer/flowplayer.min.js?ver=1.0.5"></script>

		<?php /* Phraseanet Modal JS */ ?>
		<script src="../../js/modal.js?ver=1.0.5"></script>
		

	</head>
	<body class="wp-core-ui media-modal media-frame">
			
		

	</body>
</html>
