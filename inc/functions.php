<?php 
/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Theme functions & functionality
 * 
 * @package     iPress\Functions
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
//  Theme Functions
//
//  - ipress_is_home_page
//  - ipress_is_index
//  - ipress_is_plugin
//  - ipress_code
//  - ipress_format_kses
//  - ipress_format_allowedtags
//  - ipress_pagination
//  - ipress_numeric_posts_nav
//  - ipress_prev_next_post_nav
//  - ipress_date_archive_title
//  - ipress_time_diff
//  - ipress_get_params
//  - ipress_get_permalink_by_page
//  - ipress_paged_post_url
//  - ipress_canonical_url
//  - ipress_index
//  - ipress_content
//  - ipress_truncate
//  - ipress_analytics
//  - mb_strpos
//  - mb_strrpos
//  - mb_strlen
//  - mb_strtolower
//  
//----------------------------------------------

/**
 * Check if the root page of the site is being viewed.
 *
 * is_front_page() returns false for the root page of a website when
 * - the WordPress "Front page displays" setting is set to "A static page"
 * - "Front page" is left undefined
 * - "Posts page" is assigned to an existing page
 *
 * This function checks for is_front_page() or the root page of the website
 * in this edge case.
 *
 * @return boolean
 */
function ipress_is_home_page() {
    return ( is_front_page() || ( is_home() && get_option( 'page_for_posts' ) && ! get_option( 'page_on_front' ) && ! get_queried_object() ) ) ? true : false;
}

/**
 * Check if the page being viewed is the index page.
 *
 * @param   string
 * @return  boolean
 */
function ipress_is_index( $page ) {
    return ( basename( $page ) === 'index.php' );
}

/**
 * Detect active plugin by constant, class or function existence.
 *
 * @param  array    $plugins 
 * @return boolean
 */
function ipress_is_plugin( $plugins ) {

    // check by class
    if ( isset( $plugins['classes'] ) ) {
        foreach ( $plugins['classes'] as $name ) {
            if ( class_exists( $name ) ) { return true; }
        }
    }

    // check by function
    if ( isset( $plugins['functions'] ) ) {
        foreach ( $plugins['functions'] as $name ) {
            if ( function_exists( $name ) ) { return true; }
        }
    }

    // check by constant
    if ( isset( $plugins['constants'] ) ) {
        foreach ( $plugins['constants'] as $name ) {
            if ( defined( $name ) ) { return true; }
        }
    }

    // none found...
    return false;
}

/**
 * Mark up content with code tags. Escapes all HTML, so `<` gets changed to `&lt;` and displays correctly.
 *
 * @param   string $content Content to be wrapped in code tags.
 * @return  string Content wrapped in code tags.
 */
function ipress_code( $content ) {
    return '<code>' . esc_html( $content ) . '</code>';
}

/**
 * Wrapper for wp_kses() that can be used as a filter function.
 *
 * @uses    ipress_format_allowedtags() List of allowed HTML elements.
 * @param   string $string Content to filter through kses.
 * @return  string
 */
function ipress_format_kses( $string ) {
    return wp_kses( $string, ipress_format_allowedtags() );
}

/**
 * Return an array of allowed tags for output formatting. 
 * - Used by wp_kses() for sanitizing output.
 *
 * @return array Allowed tags.
 */
function ipress_format_allowedtags() {

    // return structured list
    return [
            'a'          => [ 'href' => [], 'title' => [] ],
            'b'          => [],
            'blockquote' => [],
            'br'         => [],
            'div'        => [ 'align' => [], 'class' => [], 'style' => [] ],
            'em'         => [],
            'i'          => [],
            'p'          => [ 'align' => [], 'class' => [], 'style' => [] ],
            'span'       => [ 'align' => [], 'class' => [], 'style' => [] ],
            'strong'     => []
    ];
}

//----------------------------------------------
// Pagination
//----------------------------------------------

/**
 * Pagination for archives 
 *
 * @global  $wp_query   WP_Query
 * @return  string
 */
function ipress_pagination() { 
    
    global $wp_query; 
    $big = 999999999; 

    // get pagination links
    $pages = paginate_links( [
            'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'    => '?paged=%#%',
            'current'   => max( 1, get_query_var('paged') ),
            'total'     => $wp_query->max_num_pages,
            'type'      => 'array',
            'prev_text' => __('Prev'),
            'next_text' => __('Next'),
    ] );

    // get paged value
    $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');

    // generate list if set
    if ( is_array( $pages ) && $paged ) {
        $list = '<ul class="pagination">';
        foreach ( $pages as $page ) { $list .= '<li>' . $page . '</li>'; }
        $list .= '</ul>';
    } else { $list = ''; }

    // return list
    return $list;
} 

/**
 * Echo archive pagination in Previous Posts / Next Posts format.
 */
function ipress_prev_next_posts_nav() {

    // get prev & next links
    $prev_link = get_previous_posts_link( '&#x000AB; ' . __( 'Previous', 'ipress' ) );
    $next_link = get_next_posts_link( __( 'Next', 'ipress' ) . ' &#x000BB;' );

    // set link html
    $prev = $prev_link ? '<div class="pagination-previous alignleft">' . $prev_link . '</div>' : '';
    $next = $next_link ? '<div class="pagination-next alignright">' . $next_link . '</div>' : '';

    // set link wrapper
    $nav = ipress_html( [
        'html'      => '<div %s>',
        'context'   => 'archive-pagination',
        'echo'      => false,
    ] );

    // construct link
    $nav .= $prev . $next . '</div>';
    
    // output if valid
    if ( $prev || $next ) { echo $nav; }
}

/**
 * Echo archive pagination in page numbers format.
 * The links, if needed, are ordered as:
 *
 *  * previous page arrow,
 *  * first page,
 *  * up to two pages before current page,
 *  * current page,
 *  * up to two pages after the current page,
 *  * last page,
 *  * next page arrow.
 *
 * @global  WP_Query    $wp_query Query object.
 * @return  null        Return early if on a single post or page, or only one page present.
 */
function ipress_numeric_posts_nav() {

    global $wp_query;

    // single post only
    if ( is_singular() ) { return; }

    // stop execution if there's only 1 page
    if( $wp_query->max_num_pages <= 1 ) { return; }

    // get pagination values
    $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
    $max   = intval( $wp_query->max_num_pages );

    // add current page to the array
    if ( $paged >= 1 ) { $links[] = $paged; }

    // add the pages around the current page to the array
    if ( $paged >= 3 ) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }

    // add the pages around the current page to the array
    if ( ( $paged + 2 ) <= $max ) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }

    // generate wrapper
    $output = ipress_html( [
        'html'      => '<div %s>',
        'context'   => 'archive-pagination',
        'echo'      => false
    ] );

    // start list
    $output .= '<ul>';

    // previous post link
    if ( get_previous_posts_link() ) {
        $output .= sprintf( '<li class="pagination-previous">%s</li>' . PHP_EOL, get_previous_posts_link( '&#x000AB; ' . __( 'Previous Page', 'ipress' ) ) );
    }

    // link to first page, plus ellipses if necessary
    if ( ! in_array( 1, $links ) ) {

        $class = ( 1 == $paged )? ' class="active"' : '';
        $output .= sprintf( '<li%s><a href="%s">%s</a></li>' . PHP_EOL, $class, esc_url( get_pagenum_link( 1 ) ), ' ' . '1' );

        if ( ! in_array( 2, $links ) ) {
            $output .= '<li class="pagination-omission">&#x02026;</li>' . PHP_EOL;
        }
    }

    // link to current page, plus 2 pages in either direction if necessary
    sort( $links );
    foreach ( (array) $links as $link ) {
        $class = ( $paged == $link ) ? ' class="active"  aria-label="' . __( 'Current page', 'ipress' ) . '"' : '';
        $output .= sprintf( '<li%s><a href="%s">%s</a></li>' . PHP_EOL, $class, esc_url( get_pagenum_link( $link ) ), ' ' . $link );
    }

    // link to last page, plus ellipses if necessary
    if ( ! in_array( $max, $links ) ) {

        if ( ! in_array( $max - 1, $links ) ) {
            $output .= '<li class="pagination-omission">&#x02026;</li>' . PHP_EOL;
        }

        $class = $paged == $max ? ' class="active"' : '';
        $output .= sprintf( '<li%s><a href="%s">%s</a></li>' . PHP_EOL, $class, esc_url( get_pagenum_link( $max ) ), ' ' . $max );
    }

    // next post link
    if ( get_next_posts_link() ) {
        $output .= sprintf( '<li class="pagination-next">%s</li>' . PHP_EOL, get_next_posts_link( __( 'Next Page', 'ipress' ) . ' &#x000BB;' ) );
    }

    // generate output
    $output .= '</ul></div>' . PHP_EOL;

    // send output
    echo $output;
}

/**
 * Display links to previous and next post, from a single post
 *
 * @return null Return early if not a post
 */
function ipress_prev_next_post_nav() {

    // single post only
    if ( ! is_singular( 'post' ) ) { return; }

    // start wrapper
    $output = ipress_html( [
        'html'      => '<div %s>',
        'context'   => 'adjacent-post-pagination',
        'echo'      => false
    ] );

    // genrate output
    $output .= sprintf( '<div class="pagination-previous alignleft">%s</div>', previous_post_link() );
    $output .= sprintf( '<div class="pagination-next alignright">%s</div></div>', next_post_link() );

    // send output
    echo $output;
}

//---------------------------------------------
//  Time & Date 
//---------------------------------------------

/**
 * Add custom headline and description to date archive pages
 */
function ipress_date_archive_title() {

    // date only
    if ( ! is_date() ) { return; }

    // what type of date?
    if ( is_day() ) {
        $headline = __( 'Archives for ', 'ipress' ) . get_the_date();
    } elseif ( is_month() ) {
        $headline = __( 'Archives for ', 'ipress' ) . single_month_title( ' ', false );
    } elseif ( is_year() ) {
        $headline = __( 'Archives for ', 'ipress' ) . get_query_var( 'year' );
    }

    // output data
    echo sprintf( '<div %s><h1 %s>%s</h1></div>', ipress_attr( 'date-archive-description' ), ipress_attr( 'archive-title' ), strip_tags( $headline ) );
}

/**
 * Calculate the time difference - a replacement for `human_time_diff()` until it is improved.
 * - time elapsed since a given date in text readable form
 *
 * @param   $older_date int
 * @param   $newer_date int 
 * @return  string
 */
function ipress_time_diff( $older_date, $newer_date = false ) {

    // current time if none set
    $newer_date = $newer_date ? $newer_date : time();

    // difference in seconds
    $since = absint( $newer_date - $older_date );

    // no difference
    if ( ! $since ) {
        return '0 ' . _x( 'seconds', 'time difference', 'ipress' );
    }
    
    // hold units of time in seconds, and their pluralised strings (not translated yet)
    $units = [
        [ 31536000, _nx_noop( '%s year', '%s years', 'time difference', 'ipress' ) ],  // 60 * 60 * 24 * 365
        [ 2592000, _nx_noop( '%s month', '%s months', 'time difference', 'ipress' ) ], // 60 * 60 * 24 * 30
        [ 604800, _nx_noop( '%s week', '%s weeks', 'time difference', 'ipress' ) ],    // 60 * 60 * 24 * 7
        [ 86400, _nx_noop( '%s day', '%s days', 'time difference', 'ipress' ) ],       // 60 * 60 * 24
        [ 3600, _nx_noop( '%s hour', '%s hours', 'time difference', 'ipress' ) ],      // 60 * 60
        [ 60, _nx_noop( '%s minute', '%s minutes', 'time difference', 'ipress' ) ],
        [ 1, _nx_noop( '%s second', '%s seconds', 'time difference', 'ipress' ) ],
    ];

    // step one: the first unit
    for ( $i = 0, $j = count( $units ); $i < $j; $i++ ) {
        $seconds = $units[$i][0];

        // finding the biggest chunk (if the chunk fits, break)
        if ( ( $count = floor( $since / $seconds ) ) != 0 ) { break; }
    }

    // translate unit string, and add to the output
    $output = sprintf( translate_nooped_plural( $units[$i][1], $count, 'ipress' ), $count );

    // note the next unit
    $ii = $i + 1;

    // step two: the second unit
    if ( $ii < $j ) {
        $seconds2 = $units[$ii][0];

        // check if this second unit has a value > 0
        if ( ( $count2 = (int) floor( ( $since - ( $seconds * $count ) ) / $seconds2 ) ) !== 0 )
            // add translated separator string, and translated unit string
            $output .= sprintf( ' %s ' . translate_nooped_plural( $units[$ii][1], $count2, 'ipress' ),  _x( 'and', 'separator in time difference', 'ipress' ),  $count2 );
    }

    // return output
    return $output;
}

//---------------------------------------------
//  Miscellaneous Functions          
//---------------------------------------------

/**
 * Retrieve function parameters
 *
 * @params  array|string $args
 * @return  array
 */
function ipress_get_params( $args ) {

    // passed as normal array or as regular url-compatible arguments...
    if ( is_array( $args ) && !empty( $args ) ) {
        // handle associative array for options
        foreach( array_keys( $args ) as $key ) { $params[strtolower( $key )] = $args[$key]; }
    } else {
        parse_str( $args, $params );
    }
    
    // return contruct
    return $params;
}

/**
 * Get url by page template 
 *
 * @param   string $template
 * @return  string
 */
function ipress_get_permalink_by_page( $template ) {

    // get pages
    $page = get_pages( [
        'meta_key' => '_wp_page_template',
        'meta_value' => $template . '.php'
    ] );

    // get the url
    return ( empty( $page ) ) ? '' : get_permalink( $page[0]->ID );
}

/**
 * Return the special URL of a paged post adapted from _wp_link_page() in WordPress core
 *
 * @param   int     $i The page number to generate the URL from.
 * @param   int     $post_id The post ID
 * @return  string  Unescaped URL
 */
function ipress_paged_post_url( $i, $post_id = 0 ) {

    global $wp_rewrite;

    // get post by ID
    $post = get_post( $post_id );

    // paged?
    if ( 1 == $i ) {
        $url = get_permalink( $post_id );
    } else {
        if ( '' == get_option( 'permalink_structure' ) || in_array( $post->post_status, [ 'draft', 'pending' ] ) ) {
            $url = add_query_arg( 'page', $i, get_permalink( $post_id ) );
        } elseif ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_on_front' ) == $post->ID ) {
            $url = trailingslashit( get_permalink( $post_id ) ) . user_trailingslashit( $wp_rewrite->pagination_base . '/' . $i, 'single_paged' );
        } else {
            $url = trailingslashit( get_permalink( $post_id ) ) . user_trailingslashit( $i, 'single_paged' );
        }
    }

    // return link
    return $url;
}

/**
 * Calculate and return the canonical URL.
 * 
 * @global  $wp_query   WP_Query
 * @return  string      The canonical URL, if one exists.
 */
function ipress_canonical_url() {

    global $wp_query;
    $canonical = '';

    // pagination values
    $paged = intval( get_query_var( 'paged' ) );
    $page  = intval( get_query_var( 'page' ) );

    // front page / home page
    if ( is_front_page() ) {
        $canonical = ( $paged ) ? get_pagenum_link( $paged ) : trailingslashit( home_url() );
    }

    // single post
    if ( is_singular() ) {
        $numpages = substr_count( $wp_query->post->post_content, '<!--nextpage-->' ) + 1;
        if ( ! $id = $wp_query->get_queried_object_id() ) { return; }
        $canonical = ( $numpages > 1 && $page > 1 ) ? ipress_paged_post_url( $page, $id ) : get_permalink( $id );
    }

    // archive
    if ( is_category() || is_tag() || is_tax() ) {
        if ( ! $id = $wp_query->get_queried_object_id() ) { return; }
        $taxonomy = $wp_query->queried_object->taxonomy;
        $canonical = ( $paged ) ? get_pagenum_link( $paged ) : get_term_link( (int) $id, $taxonomy );
    }

    // author
    if ( is_author() ) {
        if ( ! $id = $wp_query->get_queried_object_id() ) { return; }
        $canonical = ( $paged ) ? get_pagenum_link( $paged ) : get_author_posts_url( $id );
    }

    // search
    if ( is_search() ) {
        $canonical = get_search_link();
    }

    // return generated code
    return $canonical;
}

/**
 * Custom Excerpt - Callback for Index page Excerpts
 *
 * @param  integer
 * @return integer
 */
function ipress_index( $length ) { return absint( $length ); } 
  
/**
 * Create the Custom Excerpt 
 */
function ipress_excerpt( $length_callback = '', $more_callback = '' ) { 
    
    global $post; 

    // excerpt length    
    if ( !empty( $length_callback ) && function_exists( $length_callback ) ) { 
        add_filter( 'excerpt_length', $length_callback ); 
    } 

    // excerpt more
    if ( !empty( $more_callback ) && function_exists( $more_callback ) ) { 
        add_filter( 'excerpt_more', $more_callback ); 
    } 

    // get the excerpt
    $output = get_the_excerpt(); 
    $output = apply_filters( 'wptexturize', $output ); 
    $output = apply_filters( 'convert_chars', $output ); 

    // output the excerpt
    echo sprintf( '<p>%s</p>', $output ); 
} 

/**
 * Trim the content by word count
 * 
 * @param  integer
 * @param  string
 * @param  string
 */
function ipress_content( $length=54, $before='', $after='' ) {

    // get the content
    $content = get_the_content();

    // trim to word count and output
    echo sprintf( '%s', $before . wp_trim_words( $content, $length, '...' ) . $after );
}

/**
 * Return a phrase shortened in length to a maximum number of characters.
 * - Truncated at the last white space in the original string. 
 *
 * @param   string $text 
 * @param   integer $max_characters 
 * @return  string 
 */
function ipress_truncate( $text, $max_characters ) {

    // sanitize
    $text = trim( $text );

    // test text length
    if ( mb_strlen( $text ) > $max_characters ) {

        // truncate $text to $max_characters + 1
        $text = mb_substr( $text, 0, $max_characters + 1 );

        // truncate to the last space in the truncated string
        $text = trim( mb_substr( $text, 0, mb_strrpos( $text, ' ' ) ) );
    }

    // return truncated text
    return $text;
}

//---------------------------------------------
//  Google Analytics Shortcodes
//  - cutting edge browsers with IE degradation 
//  - preloads analytics
//---------------------------------------------

/**
 * Insert Google Analytics Code. 
 * - Use if visitors primarily use modern browsers to access your site
 * - preloads analytics with LT IE9 fallback
 * - place above </body> tag
 *
 * @param   array|string $code
 * @return  string
 */
function ipress_analytics( $code='' ) {

    // no code?
    if ( empty( $code ) ) { return; }
    
    // start data
    ob_start();
?>
    <!-- Google Analytics -->
    <script>
        window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
        ga('create', <?= $code; ?>, 'auto');
        ga('send', 'pageview');
    </script>
    <script async src='https://www.google-analytics.com/analytics.js'></script>
    <!-- End Google Analytics -->
<?php

    // get &store data
    $output = ob_get_clean();

    // return filterable data
    return apply_filters( 'ipress_analytics', $output );
}

//------------------------------------------
// Compatibility Functions
// -- compatibilty functions for mbstring
//------------------------------------------

/**
 * mb_strpos
 * 
 * @param   string  $haystack
 * @param   string  $needle
 * @param   integer $offset
 * @param   string  $encoding
 * @return  integer
 */
if ( ! function_exists( 'mb_strpos' ) ) {
    function mb_strpos( $haystack, $needle, $offset = 0, $encoding = '' ) {
        return strpos( $haystack, $needle, $offset );
    }
}

/**
 * mb_strrpos
 *
 * @param   string  $haystack
 * @param   string  $needle
 * @param   integer $offset
 * @param   string  $encoding
 * @return  integer
 */
if ( ! function_exists( 'mb_strrpos' ) ) {
    function mb_strrpos( $haystack, $needle, $offset = 0, $encoding = '' ) {
        return strrpos( $haystack, $needle, $offset );
    }
}

/**
 * mb_strlen
 *
 * @param   string  $string
 * @param   string  $encoding
 * @return  integer
 */
if ( ! function_exists( 'mb_strlen' ) ) {
    function mb_strlen( $string, $encoding = '' ) {
        return strlen( $string );   
    }
}

/**
 * mb_strtolower
 *
 * @param   string  $string
 * @param   string  $encoding
 * @return  string
 */
if ( ! function_exists( 'mb_strtolower' ) ) {
    function mb_strtolower( $string, $encoding = '' ) {
        return strtolower( $string );
    }
}

//end
