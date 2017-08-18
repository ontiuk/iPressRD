<?php 

/**
 * iPress - WordPress Base Theme                       
 * ==========================================================
 *
 * Theme shortcodes
 *
 * @package     iPress\Shortcodes
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */

// access restriction
if ( ! defined( 'ABSPATH' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit;
}

//----------------------------------------------  
// Shortcodes
//----------------------------------------------  

// Include shortcode files by type
include_once( IPRESS_SHORTCODES_DIR . '/category.php' );
include_once( IPRESS_SHORTCODES_DIR . '/date.php' );
include_once( IPRESS_SHORTCODES_DIR . '/links.php' );
include_once( IPRESS_SHORTCODES_DIR . '/media.php' );
include_once( IPRESS_SHORTCODES_DIR . '/post.php' );
include_once( IPRESS_SHORTCODES_DIR . '/user.php' );

//---------------------------------------------
//  Shortcode Actions
//---------------------------------------------

// Allow shortcodes in Dynamic Sidebar
add_filter( 'widget_text', 'do_shortcode' );          

// Remove <p> tags in Dynamic Sidebars
add_filter( 'widget_text', 'shortcode_unautop' ); 

// Remove auto <p> tags in Excerpt
add_filter( 'the_excerpt', 'shortcode_unautop' ); 

// Allows Shortcodes to be executed in Excerpt
add_filter( 'the_excerpt', 'do_shortcode' );     

//end
