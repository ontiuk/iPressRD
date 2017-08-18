<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Links and Edit Shortcodes
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
//  Links Shortcodes                      
//---------------------------------------------

/**
 *  Return to Top link
 *
 * @param   array|string $atts Shortcode attributes - Empty string if no attributes
 * @return  string
 */
function ipress_backtotop_shortcode( $atts ) {

    $defaults = [
        'after'    => '',
        'before'   => '',
        'href'     => '#top',
        'nofollow' => true,
        'text'     => __( 'Return to top', 'ipress' ),
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_backtotop' );

    // Set & generate output
    $nofollow = $atts['nofollow'] ? 'rel="nofollow"' : '';
    $output = sprintf( '%s<a href="%s" %s>%s</a>%s', $atts['before'], esc_url( $atts['href'] ), $nofollow, $atts['text'], $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_backtotop_shortcode', $output, $atts );
}

// Back To Top Shortcode
add_shortcode( 'ipress_backtotop', 'ipress_backtotop_shortcode' );

/**
 * Adds the visual copyright notice
 *
 * @param   array|string 
 * @return  string 
 */
function ipress_copyright_shortcode( $atts ) {

    $defaults = [
        'after'     => '',
        'before'    => '',
        'copyright' => __( '&#x000A9;', 'ipress' ),
        'first'     => '',
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_copyright' );

    // Generate output
    $output = $atts['before'] . $atts['copyright'] . '&nbsp;';
    if ( '' != $atts['first'] && date( 'Y' ) != $atts['first'] ) {
        $output .= $atts['first'] . '&#x02013;';
    }
    $output .= date( 'Y' ) . $atts['after'];

    // Return filterable output
    return apply_filters( 'ipress_copyright_shortcode', $output, $atts );
}

// Copyright Shortcode
add_shortcode( 'ipress_copyright', 'ipress_copyright_shortcode' );

/**
 * Adds link to the iPress Homepage
 *
 * @param   array|string $atts 
 * @return  string Shortcode output
 */
function ipress_link_shortcode( $atts ) {

    $defaults = [
        'after'  => '',
        'before' => '',
        'url'    => 'http://ipress.uk/',
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_link' );

    // Generate output
    $output = $atts['before'] . '<a href="' . esc_url( $atts['url'] ) . '">iPress</a>' . $atts['after'];

    // Return filterable output
    return apply_filters( 'ipress_link_shortcode', $output, $atts );
}

// iPress Site Shortcode
add_shortcode( 'ipress_link', 'ipress_link_shortcode' );

/**
 * Adds link to WordPress - http://wordpress.org/
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_wordpress_link_shortcode( $atts ) {

    $defaults = [
        'after'  => '',
        'before' => '',
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_wordpress_link' );
    $output = sprintf( '%s<a href="%s">%s</a>%s', $atts['before'], 'http://wordpress.org/', 'WordPress', $atts['after'] );

    // Return filterable attributes
    return apply_filters( 'ipress_wordpress_link_shortcode', $output, $atts );
}

// WordPress Link Shortcode
add_shortcode( 'ipress_wordpress_link', 'ipress_wordpress_link_shortcode' );

/**
 * Adds admin login / logout link
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_loginout_shortcode( $atts ) {

    $defaults = [
        'after'    => '',
        'before'   => '',
        'redirect' => '',
    ];

    // Get ahortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'footer_loginout' );

    // Set the link
    $link = ( ! is_user_logged_in() ) ? '<a href="' . esc_url( wp_login_url( $atts['redirect'] ) ) . '">' . __( 'Log in', 'ipress' ) . '</a>' :
        '<a href="' . esc_url( wp_logout_url( $atts['redirect'] ) ) . '">' . __( 'Log out', 'ipress' ) . '</a>';

    // Generate output
    $output = $atts['before'] . apply_filters( 'loginout', $link ) . $atts['after'];

    // Return filterable output
    return apply_filters( 'ipress_loginout_shortcode', $output, $atts );
}

// Login / Logout Link Shortcode 
add_shortcode( 'ipress_loginout', 'ipress_loginout_shortcode' );

//end
