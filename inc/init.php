<?php

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Initialise action & filter functions
 * 
 * @package     iPress\Init
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
//  Version Control
//----------------------------------------------

/**
 * Process theme switching version control
 * 
 * global $wp_version
 */
function ipress_switch_theme() {

    global $wp_version;

    // test WordPress versioning
    if ( version_compare( $wp_version, IPRESS_THEME_WP, '<' ) ) {

        // action switch & admin notice
        unset( $_GET['activated'] );
        add_action( 'admin_notices', function() {
            global $wp_version;
            $message = sprintf( __( 'iPress requires at least WordPress version %s. You are running version %s.', 'ipress' ), IPRESS_THEME_WP, $wp_version );
            printf( '<div class="error"><p>%s</p></div>', $message );
        } );
        switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
        return false;
    }

    // php versioning
    if ( version_compare( phpversion(), IPRESS_THEME_PHP, '<' ) ) {

        // theme info message.
        unset( $_GET['activated'] );
        add_action( 'admin_notices', function() {
            $message = sprintf( __( 'PHP version <strong>%s</strong> is required You are using <strong>%s</strong>. Please update or contact your hosting company', 'ipress' ), phpversion(), IPRESS_THEME_PHP );
            printf( '<div class="update-nag"><p>%s</p></div>', $message );
        } );
        switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
        return false;
    }

    // default ok
    return true;
}

//----------------------------------------------
// Theme SetUp & Initialization
//----------------------------------------------

/**
 * Clean the WordPress Header
 * - The WordPress head contains multiple meta & link records,
 * - many of which are not required, are detrimental, and slow loading.
 * - All are removed here by default. Comment out/remove entries to reactivate
 */
function ipress_head_cleanup() {
    
    // post & comment feeds    
    remove_action( 'wp_head', 'feed_links', 2 );

    // category feeds
    remove_action( 'wp_head', 'feed_links_extra', 3 );

    // editURI link    
    remove_action( 'wp_head', 'rsd_link' );

    // windows live writer    
    remove_action( 'wp_head', 'wlwmanifest_link' );

    // remove meta robots tag from wp_head
    remove_action( 'wp_head', 'noindex', 1 );

    // index link
    remove_action( 'wp_head', 'index_rel_link' ); 

    // previous link
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );

    // start link
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );

    // links for adjacent posts    
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 ); 

    // XHTML generator
    $disable_version = function() { return ''; };
    add_filter( 'the_generator', $disable_version );
    remove_action( 'wp_head', 'wp_generator' ); 

    // shortlink for the page    
    remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

    // remove WP version from scripts    
    $remove_wp_ver_css_js = function( $src, $handle ) { return ( strpos( $src, 'ver=' ) ) ? remove_query_arg( 'ver', $src ) : $src; }; 
    add_filter( 'style_loader_src', $remove_wp_ver_css_js, 9999, 10, 2 ); 
    add_filter( 'script_loader_src', $remove_wp_ver_css_js, 9999, 10, 2 );

    // remove 'text/css' from enqueued stylesheet
    add_filter( 'style_loader_tag', 'ipress_style_remove' );

    // remove inline Recent Comment Styles from wp_head()
    add_action( 'widgets_init', 'ipress_head_comments' );

    // canonical refereneces    
    remove_action( 'wp_head', 'rel_canonical' );
}

/**
 * Remove wp_head() injected Recent Comment styles
 *
 * global $wp_widget_factory;
 */
function ipress_head_comments(){

    global $wp_widget_factory;

    // remove head comments
    remove_action( 'wp_head', [
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ] );

    // check and remove
    if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
        remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
    }
}

/**
 * Remove 'text/css' from our enqueued stylesheet
 *
 * @param   string
 * @return  string
 */
function ipress_style_remove($tag) {
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

//----------------------------------------------
//  Title Tag Functions
//----------------------------------------------

/**
 * Define the pre_get_document_title callback 
 *
 * @return  string
 */
function ipress_pre_get_document_title() { 
    
    // home page?
    if ( ipress_is_home_page() ) {

        // get details
        $title = get_bloginfo( 'name' );        
        $sep = (string)apply_filters( 'ipress_document_title_separator', '-' );
        $app = (bool)apply_filters( 'ipress_home_doctitle_append', true );

        // sanitize title
        $title = wptexturize( $title );
        $title = convert_chars( $title );
        $title = esc_html( $title );
        $title = capital_P_dangit( $title );

        // return title        
        return ( $app ) ? $title . ' ' . $sep . ' ' . get_bloginfo( 'description' ) : $title;
    }

    // default
    return ''; 
} 

/**
 * define the document_title_separator callback 
 *
 * @param   string $sep
 * @return  string
 */
function ipress_document_title_separator( $sep ) { 

    // get the theme setting and set if needed...
    $ts_sep = (string)apply_filters( 'ipress_doctitle_separator', '' );

    // return title separator
    return ( empty( $ts_sep ) ) ? $sep : esc_html( $ts_sep ); 
} 

/**
 * Define the document_title_parts callback 
 *
 * @param   array $title
 * @return  array
 */
function ipress_document_title_parts( $title ) { 

    // home page or not amending inner pages
    if ( is_front_page() || ipress_is_home_page() ) { return $title; }
    
    // append site name?
    $app_site_name = (bool)apply_filters( 'ipress_append_site_name', true );
    $title['site'] = ( $app_site_name ) ? get_bloginfo( 'name' ) : '';

    // return
    return $title; 
}

//----------------------------------------------
//  Theme Features
//----------------------------------------------

/**
 * Post Setup Features
 */
function ipress_setup_features() {
    
    // enable Threaded Comments
    // add_action( 'get_header', 'ipress_enable_threaded_comments'); 

    // remove the bloody awful emojicons! Worse than Pokemon!
    add_action( 'init', 'ipress_disable_emojicons', 1 );

    // admin bar - All Users
    // ipress_hide_adminbar( true );
    
    // admin bar - Non Admin Users Only
    // ipress_hide_adminbar( false );
}

/**
 * Threaded Comments for single posts
 */
function ipress_enable_threaded_comments() {
    
    // restrictions: front-end single post page
    if ( !is_admin() && ( is_singular() AND comments_open() AND ( get_option( 'thread_comments' ) == 1) ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

/**
 * Remove emojicons support - hurrah!
 */
function ipress_disable_emojicons() { 

    // remove head/foot styles & scripts    
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );    
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );  
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

    // editor functionality
    add_filter( 'tiny_mce_plugins', 'ipress_disable_emojis_tinymce' );
} 

/**
 *  Remove tinymce emoji support
 *
 *  @param  array $plugins
 *  @return array
 */
function ipress_disable_tinymce_emoji( $plugins ) { 
    return ( is_array( $plugins ) ) ? array_diff( $plugins, [ 'wpemoji' ] ) : []; 
} 

/**
 *  Remove adminbar for non-admin logged in users
 *
 *  @param boolean $all
 */
function ipress_hide_adminbar( $all ) {

    // all users or logged in non-admin users
    if ( $all ) { 
        add_filter( 'show_admin_bar', '__return_false' );
    } else { 
        if ( !current_user_can( 'administrator' ) && !is_admin() ) { add_filter( 'show_admin_bar', '__return_false' ); }
    }
}

//end
