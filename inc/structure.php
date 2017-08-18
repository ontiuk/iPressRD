<?php
/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Theme structure & header functionality
 * 
 * @package     iPress\Structure
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
//  Structure: Head - Doctype, Meta & Rel links
//
// - ipress_doctype
// - ipress_head
// - 
// - ipress_head - ipress_meta_name
// - ipress_head - ipress_meta_url
// - ipress_head - ipress_canonical
// - ipress_head - ipress_responsive_viewport
// - ipress_head - ipress_x_ua_compatible
// - ipress_head - ipress_meta_description
// - ipress_head - ipress_meta_home_keywords
// - ipress_head - ipress_meta_robots
// - ipress_head - ipress_dns_prefetch
// - ipress_head - ipress_touch
// - 
// - wp_head - ipress_site_icon
// - wp_head - ipress_do_meta_pingback
// - wp_head - ipress_paged_rel
//  
//----------------------------------------------

/**
 * HTML5 doctype markup generator - html, lang, head, charset 
 * - In theme use with do_action( 'ipress_doctype' );
 */
function ipress_do_doctype() {
?><!DOCTYPE html>
<html <?php language_attributes( 'html' ); ?>>
<head <?php echo ipress_attr( 'head' ); ?>>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php
}

// Generate the html5 doctype and opening markup.
add_action( 'ipress_doctype', 'ipress_do_doctype' );

/**
 * Trigger ipress_head through wp_head with high priority
 */
function ipress_head() {

    // filterable head usage - default off/false
    $ipress_head = apply_filters( 'ipress_head', false );
    if ( $ipress_head ) { do_action( 'ipress_head'); }
}

// Link ipress_head to wp_head with high priority
add_action( 'wp_head', 'ipress_head', 5 );

/**
 * Output meta tag for site name.
 */
function ipress_meta_name() {

    // not on home page
    if ( !is_front_page() ) { return; }

    // filterable name
    $name = apply_filters( 'ipress_meta_name', get_bloginfo( 'name' ) );

    // ok ...
    echo sprintf( '<meta itemprop="name" content="%s" />' . PHP_EOL, $name );
}

// Add SEO Site name for home page
add_action( 'ipress_head', 'ipress_meta_name', 5 );

/**
 * Output meta tag for site URL.
 */
function ipress_meta_url() {

    // not on home page
    if ( !is_front_page() ) { return; }

    // filterable data
    $home_url = apply_filters( 'ipress_meta_url', trailingslashit( home_url() ) );

    // ok...
    echo sprintf( '<meta itemprop="url" content="%s" />' . PHP_EOL, $home_url );
}

// Generate SEO Meta URL for site on home page
add_action( 'ipress_head', 'ipress_meta_url', 5 );

/**
 * Remove default WordPress canonical tag and output custom link tag
 */
function ipress_canonical() {

    // remove the WordPress canonical, set new link
    $canonical = esc_url( apply_filters( 'ipress_canonical', ipress_canonical_url() ) );

    // ok?
    if ( $canonical ) {
        remove_action( 'wp_head', 'rel_canonical' );
        echo sprintf( '<link rel="canonical" href="%s" />' . PHP_EOL,  $canonical );
    }
}

// Output canonical URL
add_action( 'ipress_head', 'ipress_canonical', 8 );

/**
 * Output the responsive theme viewport tag
 */
function ipress_responsive_viewport() {

    // valid support
    $ipress_viewport = apply_filters( 'ipress_viewport', false );
    if ( $ipress_viewport ) {

        // set the viewport default
        $viewport_default = apply_filters( 'ipress_viewport_default', 'width=device-width, initial-scale=1' );

        // output the viewport meta
        echo sprintf( '<meta name="viewport" content="%s" />' . PHP_EOL, esc_attr( $viewport_default ) );
    }
}

// Add a responsive viewport meta tag
add_action( 'ipress_head', 'ipress_responsive_viewport', 3 );

/**
 * Output the x-ua-compatible http-equiv tag
 */
function ipress_x_ua_compatible() {

    // valid support
    $ipress_compat = apply_filters( 'ipress_x_ua_compatible', false );
    if ( $ipress_compat ) {
        
        // set the viewport default
        $x_ua_default = apply_filters( 'ipress_x_ua_default', 'http-equiv="x-ua-compatible" content="ie=edge"' );

        // output the viewport meta
        echo sprintf( '<meta %s />' . PHP_EOL, esc_attr( $x_ua_default ) );
    }
}

// Add a x-ua-compatible meta tag
add_action( 'ipress_head', 'ipress_x_ua_compatible', 1 );

/**
 * Output the meta description for home page if set
 */
function ipress_meta_description() {

    // set up description
    $ipress_meta_desc = apply_filters( 'ipress_meta_description', false );
    if ( $ipress_meta_desc ) {

        // home page or not?
        if ( ipress_is_home_page() ) { 
            $description = apply_filters( 'ipress_meta_home_description', '' );
            $description = ( !empty( $description ) ) ? $description : get_bloginfo( 'description' );
        } else { 
            $description = apply_filters( 'ipress_meta_description', '' ); 
        }

        // add the description tag if one exists
        if ( $description ) {
            echo sprintf( '<meta name="description" content="%s" />' . PHP_EOL, esc_attr( $description ) );
        }
    }
}

// Generate the  meta description tag
add_action( 'ipress_head', 'ipress_meta_description' );

/**
 * Output the meta keywords based if available
 */
function ipress_meta_home_keywords() {

    global $wp_query;

    // set up keywords
    $ipress_keywords = apply_filters( 'ipress_keywords', false );
    if ( $ipress_keywords ) {

        // get keywords if set
        $keywords =  ( ipress_is_home_page() ) ? apply_filters( 'ipress_meta_home_keywords', '' ) :
                                                 apply_filters( 'ipress_meta_keywords', '' );

        // add the keywords tag if they exist - comma separated values or array
        if ( !empty( $keywords ) ) {
            $keywords = ( is_array( $keywords ) ) ? join( ', ', $keywords ) : trim( $keywords );
            echo sprintf( '<meta name="keywords" content="%s" />' . PHP_EOL, esc_attr( $keywords ) );
        }
    }
}

// Generate the homepage meta keywords tag
add_action( 'ipress_head', 'ipress_meta_home_keywords' );

/**
 * Output the index, follow, noodp, noydir, noarchive robots meta
 */
function ipress_meta_robots() {

    global $wp_query;

    // if the blog is private
    $ipress_robots = apply_filters( 'ipress_robots', false );
    if ( !get_option( 'blog_public' ) || !$ipress_robots ) { return; }

    // defaults
    $noarchive = (bool)apply_filters( 'ipress_robots_site_noarchive', false );
    $noodp     = (bool)apply_filters( 'ipress_robots_site_noodp', false );
    $noydir    = (bool)apply_filters( 'ipress_robots_site_noydir', false );

    // meta construct
    $meta = [
        'noindex'   => '',
        'nofollow'  => '',
        'noarchive' => ( $noarchive ) ? 'noarchive' : '',
        'noodp'     => ( $noodp ) ? 'noodp' : '',
        'noydir'    => ( $noydir ) ? 'noydir' : ''
    ];

    // home page?
    ( ipress_is_home_page() ) ? ipress_meta_robots_home( $meta ) : ipress_meta_robots_site( $meta );
}

// Generate robots meta tag for homepage
add_action( 'ipress_head', 'ipress_meta_robots' );

/**
 * Output the index, follow, noodp, noydir, noarchive robots meta for homepage
 * 
 * @param   array   $meta
 */
function ipress_meta_robots_home( $meta) {

    // check root page SEO settings, set noindex, nofollow and noarchive
    $home_noindex    = (bool)apply_filters( 'ipress_robots_home_noindex', false );
    $home_nofollow   = (bool)apply_filters( 'ipress_robots_home_nofollow', false );
    $home_noarchive  = (bool)apply_filters( 'ipress_robots_home_noarchive', false );

    // meta construct
    $meta['noindex']   = ( $home_noindex ) ? 'noindex' : $meta['noindex'];
    $meta['nofollow']  = ( $home_nofollow ) ? 'nofollow' : $meta['nofollow'];
    $meta['noarchive'] = ( $home_noarchive ) ? 'noarchive' : $meta['noarchive'];

    // strip empty array items
    $meta = array_filter( $meta );

    // add meta if any exist
    if ( $meta ) {
        echo sprintf( '<meta name="robots" content="%s" />' . PHP_EOL, implode( ',', $meta ) );
    }
}

/**
 * Output the index, follow, noodp, noydir, noarchive for archive pages
 *
 * @param   array   $meta
 */
function ipress_meta_robots_site( $meta ) {

    global $wp_query;

    // category, Tag & Taxonomy Archives
    if ( is_category() || is_tag() || is_tax() ) {

        // current term
        $term = $wp_query->get_queried_object();

        // category type
        if ( is_category() ) {
            $cat_noindex    = (bool)apply_filters( 'ipress_robots_cat_noindex', false );
            $cat_noarchive  = (bool)apply_filters( 'ipress_robots_cat_noarchive', false );

            $meta['noindex']   = ( $cat_noindex ) ? 'noindex' : $meta['noindex'];
            $meta['noarchive'] = ( $cat_noarchive ) ? 'noarchive' : $meta['noarchive'];
        }

        // tag type
        if ( is_tag() ) {

            // tag support
            $tag_noindex = (bool)apply_filters( 'ipress_robots_tag_noindex', false );
            $tag_noarchive = (bool)apply_filters( 'ipress_robots_tag_noarchive', false );

            $meta['noindex']   = ( $tag_noindex ) ? 'noindex' : $meta['noindex'];
            $meta['noarchive'] = ( $tag_noarchive ) ? 'noarchive' : $meta['noarchive'];
        }
    }

    // post type archive
    if ( is_post_type_archive() ) {
    
        // custom post type support?
        $cpt_noindex    = (bool)apply_filters( 'ipress_robots_cpt_noindex', false );
        $cpt_noarchive  = (bool)apply_filters( 'ipress_robots_cpt_noarchive', false );

        $meta['noindex']   = ( $cpt_noindex ) ? 'noindex' : $meta['noindex'];
        $meta['noarchive'] = ( $cpt_noarchive ) ? 'noarchive' : $meta['noarchive'];
    }

    // author archive
    if ( is_author() ) {

        // author level support 
        $author_noindex    = (bool)apply_filters( 'ipress_robots_author_noindex', false );
        $author_noarchive  = (bool)apply_filters( 'ipress_robots_author_noarchive', false );

        $meta['noindex']   = ( $author_noindex ) ? 'noindex' : $meta['noindex'];
        $meta['noarchive'] = ( $author_noarchive ) ? 'noarchive' : $meta['noarchive'];
    }

    // date archives
    if ( is_date() ) {
        
        // date level support
        $date_noindex    = (bool)apply_filters( 'ipress_robots_date_noindex', false );
        $date_noarchive  = (bool)apply_filters( 'ipress_robots_date_noarchive', false );

        $meta['noindex']   = ( $date_noindex ) ? 'noindex' : $meta['noindex'];
        $meta['noarchive'] = ( $date_noarchive ) ? 'noarchive' : $meta['noarchive'];
    }

    // search archives
    if ( is_search() ) {

        // search level support
        $search_noindex    = (bool)apply_filters( 'ipress_robots_search_noindex', false );
        $search_noarchive  = (bool)apply_filters( 'ipress_robots_search_noarchive', false );

        $meta['noindex']   = ( $search_noindex ) ? 'noindex' : $meta['noindex'];
        $meta['noarchive'] = ( $search_noarchive ) ? 'noarchive' : $meta['noarchive'];
    }

    // single post
    if ( is_singular() ) {
    
        // single page level support        
        $single_noindex    = (bool)apply_filters( 'ipress_robots_single_noindex', false );
        $single_noarchive  = (bool)apply_filters( 'ipress_robots_single_noarchive', false );

        $meta['noindex']   = ( $single_noindex ) ? 'noindex' : $meta['noindex'];
        $meta['noarchive'] = ( $single_noarchive ) ? 'noarchive' : $meta['noarchive'];
    }

    // strip empty array items
    $meta = array_filter( $meta );

    // add meta if any exist
    if ( $meta ) {
        echo sprintf( '<meta name="robots" content="%s" />' . PHP_EOL, implode( ',', $meta ) );
    }
}

//----------------------------------------------
//  Structure: Header Links
//----------------------------------------------

/**
 * Output the analytics dns-prefetch link tag to the head
 */
function ipress_dns_prefetch() {

    // filterable display
    $ipress_dns_prefetch = apply_filters( 'ipress_dns_prefetch', false );
    if ( $ipress_dns_prefetch ) {

        // set the viewport default
        $dns_prefetch = apply_filters( 'ipress_dns_prefetch_default', '//google-analytics.com' );

        // output the viewport meta
        echo sprintf( '<link href="%s" rel="dns-prefetch"/>' . PHP_EOL, esc_url( $dns_prefetch ) );
    }
}

// Add a dns-prefetch link tag
add_action( 'ipress_head', 'ipress_dns_prefetch' );

/**
 * Output the apple touch icon link tag to the head
 * - defaults to media folder icons image
 */
function ipress_touch() {

    // filterable display
    $ipress_touch = apply_filters( 'ipress_touch', false );
    if ( $ipress_touch ) { 
        
        // set the viewport default
        $apple_touch = apply_filters( 'ipress_touch_default', get_template_directory_uri() . '/media/icons/touch.png' );

        // output the viewport meta
        echo sprintf( '<link rel="apple-touch-icon-precomposed" href="%s" />' . PHP_EOL, esc_url( $apple_touch ) );
    }
}

// Add an apple touch meta tag
add_action( 'ipress_head', 'ipress_touch' );

/**
 * Echo favicon link if one is found with parent fallback
 */
function ipress_site_icon() {

    // filterable display
    $ipress_icon = apply_filters( 'ipress_icon', false );
    if ( $ipress_icon ) { 
        
        // use WP set site icon, if available
        if ( function_exists( 'has_site_icon' ) && has_site_icon() ) { return; }

        // get icon... assume .ico should be anyway, use an online converter if not
        $favicon = ( file_exists( IPRESS_CHILD_MEDIA_DIR . '/favicon.ico' ) ) ? IPRESS_PARENT_MEDIA_URL . '/favicon.ico' : '';

        // no .ico... do it here alternative
        $favicon = (string)apply_filters( 'ipress_site_icon_url', $favicon );

        // done...
        if ( !empty( $favicon ) ) {
            echo sprintf( '<link rel="Shortcut Icon" href="%s" type="image/x-icon" />' . PHP_EOL, esc_url( $favicon ) );
        }
    }   
}

// Output a favicon link
add_action( 'wp_head', 'ipress_site_icon' );

/**
 * Adds the pingback meta tag to the head so that other sites can know how to send a pingback to our site
 */
function ipress_do_meta_pingback() {

    // filterabe display
    $ipress_ping = apply_filters( 'ipress_ping', false );
    if ( $ipress_ping ) {
   
        // site pingback status...
        if ( 'open' === get_option( 'default_ping_status' ) ) {
            echo '<link rel="pingback" href="' . get_bloginfo( 'pingback_url' ) . '" />' . PHP_EOL;
        }
    }
}

// Head Pingbacks...
add_action( 'wp_head', 'ipress_do_meta_pingback' );

/**
 * Output rel links in the head to indicate previous and next pages in paginated archives and posts
 * @link http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
 */
function ipress_paged_rel() {

    global $wp_query;

    // filterable display
    $ipress_paged_rel = apply_filters( 'ipress_paged_rel', false );
    if ( $ipress_paged_rel ) { 
        
        // get pagination
        $paged = intval( get_query_var( 'paged' ) );
        $page  = intval( get_query_var( 'page' ) );
        $prev = $next = '';

        // single post?
        if ( ! is_singular() ) {
            $prev = $paged > 1 ? get_previous_posts_page_link() : $prev;
            $next = $paged < $wp_query->max_num_pages ? get_next_posts_page_link( $wp_query->max_num_pages ) : $next;
        } else {
            // no need for this on previews
            if ( is_preview() ) { return ''; }
    
            $numpages = substr_count( $wp_query->post->post_content, '<!--nextpage-->' ) + 1;
            if ( $numpages && ! $page ) { $page = 1; }

            if ( $page > 1 ) {
                $prev = ipress_paged_post_url( $page - 1 );
            }

            if ( $page < $numpages ) {
                $next = ipress_paged_post_url( $page + 1 );
            }
        }

        // set & display links
        if ( $prev ) { echo sprintf( '<link rel="prev" href="%s" />' . PHP_EOL, esc_url( $prev ) ); }
        if ( $next ) { echo sprintf( '<link rel="next" href="%s" />' . PHP_EOL, esc_url( $next ) ); }   
    }
}

// Rel Links for paged resurces:  
add_action( 'wp_head', 'ipress_paged_rel' );

//end
