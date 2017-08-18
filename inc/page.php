<?php

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Set page support
 * 
 * @package     iPress\Page
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
// Page Support Action & Filter Functions
//----------------------------------------------  

/**
 * Set up page excerpt & tag support
 */
function ipress_page_support() {

    // page excerpt support
    $page_excerpt_support = (bool)apply_filters( 'ipress_page_excerpt', false );
    if ( $page_excerpt_support ) { add_post_type_support( 'page', 'excerpt' ); }

    // page tag support   
    $page_tag_support = (bool)apply_filters( 'ipress_page_tags', false );
    if ( $page_tag_support ) { register_taxonomy_for_object_type( 'post_tag', 'page' ); }
}

// Page excerpt & tag support
add_action( 'init', 'ipress_page_support');

/**
 * Ensure all tags are included in queries
 */
function ipress_page_tags_query( $wp_query ) {
    $page_tags = (bool)apply_filters( 'ipress_ts_page_tags', false );
    if ( $page_tags && $wp_query->get( 'tag' ) ) { $wp_query->set( 'post_type', 'any' ); }
}

// Tags query support
add_action( 'pre_get_posts', 'ipress_page_tags_query' );

//end
