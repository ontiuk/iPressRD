// Theme jQuery functionality
( function( $, t, undefined ) {
    "use strict";
    
    // Vars

    // Window & Document
    var body    = $('body'),
        _window = $(window);
    
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
