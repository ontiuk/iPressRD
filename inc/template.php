<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Theme template rules
 * 
 * @package     iPress\Template
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
// Template Override Functions
//---------------------------------------------

/*
// Add /partials path to template partials search paths
add_filter( 'template_locations', function( $locations ) {
    unset( $locations['theme-compat'] );
    array_walk( $locations, function ( &$v, $k, $s ) { $v .= $s; }, '/partials' );
    return $locations;
}, 10, 1 );
 */

// Add /partials & /templates path to template partials search paths
add_filter( 'template_locations', function( $locations ) {
    unset( $locations['theme-compat'] );
    $new_locat = [
        'template-partials'     => get_template_directory() . '/partials',
        'template-templates'    => get_template_directory() . '/templates'
    ];
    return array_merge( $new_locat, $locations );
}, 10, 1 );

/*
// Modify template partial search path for e.g. header file
add_filter( 'locate_template', function( $location, $template_names ) {
    if ( in_array( 'header.php', $template_names ) ) {
        $location = ( is_child_theme() ) ? get_stylesheet_directory() . '/header.php' : get_template_directory() . '/partials/header.php';
    }
    return $location;
}, 10, 2 );
*/

//---------------------------------------------
// Theme Template Hooks  
//---------------------------------------------

/**
 * Reset path for main template files, except index.php
 *
 * @param   string
 * @return  string
 */
add_filter( 'template_include', function ( $template ) {

    // WooCommerce override: allow Woocommerce version of template_include to take priority if set
    if ( class_exists( 'Woocommerce' ) ) { return $template; }

    // test restrictions
    return ( is_child_theme() ) ? $template : IPRESS_ROUTE_DIR . '/' . basename( $template );
}, 99 );

//end
