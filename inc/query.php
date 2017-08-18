<?php

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Main query manipulation
 * 
 * @package     iPress\Query
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
//  Main Query Filters
//----------------------------------------------

/**
 * Set the query duplicate restrictions
 *
 * @return  string
 */
function ipress_posts_distinct() { return 'DISTINCT'; }

// Eliminate duplicates in query results
//add_filter( 'posts_distinct', 'ipress_posts_distinct' );

/**
 * Set the Group By clause
 *
 * @global  $wpdb
 * @param   string
 * @return  string
 */
function ipress_posts_groupby( $groupby ) {
    
    global $wpdb;

    return $groupby;
}

// Set the GROUP BY clause for the SQL query 
//add_filter( 'posts_groupby', 'ipress_posts_groupby' );

/**
 * Set the table join parameters
 * 
 * @param   string
 * @return  string
 */
function ipress_posts_join( $join ) { return $join; }

// Set the table JOIN clause for the SQL query 
//add_filter( 'posts_join', 'ipress_posts_join' );

/**
 * Set the return limiter
 * 
 * @param   integer
 * @param   string
 * @return  string
 */
function ipress_posts_limit( $limit, $query ) {
    return $limit;
}

// Set the LIMIT clause for the SQL query 
//add_filter( 'post_limits', 'ipress_posts_limit', 10, 2 );

/**
 * Set the orderby clause
 * 
 * @param   string
 * @return  string
 */
function ipress_posts_orderby( $orderby ) {
    return $orderby;
}

// Set the ORDER BY clause for the SQL query 
//add_filter( 'posts_orderby', 'ipress_posts_orderby' );

/**
 * Set the paged join clause
 * 
 * @param   string
 * @return  string
 */
function ipress_posts_join_paged( $join_paged ) {
    return $join_paged; 
}

// Set the POSTS JOIN PAGED clause for the SQL query 
//add_filter( 'posts_join_paged','ipress_posts_join_paged' );

/**
 * Set the where clause
 * 
 * @param   string
 * @return  string
 */
function ipress_posts_where( $where ) { return $where; }

// Set the POSTS WHERE clause for the SQL query 
//add_filter( 'posts_where' , 'ipress_posts_where' );

//----------------------------------------------  
// WP_Query Manipulation
//----------------------------------------------  

/**
 * Customise the CPT query if a taxonomy term is used... modify 'cpt'
 *
 * @param   object  WP_Query
 */
function ipress_cpt_archive( $query ) {

    // main query & cpt post-type
    $post_types = apply_filters( 'ipress_post_type_archives', [] );
    if ( $query->is_main_query() && !is_admin() && $query->is_post_type_archive( $post_types ) ) {

        // only if taxonomy set modify query
        if ( is_tax() ) {

            $tax_obj = $query->get_queried_object();
            
            $tax_query = [
                    'taxonomy'  => $tax_obj->taxonomy,
                    'field'     => 'slug',
                    'terms'     => $tax_obj->slug,
                    'include_children' => false
            ];
            
            $query->tax_query->queries[] = $tax_query;
            $query->query_vars['tax_query'] = $query->tax_query->queries;
        }
    }
}

// Customise the post types query by taxonomy term
//add_action( 'pre_get_posts', 'ipress_post_type_archives' );

/**
 * Exclude uncategorised posts from home page
 *
 * @param   object  WP_Query
 * @return  string
 */
function ipress_exclude_category( $query ) {

    // main query & home page
    if ( $query->is_home() && $query->is_main_query() ) {
        $exc_cats = apply_filters( 'ipress_exclude_category', ['-1'] );
        if ( $exc_cats ) {
            $cats = array_map( 'ipress_exclude_category_map', $exc_cats );
            $cats = join( ',', $cats );
            $query->set( 'cat', $cats );
        }
    }
}

/**
 * Map excluded categories to negatives
 *
 * @param string
 * @return integer
 */ 
function exclude_category_map( $cat ) {
    $cat = (int)$cat;
    return ( $cat <= 0 ) ? $cat : ( -1 * $cat );
}

// Exclude category posts from home page - defaults to unclassified
add_action( 'pre_get_posts', 'ipress_exclude_category' );

/**
 * Use deals post type in Search
 *
 * @param object $query WP_Query object
 */
function ipress_search_include( $query ) {

    // main query search
    if ( !is_admin() && $query->is_main_query() && $query->is_search ) {
        $post_types = apply_filters( 'ipress_query_search_include', [] );
        if ( $post_type ) {
            $query->set( 'post_type', [ $post_types ] );
        }
    }
}

// Add custom post types to Search
//add_action( 'pre_get_posts', 'ipress_search_include' );

//end
