<?php

/**
 * iPress - WordPress Base Theme                       
 * ==========================================================
 *
 * Register post types & taxonomies
 * 
 * @package     iPress\Custom
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
//  Register Custom Post Types
//----------------------------------------------

/**
 * Create Custom Post Type
 *
 * $post_types = [ 'cpt' => [ 
 *       'name'          => 'CPT', 
 *       'plural'        => 'CPTs',
 *       'description'   => 'This is the CPT post type', 
 *       'supports'      => [ 'title', 'editor', 'thumbnail' ],
 *       'taxonomies     => [ 'cpt_type' ],
 *       'args'          => [], 
 *   ] ];
 */
function ipress_register_post_types() {

    // filterable custom post types
    $post_types = [];

    // iterate custom post types...
    foreach ( $post_types as $k=>$v ) {

        // sanitize post type... a-z_- only
        $post_type = sanitize_key( str_replace( ' ', '-', $k ) );
        
        // set up singluar & plural
        $singular       = ( isset( $v['name'] ) && !empty( $v['name'] ) ) ? $v['name'] : ucfirst( $post_type );
        $plural         = ( isset( $v['plural'] ) && !empty( $v['plural'] ) ) ? $v['plural'] : ucfirst( $singular ) . 's'; 
        $description    = ( isset( $v['description'] ) && !empty( $v['description'] ) ) ? $v['description'] : 'This is the ' . $singular . ' post type';
        
        // set up post type labels - Rename to suit, common options here, full list at: https://codex.wordpress.org/Function_Reference/register_post_type
        $labels = [
            'name'          => __( $plural, 'ipress' ),
            'singular_name' => __( $singular, 'ipress' ),
            'add_new_item'  => __( 'Add New ' . $singular, 'ipress' ),
            'edit_item'     => __( 'Edit ' . $singular, 'ipress' ),
            'new_item'      => __( 'New ' . $singular, 'ipress' ),
            'view_item'     => __( 'View ' . $singular, 'ipress' ),
            'search_items'  => __( 'Search ' . $singular, 'ipress' ),
            'all_items'     => __( 'All ' . $plural, 'ipress' ), 
            'archives'      => __( $singular . ' Archives', 'ipress' ),
            'not_found'     => __( 'No ' . $plural . ' found', 'ipress' ),
            'not_found_in_trash' => __( 'No ' . $plural . ' found in Trash', 'ipress' ),
            'insert_into_item'   => __( 'Insert into ' . $singular, 'ipress' ),
            'uploaded_to_this_item' => __( 'Uploaded to this ' . $singular, 'ipress' ),
            'parent_item_colon'  => ''
        ];

        // set up post type support
        $supports = ( isset( $v['supports'] ) && is_array( $v['supports'] ) && !empty( $v['supports'] ) ) ? $v['supports'] : [
            'title',
            'editor',
            'thumbnail'
        ];

        // set up post type args - common options here, full list at: https://codex.wordpress.org/Function_Reference/register_post_type
        $defaults = ( isset( $v['args'] ) && is_array( $v['args'] ) && !empty( $v['args'] ) ) ? $v['args'] : [];
        $args = array_merge( [
            'labels'                => $labels,
            'description'           => __( $description, 'ipress' ),
            'public'                => true,
            'query_var'             => true,
            'rewrite'               => [ 'slug' => $post_type, 'with_front' => false ], 
            'hierarchical'          => false, 
            'has_archive'           => true, 
            'supports'              => $supports, 
            'can_export'            => true
        ], $defaults );
    
        // associated taxonomies
        $taxonomies = ( isset( $v['taxonomies'] ) && is_array( $v['taxonomies'] ) && !empty( $v['taxonomies'] ) ) ? $v['taxonomies'] : '';
        if ( !empty( $taxonomies ) ) {
            $args['taxonomies'] = $taxonomies;
        }

        // register new post type... no flush rewrite here: after_switch_theme?
        register_post_type( $post_type, $args );
    }
}

// Generate & register custom post types
add_action( 'init', 'ipress_register_post_types' ); 

//----------------------------------------------
//  Register Taxonomies
//----------------------------------------------

/**
 * Register taxonomies & assign to post types
 * 
 * $taxonomies = [ 'tax_name' => [ 
 *       'name'          => 'Tax Name', 
 *       'plural'        => 'Taxes',
 *       'description'   => 'This is the Taxonomy name', 
 *       'post-types'    => [ 'cpt' ], 
 *       'args'          => [] 
 * ] ];
 */
function ipress_register_taxonomies() {

    // taxonomies to deal with
    $taxonomies = [];

    // iterate taxonomies...
    foreach ( $taxonomies as $k=>$v ) {

        // sanitize taxonomy... a-z_- only
        $taxonomy = sanitize_key( str_replace( ' ', '-', $k ) );
        
        // set up singluar & plural
        $singular = ( isset( $v['name'] ) && !empty( $v['name'] ) ) ? $v['name'] : ucfirst( $taxonomy );
        $plural   = ( isset( $v['plural'] ) && !empty( $v['plural'] ) ) ? $v['plural'] : ucfirst( $singular ) . 's'; 
        $description = ( isset( $v['description'] ) && !empty( $v['description'] ) ) ? $v['description'] : 'This is the ' . ucfirst( $singular ) . ' taxonomy';
 
        // set up taxonomy labels
        $labels = [
            'name'              => __( $plural, 'ipress' ), 
            'singular_name'     => __( $singular, 'ipress' ), 
            'all_items'         => __( 'All ' . $plural, 'ipress' ), 
            'edit_item'         => __( 'Edit ' . $singular, 'ipress' ), 
            'view_item'         => __( 'View ' . $singular, 'ipress' ), 
            'update_item'       => __( 'Update ' . $singular, 'ipress' ), 
            'add_new_item'      => __( 'Add New ' . $singular, 'ipress' ), 
            'new_item_name'     => __( 'New ' . $singular . ' Name', 'ipress' ), 
            'parent_item'       => __( 'Parent ' . $singular, 'ipress' ), 
            'parent_item_colon' => __( 'Parent ' . $singular . ':', 'ipress' ), 
            'popular_items'     => __( 'Popular ' . $plural, 'ipress' ), 
            'search_items'      => __( 'Search ' . $plural, 'ipress' ), 
            'not_found'         => __( 'No ' . $plural . ' found', 'ipress' ), 
            'separate_items_with_commas' => __( 'Separate ' . $plural . ' with commas', 'ipress' ), 
            'add_or_remove_items' => __( 'Add or remove ' . $plural, 'ipress' ), 
            'choose_from_the_most_used' => __( 'Chose from the most used ' . $plural, 'ipress' ), 
        ];

        // set up taxonomy args
        $defaults = ( isset( $v['args'] ) && is_array( $v['args'] ) && !empty( $v['args'] ) ) ? $v['args'] : [];
        $args = array_merge( [
            'labels'            => $labels,
            'description'       => $description, 
            'show_admin_column' => true, 
            'rewrite'           => [ 'slug' => $taxonomy, 'with_front' => false ]
        ], $defaults );

        // assign to post types?
        $post_types = ( isset( $v['post-types'] ) && is_array( $v['post-types'] ) && !empty( $v['post-types'] ) ) ? $v['post-types'] : [];

        // register taxonomy
        register_taxonomy( $taxonomy, $post_types, $args );
    }
}

// Generate & register taxonomies
add_action( 'init', 'ipress_register_taxonomies' ); 

//----------------------------------------------
//  Theme Setup Fuctionality
//----------------------------------------------

/**
 * Flush rewrite rules for custom post types & taxonomies after switching theme
 */
function ipress_flush_rewrite_rules() { 
    ipress_register_post_types();
    ipress_register_taxonomies();
    flush_rewrite_rules(); 
}

// Flush rewrite rules after theme activation
add_action( 'after_switch_theme', 'ipress_flush_rewrite_rules' );

//end
