<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * User functionality shortcodes
 *
 * @package     iPress\Shortcodes
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */

// Access restriction
if ( ! defined( 'ABSPATH' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit;
}

//---------------------------------------------
//  User Shortcodes 
//---------------------------------------------

/**
 *  Retrieve current user info
 *
 * @param   array|string $atts 
 * @return  string
 */
function ipress_user_info_shortcode( $atts ) {

    global $userdata;
    get_currentuserinfo();

    $defaults = [
        'after'    => '',
        'before'   => ''
    ];
    
    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_user_info' );

    // Generate output
    $output = sprintf( '<span class="ipress-user-info">%s</span>', $atts['before'] . print_r( $userdata, true ) . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_user_info_shortcode', $output, $atts );
}

// Get current user - should be used via do_shortcode
add_shortcode( 'ipress_user_info', 'ipress_user_info_shortcode' );

/**
 *  Retrieve current user ID
 *
 * @param   array|string $atts 
 * @return  string
 */
function ipress_user_id_shortcode( $atts ) {

    global $userdata;
    get_currentuserinfo();

    $defaults = [
        'after'    => '',
        'before'   => ''
    ];
    
    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_user_id' );

    // Generate output
    $output = sprintf( '<span class="ipress-user-id">%s</span>', $atts['before'] . $userdata->ID . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_user_id_shortcode', $output, $atts );
}

// Get current user id 
add_shortcode( 'ipress_user_id', 'ipress_user_id_shortcode' );

/**
 *  Retrieve current user name
 *
 * @param   array|string $atts 
 * @return  string
 */
function ipress_user_name_shortcode( $atts ) {

    global $userdata;
    get_currentuserinfo();

    $defaults = [
        'after'    => '',
        'before'   => ''
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_user_name' );

    // Generate output
    $output = sprintf( '<span class="ipress-user-name">%s</span>', $atts['before'] . $userdata->user_login . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_user_name_shortcode', $output, $atts );
}

// Get current user name 
add_shortcode( 'ipress_user_name', 'ipress_user_name_shortcode' );

/**
 *  Retrieve current user level
 *
 * @param   array|string $atts 
 * @return  string
 */
function ipress_user_level_shortcode( $atts ) {

    global $userdata;
    get_currentuserinfo();

    $defaults = [
        'after'    => '',
        'before'   => ''
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_user_level' );

    // Generate output
    $output = sprintf( '<span class="ipress-user-level">%s</span>', $atts['before'] . $userdata->user_level . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_user_level_shortcode', $output, $atts );
}

// Get current user level 
add_shortcode( 'ipress_user_level', 'ipress_user_level_shortcode' );

//end
