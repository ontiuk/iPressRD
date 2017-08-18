<?php

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Load scripts, inline js & fonts
 * 
 * @package     iPress\Scripts
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
//  Scripts, Styles & Fonts
//----------------------------------------------

/**
 * Load core, header & footer scripts 
 */
function ipress_load_scripts() { 
 
    // front end only
    if ( is_admin() ) { return; }

    // core scripts: [ 'script-name', 'script-name2' ... ]
    $core = [ 'jquery' ];

    // header scripts: [ 'label' => [ 'path', (array)dependencies, 'version' ] ... ]
    $header = [
        'modernizr' => [ IPRESS_JS_URL . '/modernizr.2.8.3.min.js', [], NULL ]
    ];

    // footer scripts: [ 'label' => [ 'path', (array)dependencies, 'version' ] ... ]
    $footer = [];

    // plugin scripts: [ 'label' => [ 'js-path', (array)dependencies, 'version' ] ... ]
    $plugins = [];

    // page scripts: [ 'label' => [ 'template', 'path', (array)dependencies, 'version' ] ... ];
    $page = [];

    // custom scripts: [ 'label' => [ 'path', (array)dependencies, 'version' ] ... ];
    $custom = [
        'theme' => [ IPRESS_JS_URL . '/theme.js', [ 'jquery' ], NULL ] 
    ];

    // localize scripts: [ 'label' => [ 'name' => name, trans => function/path ] ]
    $local = [
        'theme'     => [ 
            'name'  => 'theme', 
            'trans' => [ 
                'home_url' => home_url(), 
                'ajax_url' => admin_url( 'admin-ajax.php' ) 
            ] 
        ]
    ];

    // register & Enqueue core scripts
    if ( !empty( $core ) ) {
        foreach ( $core as $k=>$v ) { wp_enqueue_script( $v ); }
    }

    // register & Enqueue header scripts
    foreach ( $header as $k=>$v ) { 
        wp_register_script( $k, $v[0], $v[1], $v[2], false ); 
        if ( array_key_exists( $k, $local ) ) {
            $h = $local[$k]; wp_localize_script( $k, $h['name'], $h['trans'] ); 
        }
        wp_enqueue_script( $k );
    }

    // register & Enqueue footer scripts
    foreach ( $footer as $k=>$v ) { 
        wp_register_script( $k, $v[0], $v[1], $v[2], true ); 
        if ( array_key_exists( $k, $local ) ) {
            $h = $local[$k]; wp_localize_script( $k, $h['name'], $h['trans'] ); 
        }
        wp_enqueue_script( $k );
    }

    // register & Enqueue plugin scripts
    foreach ( $plugins as $k=>$v ) { 
        wp_register_script( $k, $v[0], $v[1], $v[2], true ); 
        if ( array_key_exists( $k, $local ) ) {
            $h = $local[$k]; 
            wp_localize_script( $k, $h['name'], $h['trans'] ); 
        }
        wp_enqueue_script( $k );
    }

    // page templates in footer head
   foreach ( $page as $k=>$v ) {
        if ( is_page_template( $v[0] ) ) {
            foreach ( $v as $k2=>$v2 ) {
                wp_register_script( $k, $v2[1], $v2[2], $v2[3], true ); 
                wp_enqueue_script( $k );
            }
        }
   }

    // add base footer scripts
    foreach ( $custom as $k=>$v ) { 
        wp_register_script( $k, $v[0], $v[1], $v[2], true ); 
        if ( array_key_exists( $k, $local ) ) {
            $h = $local[$k]; wp_localize_script( $k, $h['name'], $h['trans'] ); 
        }
        wp_enqueue_script( $k );
    }
}

/**
 * Load IE conditional header scripts
 *
 * @global  $wp_version
 * @global  $wp_scripts
 */
function ipress_conditional_scripts() {

    global $wp_version, $wp_scripts;

    // front end only
    if ( is_admin() ) { return; }

    // solution after WP4.3 when wp_scripts->add_data works with enqueue_scripts  
    if ( version_compare( $wp_version, '4.3', '>=' ) ) { 
        
        wp_enqueue_script( 'html5-shiv', 'https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js', [], NULL );
        wp_enqueue_script('respond-min', 'https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js', [], NULL);

        $wp_scripts->add_data( 'html5-shiv', 'conditional', 'lt IE 9' );
        $wp_scripts->add_data( 'respond-min', 'conditional', 'lt IE 9' );

        return; 
    }

    // else put conditional stuff here: HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries
    echo sprintf( '<!--[if lt IE 9]><script src=%s></script><![endif]-->', 'https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js' );
    echo sprintf( '<!--[if lt IE 9]><script src=%s></script><![endif]-->', 'https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js' );
}

//----------------------------------------------
//  Script, Style & Fonts Loader Actions 
//----------------------------------------------

// Load scripts
add_action( 'wp_enqueue_scripts', 'ipress_load_scripts' ); 

// Conditional header scripts
add_action( 'wp_enqueue_scripts', 'ipress_conditional_scripts' ); 

//----------------------------------------------
//  Header & Footer Scripts
//----------------------------------------------

/**
 * Load inline header scripts
 * - Must have <script></script> wrapper
 */
function ipress_header_scripts() {

    // capture output
    ob_start();
    // put scripts between tags
?>
<?php
    // get & display header scripts
    $output = ob_get_clean();
    echo $output;
}

// inline header scripts 
add_filter( 'ipress_header_scripts', 'do_shortcode' );
add_action( 'wp_head', 'ipress_header_scripts', 99 );

/**
 * freeform footer scripts
 * - must be full text inside <script></script> wrapper
 */
function ipress_footer_scripts() {

    // capture output
    ob_start();
    // put scripts between tags
?>

<?php
    // get & display header scripts
    $output = ob_get_clean();
    echo $output;
}

// footer Scripts
add_filter( 'ipress_footer_scripts', 'do_shortcode' );
add_action( 'wp_footer', 'ipress_footer_scripts' );

/**
 * Load analytics scripts
 * - Must be full analytics text inside <script></script> wrapper
 *
 * <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
 * <script>
 *     (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
 *     function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
 *     e=o.createElement(i);r=o.getElementsByTagName(i)[0];
 *     e.src='https://www.google-analytics.com/analytics.js';
 *     r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
 *     ga('create','UA-XXXXX-X','auto');ga('send','pageview');
 * </script>
 */
function ipress_analytics_script() {
    
    // capture output
    ob_start();
    // put analytics script between tags
?>

<?php
    // get & display header scripts
    $output = ob_get_clean();

    // send outpur
    echo $output;
}

// Analytics
add_filter( 'ipress_analytics_script', 'do_shortcode' );
add_action( 'wp_head', 'ipress_analytics_script', 99 );

//end
