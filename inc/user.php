<?php

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * User profile action & filter functions
 * 
 * @package     iPress\User
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
//  User Functions 
//---------------------------------------------

/**
 * Add custom headline and description to author archive pages.
 *
 * @return null Return early if not author archive or not page one.
 */
function ipress_author_title_description() {

    // not author
    if ( ! is_author() ) { return; }

    // set headline
    $headline = get_the_author_meta( 'display_name', (int) get_query_var( 'author' ) );
    $headline   = $headline ? sprintf( '<h1 %s>%s</h1>', ipress_attr( 'archive-title' ), strip_tags( $headline ) ) : '';

    // display headline if set
    if ( $headline ) {
        echo sprintf( '<div %s>%s</div>', ipress_attr( 'author-archive-description' ), $headline );
    }
}

//------------------------------------------
//  User Profile Functions
//------------------------------------------

//------------------------------------------
// User Profile Actions & Filters
//------------------------------------------

//end
