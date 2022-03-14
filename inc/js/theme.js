// Theme jQuery functionality
( function( $, t, undefined ) {
    "use strict";
    
    // Vars

    // Window & Document
    var body    = $('body'),
        _window = $(window);
    
	// Clunky but useable mobile detection
	var isMobile = {
		Android: function() {
			return navigator.userAgent.match(/Android|webOS/i);
		},
		BlackBerry: function() {
			return navigator.userAgent.match(/BlackBerry/i);
		},
		iOS: function() {
			return navigator.userAgent.match(/iPhone|iPad|iPod/i);
		},
		Opera: function() {
			return navigator.userAgent.match(/Opera Mini/i);
		},
		Windows: function() {
			return navigator.userAgent.match(/IEMobile/i);
		},
		any: function() {
			return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
		}
	};

    // On window load functions
    _window.on('load', function(){});

    // Disable default link behavior for dummy links : href='#'
    $('a[href="#"]').click( function(e) {
        e.preventDefault();
    });

    // Function definitions
    
    // Inner IIFE
    ( function() {

    } )();

    // Document Ready DOM
    $(function(){ 

    });

    // Other jQuery

})( jQuery, theme );

//end
