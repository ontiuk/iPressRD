<?php

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Load css and inline styles
 * 
 * @package     iPress\Styles
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

/**
 * Load CSS styles files
 */
function ipress_load_styles() { 

    // front end only
    if ( is_admin() ) { return; }

    // core styles: [ 'style-name', 'style-name' ... ];
    $core = [];

    // set styles: [ 'label' => [ 'path', (array)depn, 'version' ] ... ]
    $header = [
        'normalize'  => [ IPRESS_CSS_URL . '/' . 'normalize.min.css', [], NULL ]
    ];
   
    // plugin styles: [ 'label' => [ 'path', (array)depn, 'version' ] ... ]
    $plugins = [];
    
    // core styles: [ 'label' => [ 'path', (array)depn, 'version' ] ... ];
    $theme = [ 
        'theme'  => [ IPRESS_URL . '/' . 'style.css', [], NULL ]
    ];

    // load & register Core CSS in order
    foreach ( $this->core as $k=>$v ) { 
        wp_register_style( $k, $v[0], $v[1], $v[2] ); 
        wp_enqueue_style( $k ); 
    }

    // load & register CSS in order
    foreach ( $header as $k=>$v ) {
        wp_register_style( $k, $v[0], $v[1], $v[2] ); 
        wp_enqueue_style( $k ); 
    }
    
    // load plugin styles 
    foreach ( $plugins as $k=>$v ) { 
        wp_register_style( $k, $v[0], $v[1], $v[2] ); 
        wp_enqueue_style( $k ); 
    }

    // add core styles last
    foreach ( $theme as $k=>$v ) { 
        wp_register_style( $k, $v[0], $v[1], $v[2] ); 
        wp_enqueue_style( $k ); 
    }
}

/**
 * Load custom front-end fonts 
 */
function ipress_load_fonts() { 

    // front end only
    if ( is_admin() ) { return; }

    // set core theme fonts: [ 'label' => [ 'path', (array)depn, 'version' ] ... ];
    $core = [];

    // set custom pugin fonts:  [ 'label' => [ 'path', (array)depn, 'version' ] ... ];
    $plugins = [];

    // set remote theme fonts: [ 'label' => [ 'path', (array)depn, 'version' ] ... ];
    $remote = [];

    // set theme fonts e.g. google: [ 'family' => 'Open+Sans:400,700|Oswald:700', 'subset' => 'latin,latin-ext' ]
    $family = [ 
        'family' => 'Open+Sans|Lato:400,700'
    ];

    // register plugin core fonts
    foreach ( $core as $k=>$v ) { 
        wp_enqueue_style( $v ); 
    }

    // register plugin fonts
    foreach ( $plugins as $k=>$v ) { 
        wp_register_style( $k, $v[0], $v[1], $v[2] ); 
        wp_enqueue_style( $k ); 
    }

    // register remote theme fonts, load from theme
    foreach ( $remote as $k=>$v ) { 
        wp_register_style( $k, $v[0], $v[1], $v[2] ); 
        wp_enqueue_style( $k ); 
    }

    // register & enqueue google family fonts 
    if ( !empty( $family ) ) {
        wp_register_style( 'custom-fonts', add_query_arg( $family, '//fonts.googleapis.com/css' ), [], null );
        wp_enqueue_style( 'custom-fonts' );
    }
}

/**
 * Load conditional styles
 */
function ipress_conditional_styles() {

    global $wp_styles;

    // front end only
    if ( is_admin() ) { return; }

    // load our stylesheet for IE9
    wp_enqueue_style( 'ie9', IPRESS_CSS_URL . '/ie9.css', [] );
    $wp_styles->add_data( 'ie9', 'conditional', 'IE 9' );
}

/**
 * Load customiser styles
 */
function ipress_customize_styles() {
    wp_enqueue_style( 'ipress-customize', IPRESS_CSS_URL . '/customize.css' );
}

//----------------------------------------------
//  Script, Style & Fonts Loader Actions 
//----------------------------------------------

// Main styles 
add_action( 'wp_enqueue_scripts', 'ipress_load_styles' ); 

// Fonts & typography
add_action( 'wp_enqueue_scripts', 'ipress_load_fonts' ); 

// Conditional header styles
add_action( 'wp_enqueue_scripts', 'ipress_conditional_styles' ); 

// Customiser custom css
add_action( 'customize_controls_enqueue_scripts', 'ipress_customize_styles' );

//----------------------------------------------
//  Header & Footer Scripts / Styles
//----------------------------------------------

/**
 * Load inline header css
 * - Must be full css text inside <style></style> wrapper
 */
function ipress_header_styles() {

    // capture output   
    ob_start();

    // put script between tags
?>
<!--[if lte IE 9]><p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p><![endif]-->
<?php
    $output = ob_get_clean();

    // send output
    echo $output;
}

// Header Inline CSS
add_filter( 'ipress_header_styles', 'do_shortcode' );
add_action( 'wp_head', 'ipress_header_styles', 12 );

/**
 * Output IE9 & below browser check
 * - Place in header below <body> tag
 */
function ipress_ie_version() {

    // capture output
    ob_start();
?>
<!--[if lte IE 9]><p class="update-nag">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p><![endif]-->
<?php
    $output = ob_get_clean();

    // send output
    echo $output;
}

//end
