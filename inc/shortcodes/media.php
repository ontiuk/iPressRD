<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Images and media shortcodes
 *
 * @package     iPress\Shortcodes
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
// Images and Media Shortcodes 
//---------------------------------------------

/**
 *  Retrieve attachment meta data
 *
 * @param   array|string $atts 
 * @return  string
 */
function ipress_attachment_meta_shortcode( $atts ) {

    $defaults = [
        'after'         => '',
        'before'        => '',
        'attachment'    => '',
        'size'          => ''
    ];

    // get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_attachment_meta' );

    // attachment ID required
    if ( empty( $attachemnt_id ) ) { return false; }

    // get attachment data
    $attachment = get_post( $atts['attachment_id'] );

    // not valid
    if ( empty( $attachment ) ) { return false; }

    // get attachment data
    $attachment = get_post( $attachment_id );
    if ( empty( $attachment ) ) { return false; }

    // set thumbnail   
    $att_data_thumb = wp_get_attachment_image_src( $attachment_id, $size );

    // generate attachment data
    $data = [];
    $data['alt']            = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
    $data['caption']        = $attachment->post_excerpt;
    $data['description']    = $attachment->post_content;
    $data['href']           = $attachment->guid;
    $data['src']            = $att_data_thumb[0];
    $data['title']          = $attachment->post_title;

    // generate output
    $output = sprintf( '<span %s>', ipress_attr( 'attachment-meta' ) ) . $atts['before'] . print_r( $data, true ) . $atts['after'] . '</span>';

    // return filterable output
    return apply_filters( 'ipress_attachment_meta_shortcode', $output, $atts );
}

// Attachment meta data shortcode
add_shortcode( 'ipress_attachment_meta', 'ipress_attachment_meta_shortcode' );

/**
 * Get post attachements by attachement mime type 
 *
 * @param   array|string $atts 
 * @return  string
 */
function ipress_post_attachments_shortcode( $atts ) {

    $defaults = [
        'after'             => '',
        'before'            => '',
        'post_mime_type'    => '',
        'numberposts'       => -1,
        'post_parent'       => ''
    ];

    // get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_attachments' );

    // parent & type required
    if ( empty( $atts['post_parent'] ) || empty( $atts['post_mime_type'] ) ) { return false; }

    // get attachment data
    $attachments = get_posts( [
        'post_type'         => 'attachment',
        'post_mime_type'    => $atts['post_mime_type'],
        'numberposts'       => $atts['numberposts'],
        'post_parent'       => $atts['post_parent']
    ] );

    // generate output
    $output = sprintf( '<span %s>', ipress_attr( 'post-attachment' ) ) . $atts['before'] . print_r( $attachments, true ) . $atts['after'] . '</span>';

    // return filterable output
    return apply_filters( 'ipress_post_attachments_shortcode', $output, $atts );
}

// Attachments by post ID shortcode
add_shortcode( 'ipress_post_attachments', 'ipress_post_attachments_shortcode' );

/**
 * Convert color form hex to rgb
 *
 * @param   array|string $atts 
 * @return  string
 */
function ipress_image_hex_to_rgb_shortcode( $atts ) {

    $defaults = [
        'after'     => '',
        'before'    => '',
        'hex'       => ''
    ];

    // get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_image_hex_to_rgb' );

    // hex code required
    if ( empty( $atts['hex'] ) ) { return false; }

    // convert hex code...
    $hex = str_replace( '#', '', $atts['hex'] );

    // ...to rgb data
    $r = hexdec( substr( $hex, 0, 2 ) );
    $g = hexdec( substr( $hex, 2, 2 ) );
    $b = hexdec( substr( $hex, 4, 2 ) );
    $rgb = $r . ',' . $g . ',' . $b; 

    // generate output
    $output = sprintf( '<span %s>', ipress_attr( 'image-hex-to-rgb' ) ) . $atts['before'] . esc_html( $rgb ) . $atts['after'] . '</span>';

    // return filterable output
    return apply_filters( 'ipress_image_hex_to_rgb_shortcode', $output, $atts );
}

// Hex Colour Code shortcode
add_shortcode( 'ipress_image_hex_to_rgb', 'ipress_image_hex_to_rgb_shortcode' );

//end
