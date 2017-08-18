<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Theme functions file
 * 
 * @package     iPress\Functions
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */

//----------------------------------------------
//  Theme Defines
//----------------------------------------------

// Theme Name & Versioning
define( 'IPRESS_THEME_NAME', 'iPress' );
define( 'IPRESS_THEME_VERSION', '1.0.2' );
define( 'IPRESS_THEME_RELEASE_DATE', date_i18n( 'F j, Y', '1477440000' ) );
define( 'IPRESS_THEME_WP', 4.5 );
define( 'IPRESS_THEME_PHP', 5.4 );

// Directory Structure
define( 'IPRESS_DIR', dirname( __FILE__ ) ); //get_template_directory()
define( 'IPRESS_ROUTE_DIR',     IPRESS_DIR . '/route' );
define( 'IPRESS_TEMPLATES_DIR', IPRESS_DIR . '/templates' );
define( 'IPRESS_PARTIALS_DIR',  IPRESS_DIR . '/partials' );
define( 'IPRESS_LANG_DIR',      IPRESS_DIR . '/languages' );
define( 'IPRESS_MEDIA_DIR',     IPRESS_DIR . '/media' );
define( 'IPRESS_INCLUDES_DIR',  IPRESS_DIR . '/inc' );
define( 'IPRESS_LIB_DIR',       IPRESS_DIR . '/lib' );
define( 'IPRESS_ADMIN_DIR',     IPRESS_DIR . '/admin' );
define( 'IPRESS_FONTS_DIR',     IPRESS_DIR . '/fonts' );

// Includes Directory Structure
define( 'IPRESS_JS_DIR',            IPRESS_INCLUDES_DIR . '/js' );
define( 'IPRESS_CSS_DIR',           IPRESS_INCLUDES_DIR . '/css' );
define( 'IPRESS_CONTROLS_DIR',      IPRESS_INCLUDES_DIR . '/controls' );
define( 'IPRESS_CONTROLS_JS_DIR',   IPRESS_CONTROLS_DIR . '/js' );
define( 'IPRESS_SHORTCODES_DIR',    IPRESS_INCLUDES_DIR . '/shortcodes' );
define( 'IPRESS_WIDGETS_DIR',       IPRESS_INCLUDES_DIR . '/widgets' );

// Directory Paths
define( 'IPRESS_URL',           get_template_directory_uri() );
define( 'IPRESS_ROUTE_URL',     IPRESS_URL . '/route' );
define( 'IPRESS_TEMPLATES_URL', IPRESS_URL . '/templates' );
define( 'IPRESS_PARTIALS_URL',  IPRESS_URL . '/partials' );
define( 'IPRESS_LANG_URL',      IPRESS_URL . '/languages' );
define( 'IPRESS_MEDIA_URL',     IPRESS_URL . '/media' );
define( 'IPRESS_INCLUDES_URL',  IPRESS_URL . '/inc' );
define( 'IPRESS_LIB_URL',       IPRESS_URL . '/lib' );
define( 'IPRESS_ADMIN_URL',     IPRESS_URL . '/admin' );
define( 'IPRESS_FONTS_URL',     IPRESS_URL . '/fonts' );

// Includes Directory Paths
define( 'IPRESS_JS_URL',            IPRESS_INCLUDES_URL . '/js' );
define( 'IPRESS_CSS_URL',           IPRESS_INCLUDES_URL . '/css' );
define( 'IPRESS_CONTROLS_URL',      IPRESS_INCLUDES_URL . '/controls' );
define( 'IPRESS_CONTROLS_JS_URL',   IPRESS_CONTROLS_URL . '/js' );
define( 'IPRESS_SHORTCODES_URL',    IPRESS_INCLUDES_URL . '/shortcodes' );
define( 'IPRESS_WIDGETS_URL',       IPRESS_INCLUDES_URL . '/widgets' );

//----------------------------------------------
//  Version Control
//----------------------------------------------

// Prevent switching & activation for old WP versions
add_action( 'after_switch_theme', 'ipress_switch_theme' );

//----------------------------------------------
//  Theme SetUp
//----------------------------------------------

/**
 * Required default content width for image manipulation
 * - Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function ipress_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'ipress_content_width', 840 );
}
add_action( 'after_setup_theme', 'ipress_content_width', 0 );

/**
 * Set up core theme settings & functionality
 */
function ipress_setup_theme() {

    // localisation Support 
    load_theme_textdomain( 'ipress', IPRESS_LANG_DIR );

    // enables post and comment RSS feed links to head 
    add_theme_support('automatic-feed-links'); 

    // make WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // add thumbnail theme support & post type support
    // - add_theme_support( 'post-thumbnails' ); 
    // - add_theme_support( 'post-thumbnails', $post_types ); 
    add_theme_support( 'post-thumbnails' ); 

    // set thumbnail default size: width, height, crop
    // - set_post_thumbnail_size( 50, 50 ); // 50px x 50px, prop resize
    // - set_post_thumbnail_size( 50, 50, true ); // 50px x 50px, hard crop
    // - set_post_thumbnail_size( 50, 50, [ 'left', 'top' ] ); // 50px x 50px, hard crop from top left
    // - set_post_thumbnail_size( 50, 50, [ 'center', 'center' ] ); // 50 px x 50px, crop from center

    // core image sizes overrides
    // - add_image_size( 'large', 1024, '', true ); // Large Image 
    // - add_image_size( 'medium', 768, '', true ); // Medium Image 
    // - add_image_size( 'small', 320, '', true);   // Small Image 
 
    // custom image sizes
    // - add_image_size( 'custom-size', 220 );                  // 220px wide, relative height, soft proportional crop mode
    // - add_image_size( 'custom-size-prop', 220, 180 );        // 220px x 180px, soft proportional crop
    // - add_image_size( 'custom-size-prop-height', 9999, 180); // 180px height: proportion resize 
    // - add_image_size( 'custom-size', 220, 180, true );       // 220 pixels wide by 180 pixels tall, soft proportional crop mode

    // add menu support 
    add_theme_support( 'menus' ); 

    // register main navigation menu locations
    register_nav_menus( [ 
        'primary'   => __( 'Primary Navigation Menu', 'ipress' ),
        'secondary' => __( 'Secondary Navigation Menu', 'ipress' ),
    ] );

    // register additional navigation menu locations
    // register_nav_menus( [ 
    //   'social'    => __( 'Social Menu', 'ipress' ),
    //   'header'    => __( 'Header Menu', 'ipress' ) 
    // ] );

    // enable support for HTML5 markup: 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    add_theme_support( 'html5', [
        'comment-list',
        'search-form',
        'comment-form',
        'gallery',
        'caption'
    ] );
 
    // add post-format support: 'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
    // - add_theme_support( 'post-formats', [ 'image', 'link' ] ); 

    // enable support for custom logo - default off
    // see: https://developer.wordpress.org/themes/functionality/custom-logo/
    // logo_defaults = [
    //   'height'      => 80,
    //   'width'       => 250,
    //   'flex-height' => true,
    //   'flex-width'  => true,
    //   'header-text' => [ get_bloginfo( 'name' ), get_bloginfo( 'description' ) ]
    // ];
    // - add_theme_support( 'custom-logo', $logo_defaults );

    // enable support for custom headers
    // see: https://developer.wordpress.org/themes/functionality/custom-headers/    
    // $header_defaults = [
    //    'default-image'          => '',
    //    'random-default'         => false,
    //    'width'                  => 0,
    //    'height'                 => 0,
    //    'flex-height'            => false,
    //    'flex-width'             => false,  
    //    'default-text-color'     => '', 
    //    'header-text'            => true,
    //    'uploads'                => true,
    //    'wp-head-callback'       => '',
    //    'admin-head-callback'    => '',
    //    'admin-preview-callback' => ''
    // ];
    // - add_theme_support( 'custom-header', $header_defaults ); 

    // enable support for custom backgrounds - default false
    // see: https://codex.wordpress.org/Custom_Backgrounds
    // $background_defaults = [ 
    //     'default-color'         => '', 
    //     'default-image'         => '', 
    //     'wp-head-callback'      => '_custom_background_cb',
    //     'admin-head-callback'   => '',
    //     'admin-preview-callback' => ''
    // ];
    // - add_theme_support( 'custom-background', $background_defaults ); 
    
    // add Woocommerce support?
    // add_theme_support( 'woocommerce' ); 

    // clean up the messy WordPress header
    add_action( 'init', 'ipress_head_cleanup' );

    // newer title tag hooks - requires title-tag support
    if ( current_theme_supports( 'title-tag' ) ) {
        add_filter( 'pre_get_document_title', 'ipress_pre_get_document_title' ); 
        add_filter( 'document_title_separator', 'ipress_document_title_separator', 10, 1 ); 
        add_filter( 'document_title_parts', 'ipress_document_title_parts', 10, 1 ); 
    }

    // setup additional features
    ipress_setup_features();
}
add_action( 'after_setup_theme', 'ipress_setup_theme' );

//----------------------------------------------
//  Includes
//----------------------------------------------

// Load Scripts & Styles
require_once( IPRESS_INCLUDES_DIR . '/load-scripts.php' );
require_once( IPRESS_INCLUDES_DIR . '/load-styles.php' );

// Functions: actions & filters
require_once( IPRESS_INCLUDES_DIR . '/functions.php' );
require_once( IPRESS_INCLUDES_DIR . '/helper.php' );

// Custom Post-Type, Taxonomy & Meta Data
require_once( IPRESS_INCLUDES_DIR . '/custom.php' );
require_once( IPRESS_INCLUDES_DIR . '/columns.php' );

// Theme Setup & Initialisation
require_once( IPRESS_INCLUDES_DIR . '/init.php' );

// Cron Support: actions & filters
require_once( IPRESS_INCLUDES_DIR . '/cron.php' );

// Main query manipulation
require_once( IPRESS_INCLUDES_DIR . '/query.php' );

// WordPress Customizer support
require_once( IPRESS_INCLUDES_DIR . '/customizer.php' );

// Admin functionlity
require_once( IPRESS_INCLUDES_DIR . '/admin.php' );

// Layout template functions
require_once( IPRESS_INCLUDES_DIR . '/layout.php' );

// Images & Media template functions
require_once( IPRESS_INCLUDES_DIR . '/media.php' );

// Navigation template functions
require_once( IPRESS_INCLUDES_DIR . '/navigation.php' );

// Redirect template functions
require_once( IPRESS_INCLUDES_DIR . '/redirect.php' );

// Rewrites template functions
require_once( IPRESS_INCLUDES_DIR . '/rewrites.php' );

// Shortcodes functionality
require_once( IPRESS_INCLUDES_DIR . '/shortcodes.php' );

// Sidebars functionality
require_once( IPRESS_INCLUDES_DIR . '/sidebars.php' );

// Structure functionality
require_once( IPRESS_INCLUDES_DIR . '/structure.php' );

// Template functionality
require_once( IPRESS_INCLUDES_DIR . '/template.php' );

// Widgets functionality
require_once( IPRESS_INCLUDES_DIR . '/widgets.php' );

// Page Support: actions & filters
require_once( IPRESS_INCLUDES_DIR . '/page.php' );

// User Profile: actions & filters
require_once( IPRESS_INCLUDES_DIR . '/user.php' );

// Functions: theme functions, actions & filters
require_once( IPRESS_INCLUDES_DIR . '/template-functions.php' );

// Ajax Functions: actions & filters
require_once( IPRESS_INCLUDES_DIR . '/ajax.php' );

//----------------------------------------------
//  Libraries
//----------------------------------------------

//----------------------------------------------
//  Theme Support
//  - Add SetUp Overrides Here
//----------------------------------------------

// Theme Setup Configuration: actions, filters etc
require_once( IPRESS_INCLUDES_DIR . '/config.php' );

//end
