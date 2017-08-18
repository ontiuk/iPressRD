<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Initialise theme customizer
 * 
 * @package     iPress\Customizer
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
//  Customizer Functionality
//----------------------------------------------

/**
 * Set up customizer
 *
 * @param   object  WP_Customiser_Manager
 */
function ipress_customizer( $wpm ) {
  
  // $wp_customize calls go here.
  
  // uncomment the below lines to remove the default customize sections 
  // $wpm->remove_section( 'title_tagline' );
  // $wpm->remove_section( 'colors' );
  // $wpm->remove_section( 'background_image' );
  // $wpm->remove_section( 'static_front_page' );
  // $wpm->remove_section( 'nav' );

  // uncomment the below lines to remove the default controls
  // $wpm->remove_control( 'blogdescription' );
  
  // uncomment the following to change the default section titles
  // $wpm->get_section( 'colors' )->title = __( 'Theme Colors' );
  // $wpm->get_section( 'background_image' )->title = __( 'Images' );
}

// Register customizer function
add_action( 'customize_register', 'ipress_customizer' );

//end
