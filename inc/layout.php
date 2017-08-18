<?php

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Theme layout functionality
 * 
 * @package     iPress\Layout
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
//  Layout Functionality
//---------------------------------------------

/**
 * Construct html markup
 * 
 * @uses    ipress_attr()  contextual attributes
 * @param   array $args 
 * @return  string 
 */
function ipress_html( $args = [] ) {

    // defaults
    $defaults = [
        'html'      => '',
        'context'   => '',
        'echo'      => true
    ];

    // merge args
    $args = wp_parse_args( $args, $defaults );

    // pre-processing
    $pre = apply_filters( 'ipress_html_' . $args['context'], false, $args );
    if ( $pre !== false ) { return $pre; }

    // content
    if ( ! $args['html'] ) { return ''; }

    // process attributes
    $html = ( empty( $args['context'] ) ) ? $args['html'] : sprintf( $args['html'], ipress_attr( $args['context'] ) );

    // contextual filter
    $html = ( empty( $args['context'] ) ) ? $html : apply_filters( 'ipress_html_' . $args['context'] . '_output', $html, $args );

    // echo or return
    if ( $args['echo'] ) { echo $tag; }
    else { return $html; }
}

/**
 * Generate list of attributes & apply contextual filter
 *
 * @uses    ipress_parse_attr()
 * @param   string  $context   
 * @param   array   $attributes 
 * @return  string 
 */
function ipress_attr( $context, $attr = [] ) {

    // construct attributes list by context
    $attr = ipress_parse_attr( $context, $attr );

    // start list
    $output = '';

    // iterate through attributes
    foreach ( $attr as $k=>$v ) {
        if ( !$v ) { continue; }
        $output .= ( true === $v ) ? esc_html( $k ) . ' ' : sprintf( '%s="%s" ', esc_html( $k ), esc_attr( $v ) );
    }

    // contextual finter
    $output = apply_filters( 'ipress_attr_' . $context . '_output', $output, $attr, $context );

    // return formatted data
    return trim( $output );
}

/**
 * Construct attributes from defaults & apply contextual filter 
 *
 * @param   string  $context    
 * @param   array   $attributes 
 * @return  array 
 */
function ipress_parse_attr( $context, $attr = [] ) {

    // set defaults
    $defaults = [
        'class' => sanitize_html_class( $context ),
    ];

    // constuct attributes
    $attr = wp_parse_args( $attr, $defaults );

    // apply contextual filter
    return apply_filters( 'ipress_attr_' . $context, $attr, $context );
}

/**
 * Helper function - reset class attribute
 *
 * @param   array $attr 
 * @return  array 
 */
function ipress_attributes_empty_class( $attr ) {

    // set class markup
    $attr['class'] = '';

    // return attributes
    return $attr;
}

//---------------------------------------------
// Context Based Actions & Filters
// 
// - fixed closure based hooks: ipress_attr_abc
// - use 'abc' as arg in ipress_attr() call  
//
// - ipress_attr_head
// - ipress_attr_body
// - ipress_attr_header
// - ipress_attr_title
// - ipress_attr_description
// - ipress_attr_widget
// - ipress_attr_widget-header
// - ipress_attr_breadcrumb
// - ipress_attr_breadcrumb-link
// - ipress_attr_search-form
// - ipress_attr_nav
// - ipress_attr_nav-links
// - ipress_attr_nav-link
// - ipress_attr_wrap
// - ipress_attr_main
// - ipress_attr_content
// - ipress_attr_taxonomy-archive
// - ipress_attr_author-archive
// - ipress_attr_cpt-archive
// - ipress_attr_date-archive
// - ipress_attr_blog-template
// - ipress_attr_posts
// - ipress_attr_article
// - ipress_attr_article-image
// - ipress_attr_article-image-link
// - ipress_attr_article-image-widget
// - ipress_attr_article-author
// - ipress_attr_article-author-link
// - ipress_attr_article-author-name
// - ipress_attr_article-time
// - ipress_attr_article-modified-time
// - ipress_attr_article-title'
// - ipress_attr_article-content
// - ipress_attr_aticle-meta
// - ipress_attr_pagination
// - ipress_attr_article-comments
// - ipress_attr_comment
// - ipress_attr_comment-author
// - ipress_attr_comment-author-link
// - ipress_attr_comment-time
// - ipress_attr_comment-time-link
// - ipress_attr_comment-content
// - ipress_attr_sidebar
// - ipress_attr_sidebar-primary
// - ipress_attr_sidebar-secondary
// - ipress_attr_sidebar-header
// - ipress_attr_footer
//---------------------------------------------

/**
 * Add attributes for head element
 * - Context Element: 'head'
 *
 * @param   array $attr
 * @return  array
 */
add_filter( 'ipress_attr_head', function ( $attr ) {

    // set class markup
    $attr['class'] = '';

    // front page only
    if ( !is_front_page() ) { return $attr; }

    // set schema markup
    $attr['itemscope'] = true;
    $attr['itemtype']  = 'http://schema.org/WebSite';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for body element
 * - Context Element: 'body'
 *
 * @param   array $attr
 * @return  array
 */
add_filter( 'ipress_attr_body', function ( $attr ) {

    // set class & schema markup
    $attr['class']     = join( ' ', get_body_class() );
    $attr['itemscope'] = true;
    $attr['itemtype']  = 'http://schema.org/WebPage';

    // search results page
    if ( is_search() ) {
        $attr['itemtype'] = 'http://schema.org/SearchResultsPage';
    }

    // return attributes
    return $attr;
} );

/**
 * Add attributes for site header element
 * - Context Element: 'header'
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_header', function ( $attr ) {

    // set schema markup
    $attr['itemscope'] = true;
    $attr['itemtype']  = 'http://schema.org/WPHeader';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for site title element
 * - Context Element: 'title'
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_title', function ( $attr ) {

    // set schema markup
    $attr['itemprop'] = 'headline';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for site description element
 * - Context Element: 'description'
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_description', function ( $attr ) {

    // set schema markup    
    $attr['itemprop'] = 'description';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for widget areas
 * - Context Element: 'widget'
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_widget', function ( $attr, $route='' ) {

    // set widget attribute
    $route = ( empty( $route ) ) ? 'widget' : $route . '-widget';
    $attr['class'] = 'widget-area ' . $route;

    // return attributes
    return $attr;
} );

/**
 * Add attributes for header widget area.
 * - Context Element: 'widget'
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_widget-header', function ( $attr ) {

    // set widget attributes
    $attr['class'] = 'widget-area header-widget';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for breadcrumb wrapper.
 * - Context Element: 'title'
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_breadcrumb', function ( $attr ) {

    // set schema markup
    $attr['itemprop']  = 'breadcrumb';
    $attr['itemscope'] = true;
    $attr['itemtype']  = 'http://schema.org/BreadcrumbList';

    // breadcrumb itemprop not valid on blog
    if ( is_singular( 'post' ) || is_archive() || is_home() || is_page_template( 'page_blog.php' ) ) {
        unset( $attr['itemprop'] );
    }

    // return attributes
    return $attr;
} );

/**
 * Add attributes for breadcrumb wrapper.
 *
 * @param array $attributes Existing attributes.
 * @return array Ammended attributes
 */
add_filter( 'ipress_attr_breadcrumb-link', function ( $attr ) {

    // set schema markup
    $attr['itemprop']  = 'itemListElement';
    $attr['itemscope'] = true;
    $attr['itemtype']  = 'http://schema.org/ListItem';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for search form.
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_search-form', function ( $attr ) {

    // set schema markup
    $attr['itemprop']  = 'potentialAction';
    $attr['itemscope'] = true;
    $attr['itemtype']  = 'http://schema.org/SearchAction';
    $attr['method']    = 'get';
    $attr['action']    = home_url( '/' );
    $attr['role']      = 'search';

    // return attributes
    return $attr;
} );

/**
 * Add standard attributes for navigation elements - primary navigation, secondary navigation, header navigation.
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_nav', function ( $attr ) {

    // set schema markup
    $attr['itemscope'] = true;
    $attr['itemtype']  = 'http://schema.org/SiteNavigationElement';
    $attr['role']      = 'navigation';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for the span wrap around navigation item links.
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_nav-links', function ( $attr ) {

    // set class & schema markup
    $attr['class'] = '';
    $attr['itemprop'] = 'name';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for the navigation item links.
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_nav-link', function ( $attr ) {

    // set nav-link markup
    $class = str_replace( 'nav-link', '', $attr['class'] );
    $attributes['class']    = $class;
    $attributes['itemprop'] = 'url';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for wrap elements
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_wrap', function ( $attr ) {

    // set class markup
    $attributes['class'] = 'wrap';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for main element.
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_main', function ( $attr, $route='main' ) {

    // set class & schema markup
    $attr['class'] = 'content content-' . $route;
    $attr['role']  = 'main';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for main content element.
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_content', function ( $attr ) {

    // set class markup
    $attributes['class'] = 'content';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for taxonomy description.
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_taxonomy-archive', function ( $attr ) {

    // set class markup
    $attributes['class'] = 'archive-description taxonomy-archive-description taxonomy-description';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for author description.
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_author-archive', function ( $attr ) {

    // set class markup
    $attributes['class'] = 'archive-description author-archive-description author-description';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for Post Type archive description.
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_cpt-archive', function ( $attr ) {

    // set class markup
    $attributes['class'] = 'archive-description cpt-archive-description';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for date archive description.
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_date-archive', function ( $attr ) {

    // set class markup
    $attributes['class'] = 'archive-description date-archive-description archive-date';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for blog template description.
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_blog-template', function ( $attr ) {

    // set class markup
    $attributes['class'] = 'archive-description blog-template-description';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for posts page description.
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_posts', function ( $attr ) {

    // set class markup
    $attr['class'] = 'archive-description posts-page-description';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for article element.
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_article', function ( $attr ) {

    // set class markup
    $attr['class'] = join( ' ', get_post_class() );

    // main query / blog
    if ( ! is_main_query() && ! ipress_is_blog_template() ) { return $attr; }

    // set schema markup
    $attr['itemscope'] = true;
    $attr['itemtype']  = 'http://schema.org/CreativeWork';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for article image element.
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_article-image', function ( $attr ) {

    // set class & schema markup
    $attr['class']    = 'post-image article-image';
    $attr['itemprop'] = 'image';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for article image link element
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_article-image-link', function ( $attr ) {

    // set class & schema markup
    $attr['href']        = get_permalink();
    $attr['aria-hidden'] = 'true';
    $attributes['class'] = 'article-image-link';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for in widget article image element
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_article-image-widget', function ( $attr ) {

    // set class & schema markup
    $attr['class']    = 'article-image attachment-' . get_post_type();
    $attr['itemprop'] = 'image';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for article author element 
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_article-author', function ( $attr ) {

    // set schema markup
    $attr['itemprop']  = 'author';
    $attr['itemscope'] = true;
    $attr['itemtype']  = 'http://schema.org/Person';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for article author link element.
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_article-author-link', function ( $attr ) {

    // set schema markup
    $attr['itemprop'] = 'url';
    $attr['rel']      = 'author';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for article author name element.
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_article-author-name', function ( $attr ) {

    // set schema markup
    $attr['itemprop'] = 'name';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for article time element
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_article-time', function ( $attr ) {

    // set date & schema markup
    $attr['itemprop'] = 'datePublished';
    $attr['datetime'] = get_the_time( 'c' );

    // return attributes
    return $attr;
} );

/**
 * Add attributes for modified time element for an article
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_article-modified-time', function ( $attr ) {

    // set date & schema markup
    $attr['itemprop'] = 'dateModified';
    $attr['datetime'] = get_the_modified_time( 'c' );

    // return attributes
    return $attr;
} );

/**
 * Add attributes for article title element
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_article-title', function ( $attr ) {

    // set schema markup
    $attr['itemprop'] = 'headline';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for article content element
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_article-content', function ( $attr ) {

    // set schema markup
    $attr['itemprop'] = 'text';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for article meta elements
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_aticle-meta', function ( $attr ) {

    // set class markup
    $attr['class'] = 'article-meta';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for pagination
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_pagination', function ( $attr ) {

    // set class markup
    $attr['class'] .= ' pagination';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for article comments element
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_article-comments', function ( $attr ) {

    // set id markup
    $attr['id'] = 'comments';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for single comment element
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_comment', function ( $attr ) {

    // set class & schema markup
    $attr['class']     = '';
    $attr['itemprop']  = 'comment';
    $attr['itemscope'] = true;
    $attr['itemtype']  = 'http://schema.org/Comment';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for comment author element
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_comment-author', function ( $attr ) {

    // set schema markup
    $attr['itemprop']  = 'author';
    $attr['itemscope'] = true;
    $attr['itemtype']  = 'http://schema.org/Person';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for comment author link element
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_comment-author-link', function ( $attr ) {

    // set link & schema markup
    $attr['rel']      = 'external nofollow';
    $attr['itemprop'] = 'url';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for comment time element
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_comment-time', function ( $attr ) {

    // set date & schema markup
    $attr['datetime'] = esc_attr( get_comment_time( 'c' ) );
    $attr['itemprop'] = 'datePublished';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for comment time link element
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_comment-time-link', function ( $attr ) {

    // set schema markup
    $attr['itemprop'] = 'url';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for comment content container
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_comment-content', function ( $attr ) {

    // set schema markup
    $attr['itemprop'] = 'text';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for sidebar element
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_sidebar', function ( $attr, $route ) {

    // set class & schema markup
    $attr['class']      = 'sidebar sidebar-' . $route . ' widget-area';
    $attr['role']       = 'complementary';
    $attr['aria-label'] = __( ucwords( $route ) . ' Sidebar', 'ipress' );
    $attr['itemscope']  = true;
    $attr['itemtype']   = 'http://schema.org/WPSideBar';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for primary sidebar element
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_sidebar-primary', function ( $attr ) {

    // set class & schema markup
    $attr['class']      = 'sidebar sidebar-primary widget-area';
    $attr['role']       = 'complementary';
    $attr['aria-label'] = __( 'Primary Sidebar', 'ipress' );
    $attr['itemscope']  = true;
    $attr['itemtype']   = 'http://schema.org/WPSideBar';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for secondary sidebar element
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_sidebar-secondary', function ( $attr ) {

    // set class & schema markup
    $attr['class']      = 'sidebar sidebar-secondary widget-area';
    $attr['role']       = 'complementary';
    $attr['aria-label'] = __( 'Secondary Sidebar', 'ipress' );
    $attr['itemscope']  = true;
    $attr['itemtype']  = 'http://schema.org/WPSideBar';

    // return attributes
    return $attr;
} );

/**
 * Add attributes for header sidebar element
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_sidebar-header', function ( $attr ) {

    // set class & schema markup
    $attr['class']      = 'sidebar sidebar-header widget-area';
    $attr['role']       = 'complementary';
    $attr['aria-label'] = __( 'Header Sidebar', 'ipress' );
    $attr['itemscope']  = true;
    $attr['itemtype']  = 'http://schema.org/WPSideBar';

    return $attr;
} );

/**
 * Add attributes for site footer element
 *
 * @param   array $attr
 * @return  array 
 */
add_filter( 'ipress_attr_footer', function ( $attr ) {

    // set schema markup
    $attributes['itemscope'] = true;
    $attributes['itemtype']  = 'http://schema.org/WPFooter';

    // return attributes
    return $attr;
} );

//---------------------------------------------
//  Layout Actions & Filters
//---------------------------------------------

/**
 * Control rtl - ltr theme layout at user level 
 *
 * @global $wp_locale
 * @global $wp_styles
 */
function ipress_theme_direction() {

    global $wp_locale, $wp_styles;

    // filterable direction: ltr, rtl, none
    $direction = apply_filters( 'ipress_theme_direction', '' );     
    if ( empty( $direction ) ) { return; }

    // get current user
    $uid = get_current_user_id();

    // set direction data
    if ( $direction ) {
        update_user_meta( $uid, 'rtladminbar', $direction );
    } else {
        $direction = get_user_meta( $_user_id, 'rtladminbar', true );
        if ( false === $direction ) {
            $direction = isset( $wp_locale->text_direction ) ? $wp_locale->text_direction : 'ltr' ;
        }
    }

    // set styles setting
    $wp_locale->text_direction = $direction;
    if ( ! is_a( $wp_styles, 'WP_Styles' ) ) { $wp_styles = new WP_Styles(); }
    $wp_styles->text_direction = $direction;
}

// Set the current theme direction
add_action( 'init', 'ipress_theme_direction' ); 

/**
 * Add page slug to body class - Credit: Starkers Wordpress Theme
 *
 * @param   array
 * @return  array
 */
function ipress_body_class( $classes ) {

    global $post;

    // set by page type restrictions
    if ( is_home() ) {
        $key = array_search( 'blog', $classes );
        if ( $key > -1 ) {
            unset($classes[$key]);
        }
    } elseif ( is_page() ) {
        $classes[] = sanitize_html_class( $post->post_name );
    } elseif ( is_singular() ) {
        $classes[] = sanitize_html_class( $post->post_name );
    }
    
    // return attributes
    return $classes;
}

// Add slug to body class
add_filter( 'body_class', 'ipress_body_class' ); 

/**
 * Remove invalid rel attribute values in the category list - default true
 *
 * @param  string
 * @return string
 */
function ipress_remove_category_rel_from_category_list( $list ) {
    return ( ! current_theme_supports( 'ipress-cat-rel-list' ) && !is_admin() ) ? str_replace( 'rel="category tag"', 'rel="tag"', $list ) : $list;
}

// Remove invalid rel attribute
add_filter( 'the_category', 'ipress_remove_category_rel_from_category_list' ); 

/**
 * Remove or amend the 'read more' link - defaults to remove
 *
 * @return string
 */
function ipress_read_more_link( $link ) { 
    $rml = apply_filters( 'ipress_read_more_link', '' ); 
    return ( $rml === false ) ? $link : $rml;
}

// Remove or amend the 'read more' link
add_filter( 'the_content_more_link', 'ipress_read_more_link' ); 

/**
 * Custom View Article link to Post
 *
 * @param string
 * @return $string
 */
function ipress_view_more( $more ) {

    global $post;

    // get fiterable link & set markup
    $view_more = (bool)apply_filters( 'ipress_view_more', false );
    $view_article = '<a class="view-article" href="' . get_permalink( $post->ID ) . '">' . __( 'View Article', 'ipress' ) . '</a>';

    // return filterable markup
    return ( $view_more ) ? apply_filters( 'ipress_view_more_link', $view_article ) : $more;
}

// Add 'View Article' button instead of [...] for Excerpts
add_filter( 'excerpt_more', 'ipress_view_more' ); 

/**
 * Video Embedding Wrapper
 *
 * @param   string
 * @return  string
 */
function ipress_embed_video_html( $html ) {
    return apply_filters( 'ipress_embed_video', sprintf( '<div class="video-container">%s</div>', $html ), $html );
}

// Wrapper for video embedding - generic & jetpack
add_filter( 'embed_oembed_html', 'ipress_embed_video_html', 10, 3 );
add_filter( 'video_embed_html', 'ipress_embed_video_html' ); 

//end
