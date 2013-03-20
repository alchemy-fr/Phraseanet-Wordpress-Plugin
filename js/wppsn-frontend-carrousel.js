/**
 * Init Flexsliders in the page
 */

jQuery(document).ready(function($) {
	
	var wppsnCarrousels = $( '.wppsn-carrousel' );

	if ( wppsnCarrousels.length > 0 ) {

		wppsnCarrousels.each(function(){

			var currentCarrouselID = $( this ).attr( 'id' );
			var currentCarrouselUniqueString = currentCarrouselID.split( '-' )[2];
			var currentSliderID = '#wppsn-slider-' + currentCarrouselUniqueString;

			$( '#' + currentCarrouselID ).flexslider({
			    animation: "slide",
			    controlNav: false,
			    animationLoop: false,
			    slideshow: false,
			    itemWidth: 200,
			    itemMargin: 5,
			    asNavFor: currentSliderID
			});

			$( currentSliderID ).flexslider({
				animation: "slide",
				controlNav: false,
				directionNav: true,
				animationLoop: false,
				slideshow: false,
				smoothHeight: true,
				sync: '#' + currentCarrouselID
			});

		});

	}

});