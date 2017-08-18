<?php

/**
 * iPress - WordPress Base Theme                       
 * ==========================================================
 *
 * Template redirect rules
 * 
 * @package     iPress\Redirect
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

//---------------------------------------------
// Redirection Rules
//---------------------------------------------

/**
 * Theme redirect rules for old themes
 */
function ipress_template_redirect() {

    global $wp_version;

    // stop preview from displaying for old themes
    if ( isset( $_GET['preview'] ) && version_compare( $wp_version, IPRESS_THEME_WP, '<' ) ) {
        wp_die( sprintf( __( '%s requires at least WordPress version %s. You are running version %s', 'ipress' ), get_stylesheet(), IPRESS_THEME_WP, $wp_version ) );
    }
}

// Old themes no preview
add_action( 'template_redirect', 'ipress_template_redirect' );

//end
