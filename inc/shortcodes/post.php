<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Post related shortcodes
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
//  Post, Comment & Content Shortcodes                      
//---------------------------------------------

/**
 * Displays the edit post link for logged in users.
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_edit_shortcode( $atts ) {

    $defaults = [
        'after'     => '',
        'before'    => '',
        'link'      => __( '(Edit)', 'ipress' ),
        'post_id'   => 0
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_edit' );

    // Not in loop? must have post_id
    if ( !in_the_loop() && $atts['post_id'] === 0 ) { return; }

    // Capture output... no function return
    ob_start();
    if ( $atts['post_id'] === 0 ) {
        edit_post_link( $atts['link'], $atts['before'], $atts['after'] );
    } else {
        edit_post_link( $atts['link'], $atts['before'], $atts['after'], $atts['post_id'] );
    }
    $output = ob_get_clean();

    // Return filterable output
    return apply_filters( 'ipress_post_edit_shortcode', $output, $atts );
}

// Post Edit Link Shortcode
add_shortcode( 'ipress_post_edit', 'ipress_post_edit_shortcode' );

/**
 * Displays the date of post publication.
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_date_shortcode( $atts ) {

    $defaults = [
        'after'     => '',
        'before'    => '',
        'format'    => sprintf( __( '%s', 'ipress' ), get_option( 'date_format' ) ),
        'label'     => ''
    ];

    // Must be in loop
    if ( !in_the_loop() ) { return; }

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_date' );

    // Set & generate output
    $display = ( 'relative' === $atts['format'] ) ? ipress_time_diff( get_the_time( 'U', get_the_ID() ), current_time( 'timestamp' ) ) . ' ' . __( 'ago', 'ipress' ) : get_the_time( $atts['format'], get_the_ID() );
    $output = sprintf( '<time class="post-time">%s</time>', $atts['before'] . $atts['label'] . $display . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_post_date_shortcode', $output, $atts );
}

// Post Date Shortcode
add_shortcode( 'ipress_post_date', 'ipress_post_date_shortcode' );

/**
 * Display the time of post publication.
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_time_shortcode( $atts ) {

    $defaults = [
        'after'     => '',
        'before'    => '',
        'format'    => sprintf( __( '%s', 'ipress' ), get_option( 'ipress_time_format' ) ),
        'label'     => ''
    ];

    // Must be in loop
    if ( !in_the_loop() ) { return; }

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_time' );

    // Generate outpur
    $output = sprintf( '<time class="post-time">%s</time>', $atts['before'] . $atts['label'] . get_the_time( $atts['format'], get_the_ID() ) . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_post_time_shortcode', $output, $atts );
}

// Post Time Shortcode
add_shortcode( 'ipress_post_time', 'ipress_post_time_shortcode' );

/**
 * Produce the post last modified date.
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_modified_date_shortcode( $atts ) {

    $defaults = [
        'after'     => '',
        'before'    => '',
        'format'    => sprintf( __( '%s', 'ipress' ), get_option( 'date_format' ) ),
        'label'     => ''
    ];

    // Must be in loop
    if ( !in_the_loop() ) { return; }

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_modified_date' );

    // Set & generate output
    $display = get_the_modified_time( $atts['format'] );
    $output = sprintf( '<time class="post-modified-time">%s</time>', $atts['before'] . $atts['label'] . $display . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_post_modified_date_shortcode', $output, $atts );
}

// Post Modified Date Shortcode
add_shortcode( 'ipress_post_modified_date', 'ipress_post_modified_date_shortcode' );

/**
 * Display the post last modified time.
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_modified_time_shortcode( $atts ) {

    $defaults = [
        'after'     => '',
        'before'    => '',
        'format'    => sprintf( __( '%s', 'ipress' ), get_option( 'time_format' ) ),
        'label'     => '',
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_modified_time' );

    // Must be in loop
    if ( !in_the_loop() ) { return; }

    // Generate output
    $output = sprintf( '<time class="post-modified-time">%s</time>', $atts['before'] . $atts['label'] . get_the_modified_time( $atts['format'] ) . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_post_modified_time_shortcode', $output, $atts );
}

// Post Modified Time Shortcode
add_shortcode( 'ipress_post_modified_time', 'ipress_post_modified_time_shortcode' );

/**
 * Generates the author of the post (unlinked display name).
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_author_shortcode( $atts ) {

    $defaults = [
        'after'  => '',
        'before' => '',
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_author' );

    // Must be in loop
    if ( !in_the_loop() ) { return; }

    // Get the current author
    $author = get_the_author();

    // Generate output
    $output  = '<span class="post-author">';
    $output .= $atts['before'] . sprintf( '<span class="post-author-name">%s</span>', esc_html( $author ) ) . $atts['after'];
    $output .= '</span>';

    // Return filterable output
    return apply_filters( 'ipress_post_author_shortcode', $output, $atts );
}

// Post Author Shortcode
add_shortcode( 'ipress_post_author', 'ipress_post_author_shortcode' );

/**
 * Generate the author of the post (link to author URL).
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_author_link_shortcode( $atts ) {

    $defaults = [
        'after'    => '',
        'before'   => '',
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_author_link' );

    // Must be in loop
    if ( !in_the_loop() ) { return; }

    // Get author url
    $url = get_the_author_meta( 'url' );

    // If no url, use post author shortcode function.
    if ( ! $url ) { return ipress_post_author_shortcode( $atts ); }

    // Get the current author
    $author = get_the_author();

    // Generate output
    $output  = '<span class="post-author">';
    $output .= $atts['before'];
    $output .= sprintf( '<a href="%s" class="post-author">', $url );
    $output .= sprintf( '<span class="post-author-name">%s</span>', esc_html( $author ) );
    $output .= '</a>' . $atts['after'] . '</span>';

    // Return filterable output
    return apply_filters( 'ipress_post_author_link_shortcode', $output, $atts );
}

// Post Author Link
add_shortcode( 'ipress_post_author_link', 'ipress_post_author_link_shortcode' );

/**
 * Generates the author of the post (link to author archive).
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_author_posts_link_shortcode( $atts ) {

    $defaults = [
        'after'  => '',
        'before' => '',
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_author_posts_link' );

    // Must be in loop
    if ( !in_the_loop() ) { return; }

    // Get the author & author url    
    $author = get_the_author();
    $url    = get_author_posts_url( get_the_author_meta( 'ID' ) );

    // Generate output
    $output  = '<span class="post-author">';
    $output .= $atts['before'];
    $output .= sprintf( '<a href="%s" class="post-author-link">', $url );
    $output .= sprintf( '<span class="post-author-name">%s</span>', esc_html( $author ) );
    $output .= '</a>' . $atts['after'] . '</span>';

    // Return fiterable output
    return apply_filters( 'ipress_post_author_posts_link_shortcode', $output, $atts );
}

// Post Author Posts Link Shortcode
add_shortcode( 'ipress_post_author_posts_link', 'ipress_post_author_posts_link_shortcode' );

/**
 * Generate the link to the current post comments.
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_comments_shortcode( $atts ) {

    $defaults = [
        'after'       => '',
        'before'      => '',
        'hide_if_off' => 'enabled',
        'more'        => __( '% Comments', 'ipress' ),
        'one'         => __( '1 Comment', 'ipress' ),
        'zero'        => __( 'Leave a Comment', 'ipress' ),
    ];
    
    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_comments' );

    // Must be in loop
    if ( !in_the_loop() ) { return; }

    // No comments if turned off for post
    if ( !comments_open() && 'enabled' === $atts['hide_if_off'] ) { return; }

    // Capture result... no function return
    ob_start();
    comments_number( $atts['zero'], $atts['one'], $atts['more'] );
    $comments = sprintf( '<a href="%s">%s</a>', get_comments_link(), ob_get_clean() );

    // Generate output 
    $output = ipress_html( [
        'html'   => '<span class="post-comments-link">' . $atts['before'] . $comments . $atts['after'] . '</span>',
        'echo'  => false,
    ] );

    // Return filterable output
    return apply_filters( 'ipress_post_comments_shortcode', $output, $atts );
}

// Post Comments Shortcode
add_shortcode( 'ipress_post_comments', 'ipress_post_comments_shortcode' );

/**
 * Output post/page id by name
 * 
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_id_by_name_shortcode( $atts ) {

    $defaults = [
        'after'  => '',
        'before' => '',
        'name'   => ''
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_id_by_name_posts_link' );

    // Get page data if available
    $page = get_page_by_title( $atts['name'], OBJECT, 'post' );
    if ( !$page ) { return; }

    // Generate output
    $output = sprintf( '<span class="post-id-by-name">%s</span>', $atts['before'] . $page->ID . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_post_id_by_name_shortcode', $output, $atts );
}

// Post ID From Title Shortcode
add_shortcode( 'ipress_post_id_by_name', 'ipress_post_id_by_name_shortcode' );

/**
 * Output post/page id by name
 * 
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_id_by_type_name_shortcode( $atts ) {

    $defaults = [
        'after'  => '',
        'before' => '',
        'name'   => '',
        'type'   => '',
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_id_by_type_name_posts_link' );

    // Get page data if available
    $page = get_page_by_path( $atts['name'], OBJECT, $atts['type'] );
    if ( !$page ) { return; }

    // Generate output
    $output = sprintf( '<span class="post-id-by-type-name">%s</span>', $atts['before'] . $page->ID . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_post_id_by_type_name_shortcode', $output, $atts );
}

// Post ID From Custom Post Type Shortcode
add_shortcode( 'ipress_post_id_by_type_name', 'ipress_post_id_by_type_name_shortcode' );

/**
 * Output text wrapped in code tags
 * 
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_code_shortcode( $atts ) {

    $defaults = [
        'before'    => '',
        'after'     => '',
        'text'      => ''
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_code' );

    // Text field required
    if ( empty( $atts['text'] ) ) { return; }

    // Generate output
    $output = sprintf( '<code class="post-code">%s</code>', $atts['before'] . esc_html( $atts['text'] ) . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_post_code_shortcode', $output, $atts );
}

// Code Tag Shortcode
add_shortcode( 'ipress_post_code', 'ipress_post_code_shortcode' );

/**
 * Generate custom post type post count
 * 
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_type_count_shortcode( $atts ) {

    $defaults = [
        'before'    => '',
        'after'     => '',
        'type'      => ''
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_type_count' );

    // Valid post type required
    if ( empty( $atts['type'] ) || !post_type_exists( $atts['type'] ) ) { return; }

    // Get post count
    $num_posts = wp_count_posts( $atts['type'] );

    // Generate output
    $output = sprintf( '<span class="post-type-count">%s</span>', $atts['before'] . intval( $num_posts->publish ) . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_post_type_count_shortcode', $output, $atts );
}

// Custom Post Type Post Count Shortcode
add_shortcode( 'ipress_post_type_count', 'ipress_post_type_count_shortcode' );

/**
 * Generate custom post type post list
 * 
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_type_list_shortcode( $atts ) {

    global $post;

    $defaults = [
        'before'            => '',
        'after'             => '',
        'type'              => '',
        'post_status'       => 'publish',
        'posts_per_page'    => -1 
    ];

    // Start post list
    $posts = [];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_type_list' );

    // Valid post type required
    if ( empty( $atts['type'] ) || !post_type_exists( $atts['type'] ) ) { return; }

    // Set up query
    $args = [ 
        'post_type'      => $atts['type'],
        'post_status'    => $atts['publish'],
        'posts_per_page' => $atts['posts_per_page'] 
    ];
    $the_query = new WP_Query( $args );

    // The loop
    if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            $posts[$post->ID] = $post->post_title;
        }
    }
    wp_reset_postdata();

    // Generate outpur
    $output = sprintf( '<span class="post-type-list">%s</span>', $atts['before'] . print_r( $posts, true ) . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_post_type_list_shortcode', $output, $atts );
}

// Custom Post Type Post List Shortcode - Should be used with do_shortcode
add_shortcode( 'ipress_post_type_list', 'ipress_post_type_list_shortcode' );

//end
