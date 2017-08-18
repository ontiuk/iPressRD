<?php

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Custom Post Type & Taxonomy Admin Columns Functionality
 * 
 * @package     iPress\Columns
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
//  Custom Post Type Columns Functionality
//
//  - Adds select filter for post type
//  - Sets post type column sortable
//----------------------------------------------

// Post Types to process
$ipress_post_types = [];

// Iterate through post types?
foreach ( $ipress_post_types as $ipress_post_type ) {

    // process columns
    add_filter( 'manage_' . $ipress_post_type . '_posts_columns', 'ipress_' . $ipress_post_type . '_custom_columns' );      
    add_action( 'manage_' . $ipress_post_type . '_posts_custom_column', 'ipress_' . $ipress_post_type . '_do_custom_column', 10, 2 );       
    add_filter( 'manage_edit-' . $ipress_post_type . '_sortable_columns', 'ipress_' . $ipress_post_type . '_sortable_columns' );
}

//------------------------------------------------------------------------------------------------------------------------------
//  Example Post Type Functions
//
//  - Modify & duplicate per post_type via function name change
//  - e.g.  add_filter( 'manage_' . $ipress_post_type . '_posts_columns', 'ipress_' . $ipress_post_type . '_custom_columns' );      
//------------------------------------------------------------------------------------------------------------------------------

/**
 * Sets the post-type custom columns
 *
 * @param   array
 * @return  array
 */
function ipress_xxx_custom_columns( $columns ) { 
    return $columns;
}

/**
 * Sets the post-type custom columns. Overwrite at child class level
 *
 * @param   string
 * @param   integer
 */
function ipress_xxx_do_custom_column( $column, $post_id ) {}

/**
 * Sets the post-type sortable custom columns
 *
 * @param   array
 * @return  array
 */
function ipress_xxx_sortable_columns( $columns ) { 
    return $columns;
}

//----------------------------------------------
//  Taxonomy Custom Fields
//
//  - Set custom taxonomies via array or filter
//  - Sets taxonomy columns in post type list
//----------------------------------------------

// In built taxonomy types
$ipress_built_in = [ 'category', 'post_tag', 'link_category' ];

// Taxonomies to process
$ipress_taxonomies = [];

// Iterate through taxonomies?
foreach ( $ipress_taxonomies as $ipress_tax ) {

    // custom taxonomy or built in?
    if ( in_array( $ipress_tax, $ipress_built_in ) ) {
    
        // add fields
        add_action( 'add_' . $ipress_tax . '_form_fields', 'ipress_' . $ipress_tax . '_add_taxonomy_custom_fields', 10, 1 );
        add_action( 'created_' . $ipress_tax, 'ipress_' . $ipress_tax . '_save_taxonomy_custom_fields', 10, 1 );
    
        // edit fields
        add_action( 'edit_' . $ipress_tax . '_form_fields', 'ipress_' . $ipress_tax . '_edit_taxonomy_custom_fields', 10, 2 );
        add_action( 'edited_' . $ipress_tax, 'ipress_' . $ipress_tax . '_update_taxonomy_custom_fields', 10, 2 );

        // delete fields
        add_action( 'deleted_' . $ipress_tax, 'ipress_' . $ipress_tax . '_delete_taxonomy_custom_fields', 10, 1 );
            
    } else {
            
        // add fields
        add_action( $ipress_tax . '_add_form_fields', 'ipress_' . $ipress_tax . '_add_taxonomy_custom_fields', 10, 1 );
        add_action( 'created_' . $ipress_tax, 'ipress_' . $ipress_tax . '_save_taxonomy_custom_fields', 10, 1 );

        // edit fields
        add_action( $ipress_tax . '_edit_form_fields', 'ipress_' . $ipress_tax . '_edit_taxonomy_custom_fields', 10, 2 );
        add_action( 'edited_' . $ipress_tax, 'ipress_' . $ipress_tax . '_update_taxonomy_custom_fields', 10, 2 );

        // delete fields
        add_action( 'deleted_' . $ipress_tax, 'ipress_' . $ipress_tax . '_delete_taxonomy_custom_fields', 10, 1 );
    }
    
    // display columns
    add_filter( 'manage_edit-' . $ipress_tax . '_columns', 'ipress_' . $ipress_tax . '_taxonomy_column' ); 
    add_filter( 'manage_' . $ipress_tax . '_custom_column', 'ipress_' . $ipress_tax . '_add_taxonomy_column', 10, 3 );
}

//------------------------------------------------------------------------------------------------------------------
//  Example Taxonomy Functions
//
//  - Modify & duplicate per taxonomy via function name change
//  - e.g.  add_filter('manage_edit-' . $ipress_tax . '_columns', 'ipress_' . $ipress_tax . '_taxonomy_column' ); 
//------------------------------------------------------------------------------------------------------------------

/**
 * Add fields
 */
function ipress_xxx_add_taxonomy_custom_fields() {}
    
/**
 * Edit fields
 */
function ipress_xxx_edit_taxonomy_custom_fields() {}

/**
 * Save & Update
 */
function ipress_xxx_save_taxonomy_custom_fields() {}
function ipress_xxx_update_taxonomy_custom_fields() {}
function ipress_xxx_delete_taxonomy_custom_fields() {}

/**
 * Add extra column
 *
 * @param   array
 * @return  array
 */
function ipress_xxx_taxonomy_column( $columns ) { return $columns; }

/**
 * Display extra column
 *
 * @param   string
 * @param   string
 * @param   string
 * @return  string
 */
function ipress_xxx_add_taxonomy_column( $out, $column, $label ) {
    return $out; 
}

//end
