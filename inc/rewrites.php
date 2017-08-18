<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Theme rewrite rules and custom query vars
 * 
 * @package     iPress\Rewrites
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
//  Rewrite Rules
//----------------------------------------------

//----------------------------------------------
//  Query Vars
//----------------------------------------------

/**
 * Add a new query var
 *
 * @param   array
 * @return  array
 */
function ipress_query_vars( $qvars ) {
    
    // filterable query vars
    $query_vars = apply_filters( 'ipress_query_vars', [] );

    // return modified query vars
    return ( empty( $query_vars ) ) ? $qvars : array_merge( $qvars, array_map( sanitize_title_with_dashes, $query_vars ) );   
}

// Add a new query
add_filter( 'query_vars', 'ipress_query_vars' , 10, 1 );

/**
 * Redirect page rewrite tag
 */
function ipress_rewrite_tag() {}

// Custom rewrite tag rules
add_action( 'init', 'ipress_rewrite_tag', 10, 0 );

//end
