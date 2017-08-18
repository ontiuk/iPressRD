<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Category and Taxonomy shortcodes
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
//  Category, Tag And Taxonomy Shortcodes
//---------------------------------------------

/**
 * Produces the tag links list
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_tags_shortcode( $atts ) {

    $defaults = [
        'after'     => '',
        'before'    => __( 'Tagged With: ', 'ipress' ),
        'sep'       => ', ',
        'post_id'   => 0
    ];
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_tags' );
    
    // Tags if present in post
    $tags = ( $atts['post_id'] === 0 ) ? get_the_tag_list( $atts['before'], trim( $atts['sep'] ) . ' ', $atts['after'] ) :
                                         get_the_tag_list( $atts['before'], trim( $atts['sep'] ) . ' ', $atts['after'], (int)$atts['post_id'] );
    // None found?
    if ( ! $tags ) { return; }

    // Generate output
    $output = sprintf( '<div class="post-tags">%s</div>', $tags );

    // Return filterable output
    return apply_filters( 'ipress_post_tags_shortcode', $output, $atts );
}

// Post Tags Shortcode
add_shortcode( 'ipress_post_tags', 'ipress_post_tags_shortcode' );

/**
 * Generate the category links list
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_categories_shortcode( $atts ) {

    $defaults = [
        'sep'       => ', ',
        'before'    => __( 'Filed Under: ', 'ipress' ),
        'after'     => '',
        'parents'   => '',
        'post_id'   => 0
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_categories' );

    // Get category list
    $cats = ( $atts['post_id'] === 0 ) ? get_the_category_list( trim( $atts['sep'] ) . ' ', $atts['parents'] ) :
                                         get_the_category_list( trim( $atts['sep'] ) . ' ', $atts['parents'], (int)$atts['post_id'] );
    // None found?
    if ( ! $cats ) { return ''; }

    // Generate output
    $output = sprintf( '<span class="post-categories">%s</span>', $atts['before'] . $cats . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_post_categories_shortcode', $output, $atts );
}

// Post Categories Shortcode
add_shortcode( 'ipress_post_categories', 'ipress_post_categories_shortcode' );

/**
 * Generate the linked post taxonomy terms list
 *
 * @param   array|string $atts 
 * @return  string|boolean 
 */
function ipress_terms_shortcode( $atts ) {

    $defaults = [
        'after'     => '',
        'before'    => __( 'Filed Under: ', 'ipress' ),
        'sep'       => ', ',
        'taxonomy'  => 'category',
        'post_id'   => 0
    ];

    // Post terms shortcode defaults
    $defaults = apply_filters( 'ipress_post_terms_shortcode_defaults', $defaults );

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_terms' );

    // Category terms
    $terms = ( $atts['post_id'] === 0 ) ? get_the_term_list( get_the_ID(), $atts['taxonomy'], $atts['before'], trim( $atts['sep'] ) . ' ', $atts['after'] ) :
                                          get_the_term_list( (int)$atts['post_id'] , $atts['taxonomy'], $atts['before'], trim( $atts['sep'] ) . ' ', $atts['after'] );

    // Bad terms, none there?
    if ( is_wp_error( $terms ) || empty( $terms ) ) { return; }

    // Generate output
    $output = sprintf( '<span class="post-terms">%s</span>', $terms );

    // Return filterable output
    return apply_filters( 'ipress_post_terms_shortcode', $output, $terms, $atts );
}

// Post Terms Shortcode
add_shortcode( 'ipress_post_terms', 'ipress_terms_shortcode' );

/**
 * Generate the category ID
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_category_id_shortcode( $atts ) {

    $defaults = [
        'before'    => '',
        'after'     => '',
        'post_id'   => 0,
        'link'      => false
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_category_id' );

    // Retrieve categories
    $cats = ( $atts['post_id'] === 0 ) ? get_the_category() : get_the_category( (int)$atts['post_id'] );

    // None there?
    if ( ! $cats ) { return ''; }

    // Generate output
    $output = ( $atts['link'] === true ) ? sprintf( '<span class="post-category-id">%s</span>', $atts['before'] . '<a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '" title="Category Link">' . (int)$cats[0]->cat_ID . '</a>' . $atts['after'] ) :
                                           sprintf( '<span class="post-category-id">%s</span>', $atts['before'] . $cats[0]->cat_ID . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_post_category_id_shortcode', $output, $atts );
}

// Post Category ID Shortcode
add_shortcode( 'ipress_post_category_id', 'ipress_post_category_id_shortcode' );

/**
 * Generate the category ID
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_category_parent_id_shortcode( $atts ) {

    $defaults = [
        'before'    => '',
        'after'     => '',
        'post_id'   => 0,
        'link'      => false
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_category_parent_id' );

    // Retrieve categories if available
    $cats = ( $atts['post_id'] === 0 ) ? get_the_category() : get_the_category( (int)$atts['post_id'] );

    // None there?
    if ( ! $cats ) { return ''; }

    // Generate output
    $output = ( $atts['link'] === true ) ? sprintf( '<div class="post-category-parent-id">%s</div>', $atts['before'] . '<a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '" title="Category Link">' . (int)$cats[0]->category_parent . '</a>' . $atts['after'] ) :
                                           sprintf( '<div class="post-category-parent-id">%s</div>', $atts['before'] . $cat[0]->category_parent . $atts['after'] );

    // Return filterable output 
    return apply_filters( 'ipress_post_category_parent_id_shortcode', $output, $atts );
}

// Post Category Parent ID Shortcode
add_shortcode( 'ipress_post_category_parent_id', 'ipress_post_category_parent_id_shortcode' );

/**
 * Generate the category Slug
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_category_slug_shortcode( $atts ) {

    $defaults = [
        'before'    => '',
        'after'     => '',
        'post_id'   => 0,
        'link'      => false
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_category_slug' );

    // Retrieve categories if available
    $cats = ( $atts['post_id'] === 0 ) ? get_the_category() : get_the_category( (int)$atts['post_id'] );
    if ( ! $cats ) { return ''; }

    // Generate output
    $output = ( $atts['link'] === true ) ? sprintf( '<div class="post-category-slug">%s</div>', $atts['before'] . '<a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '" title="Category Link">' . $cats[0]->category_nicename . '</a>' . $atts['after'] ) :
                                           sprintf( '<div class="post-category-slug"%s></div>', $atts['before'] . $cats[0]->category_nicename . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_post_category_slug_shortcode', $output, $atts );
}

// Post Category Slug Shortcode
add_shortcode( 'ipress_post_category_slug', 'ipress_post_category_slug_shortcode' );

/**
 * Generate the category name
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_category_name_shortcode( $atts ) {

    $defaults = [
        'before'    => '',
        'after'     => '',
        'post_id'   => 0,
        'link'      => false
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_category_name' );

    // Retrieve categories if available
    $cats = ( $atts['post_id'] === 0 ) ? get_the_category() : get_the_category( (int)$atts['post_id'] );
    if ( ! $cats ) { return ''; }

    // Generate output
    $output = ( $atts['link'] === true ) ? sprintf( '<div class="post-category-name">%s</div>', $atts['before'] . '<a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '" title="Category Link">' . $cats[0]->cat_name . '</a>' . $atts['after'] ) :
                                           sprintf( '<div class="post-category-name">%s</div>', $atts['before'] . $cats[0]->cat_name . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_post_category_name_shortcode', $output, $atts );
}

// Post Category Name
add_shortcode( 'ipress_post_category_name', 'ipress_post_category_name' );

/**
 * Generate the category count
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_post_category_count_shortcode( $atts ) {

    $defaults = [
        'before'    => '',
        'after'     => '',
        'post_id'   => 0,
        'link'      => false
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_post_category_count' );

    // Retrieve categories if available
    $cats = ( $atts['post_id'] === 0 ) ? get_the_category() : get_the_category( (int)$atts['post_id'] );
    if ( ! $cats ) { return ''; }

    // Generate output
    $output = ( $atts['link'] === true ) ? sprintf( '<div class="post-category-count">%s</div>', $atts['before'] . '<a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '" title="Category Link">' . $cats[0]->category_count . '</a>' . $atts['after'] ) :
                                           sprintf( '<div class="post-category-count">%s</div>', $atts['before'] . $cats[0]->category_count . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_post_category_count_shortcode', $output, $atts );
}

// Post Category Count
add_shortcode( 'ipress_post_category_count', 'ipress_post_category_count' );

//---------------------------------------------
//  Taxonomy Functions
//---------------------------------------------

/**
 * Generate the taxonomy term count
 *
 * @param   array|string $atts 
 * @return  string 
 */
function ipress_taxonomy_term_count_shortcode( $atts ) {

    global $wpdb;

    $defaults = [
        'taxonomy'  => '',
        'term'      => '',
        'before'    => '',
        'after'     => '',
        'link'      => false
    ];

    // Get shortcode attributes
    $atts = shortcode_atts( $defaults, $atts, 'ipress_taxonomy_term_count' );

    // Taxonomy term required
    if ( empty( $atts['taxonomy'] ) || empty( $atts['term'] ) ) { return 0; }

    // Category by ID
    if ( is_numeric( $term ) ) {
        $q = 'SELECT ' . $wpdb->term_taxonomy . '.count FROM ' . $wpdb->terms . ', ' . $wpdb->term_taxonomy . ' 
              WHERE ' . $wpdb->terms . '.term_id = ' . $wpdb->term_taxonomy . '.term_id 
              AND ' . $wpdb->term_taxonomy . '.taxonomy=%s
              AND ' . $wpdb->term_taxonomy . '.term_id=%d';
        $qs = $wpdb->prepare( $q, $atts['taxonomy'], absint( $atts['term'] ) );
        $cat_count = (int)$wpdb->get_var( $qs );
    } else {
        // Category by slug
        $q = 'SELECT ' . $wpdb->term_taxonomy . '.count FROM ' . $wpdb->terms . ', ' . $wpdb->term_taxonomy . ' 
              WHERE ' . $wpdb->terms . '.term_id = ' . $wpdb->term_taxonomy . '.term_id 
              AND ' . $wpdb->term_taxonomy . '.taxonomy=%s
              AND ' . $wpdb->terms . '.slug=%s';
        $qs = $wpdb->prepare( $q, $atts['taxonomy'], strtolower( $atts['term'] ) );
        $cat_count = (int)$wpdb->get_var( $qs );
    }

    // Generate output
    $output = ( $atts['link'] === true ) ? sprintf( '<div class="taxonomy-term-count">%s</div>', $atts['before'] . '<a href="' . esc_url( get_term_link ( $atts['term'], $atts['taxonomy'] ) ) . '" title="Taxonomy Term Link">' . $cats[0]->category_count . '</a>' . $atts['after'] ) :
                                           sprintf( '<div class="taxonomy-term-count">%s</div>', $atts['before'] . $cats[0]->category_count . $atts['after'] );

    // Return filterable output
    return apply_filters( 'ipress_taxonomy_term_count_shortcode', $output, $atts );
}

// Taxonomy Count
add_shortcode( 'ipress_taxonomy_term_count', 'ipress_taxonomy_term_count_shortcode' );

//end
