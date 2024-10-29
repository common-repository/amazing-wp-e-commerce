jQuery( document ).ready( function( $ ) {
	// We do not need to show product images on single product view
	if( !$( "body" ).hasClass( "single-wpsc-product" ) ) {
		// Slideshow timer holder
		var amazingTimer;

		// Show images on thumbnail hover
		$( "a img.product_image" ).parent().hover(function( e ) {
			// Get product ID
			var image_wrapper	= $( this );
			var current_image	= image_wrapper.find( '.product_image' ).first();
			var product_elem_id	= current_image.attr( 'id' );
			var product_id 		= parseInt( product_elem_id.substr( -2 ) );
			var image_classes	= current_image.attr( 'class' );

			// Add hovering class
			image_wrapper.addClass( 'hovering' );

			// Do we already have those extra images?
			if( !current_image.hasClass( 'mainThumbnail' ) ) {
				// Add loading animation if it doesn't exist already
				if( image_wrapper.find( ".amazing-loading" ).length == 0 ) {
					image_wrapper.prepend( "<div class='amazing-loading' />" );
				}

				// Send product ID to our plugin
				$.post(
					wpsc_ajax.ajaxurl,
					{	'action' 		: 'extra_product_images',
						'product_id'	: product_id,
						'img_class'		: image_classes
					},
					function( data ) {
						// Add "first" class to this image
						current_image.addClass( 'mainThumbnail' ).addClass( 'active_image' );

						// Add fetched images
						image_wrapper.append( data );

						// Start slideshow
						if( image_wrapper.hasClass( 'hovering' ) && data != "" ) {
							amazingTimer	= setInterval( function() { startAmazingSlideshow( image_wrapper ) }, 750 );
						}
						else {
							image_wrapper.find( ".amazing-loading" ).remove();
						}
					}
				);
			}
			else {
				if( image_wrapper.hasClass( 'hovering' ) ) {
					// Start slideshow
					amazingTimer	= setInterval( function() { startAmazingSlideshow( image_wrapper ) }, 750 );
				}
				else image_wrapper.find( ".amazing-loading" ).remove();
			}
		}, function( e ) {
			// Remove hovering class
			$( this ).removeClass( 'hovering' );
			
			// Stop slideshow
			stopAmazingSlideshow( $( this ).find( '.product_image' ).first() );
		});

		function startAmazingSlideshow( first_image_parent ) {
			// Remove loading animation
			$( first_image_parent ).find( ".amazing-loading" ).remove();

			if( $( first_image_parent ).find( "img" ).length < 1 ) return;

			// Find previous image
			var previous_active	= $( first_image_parent ).find( ".active_image" );
			var next_active;
			
			// Add new active status to next image
			if( previous_active.next( "img" ).length != 0 ) {
				next_active		= previous_active.next( "img" );
			}
			else {
				next_active 	= $( first_image_parent ).find( ".mainThumbnail" );
			}

			// Remove active image status from previous image
			previous_active.removeClass( "active_image" );

			// Add active image status to next image
			next_active.addClass( "active_image" );
		}

		function stopAmazingSlideshow( first_image ) {
			// Remove loading animation
			$( first_image ).parent().find( ".amazing-loading" ).remove();

			clearInterval( amazingTimer );

			$( first_image ).parent().find( ".active_image" ).removeClass( "active_image" );
			$( first_image ).addClass( "active_image" );
		}
	}
});