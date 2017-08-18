<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Search form functionality shortcodes
 *
 * @package     iPress\Shortcodes
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */

// Access restriction
if ( ! defined( 'ABSPATH' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit;
}

//---------------------------------------------
//  Search Form 
//---------------------------------------------

/**
 * Retrieve current user info
 *
 * @param   array|string $atts 
 * @return  string
 */
function ipress_search_form_shortcode( $atts ) {

    // Capture output
    ob_start();
    get_search_form( );
    $html = ob_get_contents();
    ob_end_clean();
        
    return $html;
}

// Construct search form
add_shortcode( 'ipres_search_form', 'ipress_search_form_shortcode' );

// Pre search form, for js validation etc
add_action( 'pre_get_search_form', function(){} );

//end
