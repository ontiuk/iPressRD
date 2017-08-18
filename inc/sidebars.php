<?php

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Sidebar & widget areas support
 * 
 * @package     iPress\Sidebars
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
// Sidebar Functionality
//----------------------------------------------

/**
 * Set sidebar defaults
 *
 * @param   array   $sidebar
 * @return  array
 */
function ipress_sidebar_defaults ( $args ) {

    $defaults = [
        'before_widget' => ipress_html( [
            'html'  => '<section id="%1$s" class="widget %2$s"><div class="widget-wrap">',
            'echo'  => false,
        ] ),
        'after_widget'  => ipress_html( [
            'html'  => '</div></section>' . PHP_EOL,
            'echo'  => false
        ] ),
        'before_title'  => '<h4 class="widget-title widgettitle">',
        'after_title'   => '</h4>' . PHP_EOL
    ];

    // filterable sidebar defaults
    $defaults = apply_filters( 'ipress_sidebar_' . $args['id'] . '_defaults', $defaults );
    $args = wp_parse_args( $args, $defaults );

    // return sidebar params
    return $args;
}

//----------------------------------------------
// Sidebars Action & Filter Functions
//----------------------------------------------

/**
 * Register theme sidebars
 *
 * @return array
 */
function ipress_register_sidebars() {

    // default sidebars
    $default_sidebars = apply_filters( 'ipress_default_sidebars', [
        'primary'       => [ 
            'name'          => __( 'Primary Sidebar', 'ipress' ),
            'description'   => __( 'This is the primary sidebar for two-column and full-width layouts.', 'ipress' )
        ],
        'secondary'    => [ 
            'name'          => __( 'Secondary Sidebar', 'ipress' ),
            'description'   => __( 'This is the secondary sidebar for two-column and full-width layouts.', 'ipress' )
        ],
        'header'       => [
           'name'          => __( 'Header Sidebar', 'ipress' ),
           'description'   => __( 'This is the header sidebar.', 'ipress' )
        ]
    ] );

    // footer widgets
    $footer_sidebars = apply_filters( 'ipress_footer_sidebars', [
        'footer-left'   => [
           'name'          => __( 'Footer Left Sidebar', 'ipress' ),
           'description'   => __( 'This is the footer left sidebar for all layouts.', 'ipress' )
        ],
        'footer-center' => [
           'name'          => __( 'Footer Center Sidebar', 'ipress' ),
           'description'   => __( 'This is the footer center sidebar for all layouts.', 'ipress' )
        ],
        'footer-right'  => [
           'name'          => __( 'Footer Right Sidebar', 'ipress' ),
           'description'   => __( 'This is the footer right sidebar for all layouts.', 'ipress' )
        ] 
    ] );
 
    // custom widgets
    $custom_sidebars = apply_filters( 'ipress_custom_sidebars', [] );

    // set default sidebars
    return array_merge( $default_sidebars, $footer_sidebars, $custom_sidebars );
}

/**
 * Kickstart sidebar widget areas
 *
 * @global  $ipress_sidebars
 * @uses    register_sidebar()
 */
function ipress_sidebars_init() {

    // get sidebars
    $ipress_sidebars = ipress_register_sidebars();

    // register widgets
    foreach ( $ipress_sidebars as $id => $sidebar ) {

        // re-register sidebar ID
        $sidebar['id'] = $id;

        // need name...
        if ( !isset( $sidebar['name'] ) || empty( $sidebar['name'] ) ) { continue; }
 
        // ...and description
        if ( !isset( $sidebar['description'] ) || empty( $sidebar['description'] ) ) {
            $sidebar['description'] = 'This is the ' . $sidebar['name'] . ' sidebar description';
        }

        // set up defaults for each sidebar
        $sidebar = ipress_sidebar_defaults( $sidebar );    

        // register sidebar
        register_sidebar( $sidebar );    
    }
}

// Core sidebar initialisation
add_action( 'widgets_init', 'ipress_sidebars_init' );    

//end
