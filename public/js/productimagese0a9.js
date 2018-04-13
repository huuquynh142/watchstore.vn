var Foundationify = function () {

	// Initalize product member gallery function on product pages
	function initProductImages () {

		// Define the scope
		var $productImages = $('#product-images', 'body.product');

		// Select the thumbnails
		var $thumbs = $('ul img', $productImages);

		if ( $thumbs.length ) {

			// Select the large member
			var $largeImage = $('img', $productImages).first();

			// Change the large member src and alt attributes on click
			$thumbs.on('click', function (e) {
				e.preventDefault();

				// Skip if thumb matches large
				if ($largeImage.attr('src') === $(this).parent('a').attr('href')) {
					return;
				}

				// Change the cursor to the loading cursor
				$('body').css('cursor', 'progress');

				// Change the src and alt attributes of the large member
				$largeImage.attr('src', $(this).parent('a').attr('href'))
						   .attr('alt', $(this).attr('alt'));
              	$('#product-images .zoomImg').attr('src', $(this).parent('a').attr('href'))
						   .attr('alt', $(this).attr('alt'));

			});

			// Return the loading cursor to default after the large member has loaded
			$largeImage.on('load', function () {
				$('body').css('cursor', 'auto');
			});


		} 
	}

	return {
		init: function () {
			initProductImages();
		}		
	};

}();

$(document).ready(function () {
	Foundationify.init();
});