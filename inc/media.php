<?php

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Images & Media support and functionality
 * 
 * @package     iPress\Media
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
//  Image & Media Functionality
//----------------------------------------------

/**
 * Pull an attachment ID from a post, if one exists.
 *
 * @param  integer $index 
 * @param  integer $post_id 
 * @return integer|boolean 
 */
function ipress_post_image_id( $index = 0, $post_id = null ) {

    // get image_ids for current or passed post
    $image_ids = array_keys(
        get_children(
            [
                'post_parent'    => $post_id ? $post_id : get_the_ID(),
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            ]
        )
    );

    // set or not?
    return ( isset( $image_ids[ $index ] ) ) ? $image_ids[ $index ] : false;
}

/**
 * Return an image pulled from the media gallery.
 *
 * Supported $args keys are:
 *
 *  - format   - string, default is 'html'
 *  - size     - string, default is 'full'
 *  - num      - integer, default is 0
 *  - attr     - string, default is ''
 *  - fallback - mixed, default is 'first-attached'
 *
 * Applies ipress_post_image_args, ipress_pre_post_image and ipress_get_image filters.
 *
 * @uses    ipress_post_image_id() 
 * @param   array|string $args 
 * @return  string|boolean 
 */
function ipress_post_image( $args = [] ) {

    $defaults = [
        'post_id'  => null,
        'format'   => 'html',
        'size'     => 'full',
        'num'      => 0,
        'attr'     => '',
        'fallback' => 'first-attached',
        'context'  => '',
        'echo'     => false
    ];

    // filter default parameters used by ipress_post_image().
    $defaults = apply_filters( 'ipress_post_image_args', $defaults, $args );
    $args = wp_parse_args( $args, $defaults );

    // allow child theme to short-circuit this function
    $pre = apply_filters( 'ipress_pre_post_image', false, $args, get_post() );
    if ( false !== $pre ) { return $pre; }

    // if post thumbnail exists, use its id
    if ( has_post_thumbnail( $args['post_id'] ) && ( $args['num'] === 0 ) ) {
        $id = get_post_thumbnail_id( $args['post_id'] );
    }

    // else if the first (default) image attachment is the fallback, use its id
    elseif ( 'first-attached' === $args['fallback'] ) {
        $id = ipress_post_image_id( $args['num'], $args['post_id'] );
    }

    // else if fallback id is supplied, use it
    elseif ( is_int( $args['fallback'] ) ) {
        $id = $args['fallback'];
    }

    // if we have an id, get the html and url
    if ( isset( $id ) && is_int( $id ) ) {
        $html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
        list( $url ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
    }

    // else if fallback html and url exist, use them
    elseif ( is_array( $args['fallback'] ) ) {
        $id   = 0;
        $html = $args['fallback']['html'];
        $url  = $args['fallback']['url'];
    }

    // else, return false (no image)
    else { return false; }

    // source path, relative to the root
    $src = str_replace( home_url(), '', $url );

    // determine output
    if ( 'html' === mb_strtolower( $args['format'] ) ) {
        $output = $html;
    } elseif ( 'url' === mb_strtolower( $args['format'] ) ) {
        $output = $url;
    } else {
        $output = $src;
    }

    // return false if $url is blank
    if ( empty( $url ) ) { $output = false; }

    // return data, filtered
    $output = apply_filters( 'ipress_post_image', $output, $args, $id, $html, $url, $src );

    // output or return
    if ( $echo ) {
        if ( $image ) {
            echo $image;
        } else {
            return false;
        }
    } else {  
        return $output;
    }  
}

/**
 * Returns additionally registered image sizes via add_image_size: width, height and crop sub-keys.
 *
 * @global array $_wp_additional_image_sizes 
 * @return array 
 */
function ipress_additional_image_sizes() {

    global $_wp_additional_image_sizes;
    return ( $_wp_additional_image_sizes ) ? $_wp_additional_image_sizes : [];
}

/**
 * Return all registered image sizes arrays, including the standard sizes.
 * - two-dimensional array of standard and additionally registered image sizes, with width, height and crop sub-keys.
 *
 * @uses    ipress_additional_image_sizes()
 * @param   boolean $additional
 * @return  array 
 */
function ipress_image_sizes( $additional=true ) {

    $builtin_sizes = [
        'large'     => [
            'width'  => get_option( 'large_size_w' ),
            'height' => get_option( 'large_size_h' ),
        ],
        'medium'    => [
            'width'  => get_option( 'medium_size_w' ),
            'height' => get_option( 'medium_size_h' ),
        ],
        'thumbnail' => [
            'width'  => get_option( 'thumbnail_size_w' ),
            'height' => get_option( 'thumbnail_size_h' ),
            'crop'   => get_option( 'thumbnail_crop' ),
        ],
    ];

    $additional_sizes = ( $additional ) ? ipress_additional_image_sizes() : [];
    return array_merge( $builtin_sizes, $additional_sizes );
}

/**
 * get the image meta data
 *
 * @param   integer     $attachment_id
 * @param   string      $size
 * @return  array
 */
function ipress_get_attachment_meta( $attachment_id, $size ){

    //set up data
    $data = [
        'alt'           => '',
        'caption'       => '',
        'description'   => '',
        'href'          => '',
        'src'           => '',
        'title'         => ''
    ];

    // get attachment data
    $attachment = get_post( $attachment_id );

    // not valid
    if ( empty( $attachment ) ) { return $data; }
    
    // get image data
    $att_data_thumb = wp_get_attachment_image_src( $attachment_id, $size );

    // construct data
    $data['alt']            = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
    $data['caption']        = $attachment->post_excerpt;
    $data['description']    = $attachment->post_content;
    $data['href']           = $attachment->guid;
    $data['src']            = $att_data_thumb[0];
    $data['title']          = $attachment->post_title;

    // return image data    
    return $data;
}

/**
 * get post attachements by attachement mime type 
 *
 * @param   integer     $post_id
 * @param   string      $att_type
 * @return  array
 */
function ipress_get_post_attachement( $post_id, $att_type ){

    // get attachment data
    $attachments = get_posts( [
        'post_type'         => 'attachment',
        'post_mime_type'    => $att_type,
        'numberposts'       => -1,
        'post_parent'       => $post_id
    ] );

    // return attachments    
    return $attachments;
}

/**
 * convert color form hex to rgb 
 *
 * @param   string
 * @raturn  string
 */
function ipress_hex2rgb( $hex ) {

    // convert hex...        
    $hex = str_replace( '#', '', $hex );

    // ...to rgb
    $r = hexdec( substr( $hex, 0, 2 ) );
    $g = hexdec( substr( $hex, 2, 2 ) );
    $b = hexdec( substr( $hex, 4, 2 ) );

    // return rgb value
    return $r . ', ' . $g . ', ' . $b; 
}

//----------------------------------------------
//  Image & Media Action & Filter Hooks
//----------------------------------------------

/**
 * Image size media editor support
 * - should match custom images from any custom add_images_size
 *
 * @see     https://codex.wordpress.org/Plugin_API/Filter_Reference/image_size_names_choose 
 * @param   array
 * @return  array
 */
function ipress_media_images( $sizes ) {

    // filterable custom images 
    $custom_sizes = (array)apply_filters( 'ipress_media_images', [
        'image-in-post' => __( 'Image in Post' ),
        'full'          => __( 'Original size' )
    ] );

    // test & return
    return ( empty($sizes) ) ? $sizes : array_merge( $sizes, $custom_sizes );
}

// Image size media editor support
add_filter( 'image_size_names_choose', 'ipress_media_images' );

/**
 * Remove default image sizes
 * unset( $sizes['thumbnail'] );
 * unset( $sizes['medium'] );
 * unset( $sizes['large'] );
 *
 * @param   array
 * @return  array
 */
function ipress_remove_default_image_sizes( $sizes ) {
    return (array)apply_filters( 'ipress_media_images_sizes_advanced', $sizes );
}

// Remove default image sizes
add_filter( 'intermediate_image_sizes_advanced', 'ipress_remove_default_image_sizes' );

/**
 * Allow svg mime type
 *
 * @param  array
 * @return array
 */
function ipress_custom_upload_mimes ( $existing_mimes = [] ) {

    // add the file extension to the array
    $new_mimes = apply_filters( 'ipress_upload_mimes', [ 'svg' => 'mime/type' ] ); 

    // add the file extension to the current mimes
    foreach ( $new_mimes as $k=>$v ) {
        if ( array_key_exists( $k, $existing_mimes ) ) { continue; }
        $existing_mimes[$k] = $v;
    }

    // call the modified list of extensions
    return $existing_mimes;
}

// Enable SVG mime type plus filterable other types
add_filter( 'upload_mimes', 'ipress_custom_upload_mimes' );          

/**
 * Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
 * - defaults to true
 *
 * @param  string
 * @return string
 */
function ipress_remove_thumbnail_dimensions( $html ) {

    // fiterable thumbnail dimensions
    $thumb_dimensions = (bool)apply_filters( 'ipress_remove_thumbnail_dimensions', true ); 

    // return formatted markup
    return ( $thumb_dimensions ) ? preg_replace( '/(width|height)=\"\d*\"\s/', '', $html ) : $html;
}

// Remove width and height dynamic attributes to thumbnail
add_filter( 'post_thumbnail_html', 'ipress_remove_thumbnail_dimensions', 10 ); 
add_filter( 'image_send_to_editor', 'ipress_remove_thumbnail_dimensions', 10 );   

/**
 * Custom Gravatar in Settings > Discussion
 * - add as array ( 'name' => '', 'path' => '' )'
 *
 * @param   array
 * @rerurn  array
 */
function ipress_gravatar ( $avatar_defaults ) {

    // filterable markup
    $custom_avatar = apply_filters( 'ipress_gravatar', '' );

    // set avatar
    if ( is_array( $custom_avatar ) && !empty( $custom_avatar ) ) { 
        $avatar_path = esc_url( $custom_avatar['path'], false);
        $avatar_defaults[ $avatar_path ] = $custom_avatar['name'];
    }

    // return avatar defaults
    return $avatar_defaults;
}

// Custom Avatar in Settings > Discussion
add_filter( 'avatar_defaults', 'ipress_gravatar' ); 

//end
