<?php
/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Helper functions
 * 
 * @package     iPress\Helper
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
//  WordPress Helper Functions
//
//  - ipress_the_category_id
//  - ipress_get_category_id
//  - ipress_the_category_parent_id
//  - ipress_get_category_parent_id
//  - ipress_the_category_slug
//  - ipress_get_category_slug
//  - ipress_the_category_name
//  - ipress_get_category_name
//  - ipress_the_category_count
//  - ipress_get_category_count
//  - ipress_taxonomy_title_description
//  - ipress_the_tax_term_count
//  - ipress_get_tax_term_count
//  - ipress_get_posts
//  - ipress_post_count
//  - ipress_get_post_list
//  - ipress_cpt_archive_title_description
//  - ipress_the_id_by_name
//  - ipress_get_id_by_name
//  - ipress_id_by_type_name
//  - ipress_get_user
//  - ipress_the_user_id
//  - ipress_get_user_id
//  - ipress_the_user_name
//  - ipress_get_user_name
//  - ipress_the_user_level
//  - ipress_get_user_level
//----------------------------------------------

//----------------------------------------------
//  Category Functions
//----------------------------------------------

/**
 * Output the current category ID
 */
function ipress_the_category_id() {
    echo get_the_category()[0]->cat_ID;
}

/**
 * Return the current category ID
 * 
 * @return integer
 */
function ipress_get_category_id() {
    return get_the_category()[0]->cat_ID;
}

/**
 * Output the current category parent ID
 */
function ipress_the_category_parent_id() {
    echo get_the_category()[0]->category_parent;
}

/**
 * Return the current category parent ID
 * 
 * @return integer
 */
function ipress_get_category_parent_id() {
    return get_the_category()[0]->category_parent;
}

/**
 * Output the current category slug
 */
function ipress_the_category_slug() {
    echo get_the_category()[0]->category_nicename;
}

/**
 * Return the current category slug
 * 
 * @return string
 */
function ipress_get_category_slug() {
    return get_the_category()[0]->category_nicename;
}

/**
 * Output the current category name
 */
function ipress_the_category_name() {
    echo get_the_category()[0]->cat_name;
}

/**
 * Return the current category name
 *
 * @return string
 */
function ipress_get_category_name() {
    return get_the_category()[0]->cat_name;
}

/**
 * Output the category count
 *
 * @param string|integer    $cat    category ID or slug. Empty for current category
 */
function ipress_the_category_count( $cat = '' ) {
    echo ipress_get_category_count( $cat );
}

/**
 * Return the category count
 * 
 * @global  $wpdb
 * @param   string|integer    $cat    category ID or slug. Empty for current category
 * @return  integer
 */
function ipress_get_category_count( $cat = '' ) {

    global $wpdb;

    // current category
    if ( empty( $cat ) ) {
        return (int)get_the_category()[0]->category_count;
    }

    // category by ID
    if ( is_numeric( $cat ) ) {
        $q = 'SELECT ' . $wpdb->term_taxonomy . '.count FROM ' . $wpdb->terms . ', ' . $wpdb->term_taxonomy . ' ' . 
             'WHERE ' . $wpdb->terms . '.term_id=' . $wpdb->term_taxonomy . '.term_id ' .  
             'AND ' . $wpdb->term_taxonomy . '.term_id=%d';
        $qs = $wpdb->prepare( $q, absint( $cat ) );
        return (int)$wpdb->get_var( $qs );
    }

    // category by slug
    $q = 'SELECT ' . $wpdb->term_taxonomy . '.count FROM ' . $wpdb->terms . ', ' . $wpdb->term_taxonomy . ' ' .
         'WHERE ' . $wpdb->terms . '.term_id=' . $wpdb->term_taxonomy . '.term_id ' . 
         'AND' .  $wpdb->terms . '.slug=%s';
    $qs = $wpdb->prepare( $q, strtolower( $cat ) );

    // return category count
    return (int)$wpdb->get_var( $qs );
}

//---------------------------------------------
//  Taxonomy Functions
//---------------------------------------------

/**
 * Add custom headline and / or description to category / tag / taxonomy archive pages.
 *
 * @global WP_Query $wp_query Query object.
 * @return null Return early if not the correct archive page, not page one, or no term meta is set.
 */
function ipress_taxonomy_title_description() {

    global $wp_query;

    // correct archive types
    if ( ! is_category() && ! is_tag() && ! is_tax() ) { return; }

    // get term
    $term = is_tax() ? get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ) : $wp_query->get_queried_object();
    if ( ! $term ) { return; }

    // get headline
    $headline = sprintf( '<h1 %s>%s</h1>', ipress_attr( 'archive-title' ), strip_tags( $term->name ) );

    // output description
    echo sprintf( '<div %s>%s</div>', ipress_attr( 'taxonomy-archive-description' ), $headline );
}

/**
 * Output the taxonomy term count
 * @param string $tax taxonomy name
 * @param string|integer $term term id or name
 */
function ipress_the_tax_term_count( $tax, $term ) {
    echo ipress_get_tax_term_count( $tax, $term );
}

/**
 * Return the taxonomy term count
 *
 * @global  $wpdb
 * @param   string $tax taxonomy name
 * @param   string|integer $term term id or name
 * @return  integer
 */
function ipress_get_tax_term_count( $tax, $term ) {

    global $wpdb;

    // current category
    if ( empty( $tax ) || empty( $term ) ) { return 0; }

    // category by ID
    if ( is_numeric( $term ) ) {
        $q = 'SELECT ' . $wpdb->term_taxonomy . '.count FROM ' . $wpdb->terms . ', ' . $wpdb->term_taxonomy . ' ' . 
             'WHERE ' . $wpdb->terms . '.term_id=' . $wpdb->term_taxonomy . '.term_id ' .
             'AND ' . $wpdb->term_taxonomy . '.taxonomy=%s ' . 
             'AND ' . $wpdb->term_taxonomy . '.term_id=%d';
        $qs = $wpdb->prepare( $q, $tax, absint( $term ) );
        return (int)$wpdb->get_var( $qs );
    }

    // category by slug
    $q = 'SELECT ' . $wpdb->term_taxonomy . '.count FROM ' . $wpdb->terms . ', ' . $wpdb->term_taxonomy . ' ' .
         'WHERE ' . $wpdb->terms . '.term_id= ' . $wpdb->term_taxonomy . '.term_id ' .
         'AND ' . $wpdb->term_taxonomy . '.taxonomy=%s ' . 
         'AND ' . $wpdb->terms . '.slug=%s';
    $qs = $wpdb->prepare( $q, $tax, strtolower( $term ) );

    // return term count
    return (int)$wpdb->get_var( $qs );
}

//----------------------------------------------
//  Post Type Functions
//----------------------------------------------

/**
 * Get a list of posts by type
 *
 * @param   string  $type
 * @return  array
 */
function ipress_get_posts( $type ) {

    // no type?
    if ( empty( $type ) ) { return; }

    // set up the query args
    $args = [  
        'post_type'         => $type,
        'post_status'       => 'publish',
        'posts_per_page'    => -1 
    ];

    // get the posts
    $the_query = new WP_Query( $args );

    // return the posts
    return $the_query->get_posts();
}

/**
 * Post count by post type 
 *
 * @param   string  $type
 * @return  integer
 */
function ipress_post_count( $type ){

    // get post count by type
    $num_posts = wp_count_posts( $type );

    // retrieve post count
    return (int)$num_posts->publish;
}

/**
 * Return list of the custom post type  
 *
 * @global  $post
 * @param   string  $type
 * @return  array
 */
function ipress_get_post_list( $type ) {

    global $post;

    $posts = [];

    // set up query
    $args = [ 
        'post_type'      => $type,
        'post_status'    => 'publish',
        'posts_per_page' => -1 
    ];
    $the_query = new WP_Query( $args );

    // the loop
    $posts = [];
    if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            $posts[$post->ID] = $post->post_title;
        }
    }
    wp_reset_postdata();

    // return posts
    return $posts;
}

/**
 * Add custom headline and description to relevant custom post type archive pages.
 *
 * @return null Return early if not on relevant post type archive.
 */
function ipress_cpt_archive_title_description() {

    // valid post type
    if ( ! is_post_type_archive() ) { return; }

    // get headline
    $headline = post_type_archive_title( '', false );
    $headline = ( $headline ) ? sprintf( '<h1 %s>%s</h1>', ipress_attr( 'archive-title' ), strip_tags( $headline ) ) : '';

    // output headline
    echo sprintf( '<div %s>%s</div>', ipress_attr( 'cpt-archive-description' ), $headline . $intro_text );
}

//----------------------------------------------
//  Page Functions
//----------------------------------------------

/**
 * Output post/page id by name
 * 
 * @param string post slug
 */
function ipress_the_id_by_name( $name ) {
    echo get_page_by_title( $name, OBJECT, 'post' )->ID;
}

/**
 * Get post/page id by name
 * 
 * @param string post slug
 * @return integer id
 */
function ipress_get_id_by_name( $name ) {
    return get_page_by_title( $name, OBJECT, 'post' )->ID;
}

/**
 * Returns the cpt by slug
 * 
 * @param string $type
 * @param string $name
 * @return integer
 */
function ipress_id_by_type_name( $type, $name ) {
    get_page_by_path( $name, OBJECT, $type )->ID;
}

//----------------------------------------------
//  User Data
//----------------------------------------------

/**
 * Get current user ID
 *
 * @global  $userdata
 * @return  object
 */
function ipress_get_user() {

    global $userdata;
    get_currentuserinfo();

    // return user data
    return $userdata;
}

/**
 * Output current user ID
 */
function ipress_the_user_id() {
    echo ipress_get_user_id();
}

/**
 * Get current user ID
 *
 * @return integer
 */
function ipress_get_user_id() {

    global $userdata;
    get_currentuserinfo();

    // return user id
    return (int)$userdata->ID;
}

/**
 * Get current user name
 */
function ipress_the_user_name() {
    echo ipress_get_user_name();
}

/**
 * Get current user name
 * 
 * @global  $userdata
 * @return string
 */
function ipress_get_user_name() {

    global $userdata;
    get_currentuserinfo();

    // return user login name
    return $userdata->user_login;
}

/**
 * Output current user level
 */
function ipress_the_user_level() {
    echo ipress_get_user_level();
}

/**
 * Get current user level
 * 
 * @global  $userdata
 * @return  string
 */
function ipress_get_user_level() {

    global $userdata;
    get_currentuserinfo();

    // return user level
    return $userdata->user_level;
}

//end
