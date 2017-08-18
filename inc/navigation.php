<?php 

/**
 * iPress - WordPress Base Theme                       
 * ==========================================================
 *
 * Theme navigation functions & functionality
 * 
 * @package     iPress\Navigation
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
//  Menu & Navigation Functionality
//
// - ipress_has_nav_menu
// - ipress_has_menu
// - ipress_get_nav_menu_items
// - ipress_nav_menu_items
// -     
// - ipress_get_menu_nav
// - ipress_menu_nav
// - ipress_get_menu
// - ipress_menu
// - 
// - ipress_menu_subnav
// - 
// - ipress_get_nav_menu
// - ipress_nav_menu
// - ipress_nav_menu_location
// - 
// - ipress_get_mega_nav
// - ipress_mega_nav
// 
//----------------------------------------------

/**
 * Determine if a theme supports a particular menu location. 
 * - Case sensitive, so camel-case location.
 * -  has_nav_menu alternative
 *
 * @param  string $location
 * @return boolean 
 */
function ipress_has_nav_menu( $location ) {

    // set the menu name
    if ( empty( $location ) ) { return false; }

    // retrieve registered menu locations
    $locations = array_keys( get_nav_menu_locations() );

    // test location correctly registered
    return in_array( $location, $locations );
}

/**
 * Determine if a theme has a particular menu registered
 * - Case sensitive, so camel-case menu.
 *
 * @param  string $menu
 * @return boolean 
 */
function ipress_has_menu( $menu ) {

    // set the menu name
    if ( empty( $menu ) ) { return false; }

    // retrieve registered menu locations
    $menus = wp_get_nav_menus();

    // none registered
    if ( empty( $menus ) ) { return false; }

    // registered
    foreach ( $menus as $m ) {
        if ( $menu === $m->name ) { return true; }
    }

    // default
    return false;
}

/**
 * Retrieve menu items for a menu by location
 *
 * @param  string $menu Name of the menu location
 * @return array
 */
function ipress_get_nav_menu_items( $menu ) {

    // set the menu name
    if ( empty( $menu ) ) { return false; }

    // retrieve registered menu locations
    $locations = get_nav_menu_locations();

    // test menu is correctly registered
    if ( !isset( $locations[ $menu ] ) ) { return false; }

    // retrieve menu set against location
    $menu = wp_get_nav_menu_object( $locations[ $menu ] );
    if ( false === $menu ) { return false; }

    // retrieve menu items from menu
    $menu_items = wp_get_nav_menu_items( $menu->term_id );

    // no menu items?
    return ( empty( $menu_items ) ) ? false : $menu_items;
}

/**
 * Output menu items for a menu by location
 *
 * @param  string $menu Name of the menu location
 * @return array
 */
function ipress_nav_menu_items( $menu ) {
    echo ipress_get_nav_menu_items( $menu );
}

/**
 * Navigation display via locations
 *
 * @param   string  $menu_name
 * @param   array   $args
 */
function ipress_get_menu_nav( $location, $args=[] ) {

    $defaults = [
        'class'         => '',
        'itemclass'     => '',
        'subclass'      => '',
        'submenuclass'  => '',
        'subclass2'     => '',
        'submenuclass2' => ''
    ];

    // set the menu name
    if ( empty( $location ) ) { return; }

    // parse args and merge with defaults
    $args = wp_parse_args( $args, $defaults );

    // retrieve registered menu locations
    $locations = get_nav_menu_locations();

    // registered menu at location
    if ( ! has_nav_menu( $location ) ) { return; }

    // retrieve menu set against location
    $menu = wp_get_nav_menu_object( $locations[ $location ] );
    if ( false === $menu ) { return; }

    // retrieve menu items from menu
    $menu_items = wp_get_nav_menu_items( $menu->term_id );

    // no menu items?
    if ( empty( $menu_items ) ) { return; }

    // structure list class
    if ( !empty( $args['class'] ) ) {
        //array or string
        $class = ( is_array( $args['class'] ) ) ? join( ' ', $args['class'] ) : trim( $args['class'] );
        $class = ( empty( $class ) ) ? '' : sprintf( ' class="%s">', $class );
    } else { $class = ''; }
    
    // start menu... modify classes
    $menu_wrap_open = ipress_html( [ 
        'html'  => '<ul id="menu-' . $menu->name . '"' . $class . '>', 
        'echo'  => false
    ] );
    $menu_wrap_close = '</ul>'; 

    // add list items
    $count = 0; 
    $menu_list = '';
    foreach ( (array) $menu_items as $key => $menu_item ) {        

        // parent?
        if ( $menu_item->menu_item_parent > 0 ) { continue; }

        // submenu?
        $submenu = ipress_menu_subnav( $menu_items, $menu_item->ID );
        
        // menu class
        $item_class = ( empty( $args['itemclass'] ) ) ? array_filter( $menu_item->classes ) : [ $args['itemclass'] ];

        // submenu?
        if ( $submenu ) {
            $subclass = ( isset( $args['subclass'] ) && !empty( $args['subclass'] ) ) ? $args['subclass'] : '';
            $submenuclass = ( isset( $args['submenuclass'] ) && !empty( $args['submenuclass'] ) ) ? sprintf( ' class="%s"', $args['submenuclass'] ) : '';
            $item_class[] = $subclass;
        }

        // set up class
        $class  = ( empty( $item_class ) ) ? '' : sprintf( ' class="%s"', join( ' ', $item_class ) );

        // menu construct
        $menu_list_item  = sprintf( '<li%s>', $class );
        $menu_list_item .= sprintf( '<a href="%s">%s</a>', $menu_item->url, $menu_item->title );

        // submenu?
        if ( $submenu ) {
            $menu_list_item2 = sprintf( '<ul%s>', $submenuclass );
            foreach ( $submenu as $k=>$m ) {

                // submenu2?
                $submenu2 = ipress_menu_subnav( $menu_items, $m->ID );
                if ( $submenu2 ) {
                    $subclass2 = ( isset( $args['subclass2'] ) && !empty( $args['subclass2'] ) ) ? sprintf( ' class="%s"', $args['subclass2'] ) : '';
                    $submenuclass2 = ( isset( $args['submenuclass2'] ) && !empty( $args['submenuclass2'] ) ) ? sprintf( ' class="%s"', $args['submenuclass2'] ) : '';
                }

                // menu construct
                $menu_list_item2 .= ( $submenu2 ) ? sprintf( '<li%s>', $subclass2 ) : '<li>';
                $menu_list_item2 .= sprintf( '<a href="%s">%s</a>', $m->url, $m->title );

                // submenu2?
                if ( $submenu2 ) {
                    $menu_list_item3 = sprintf( '<ul%s>', $submenuclass2 );
                    foreach ( $submenu2 as $k2=>$m2 ) {
                        $menu_list_item3 .= sprintf( '<li><a href="%s">%s</a></li>', $m2->url, $m2->title );
                    }
                    $menu_list_item2 .= $menu_list_item3 . '</ul>';
                }

                $menu_list_item2 .= '</li>';
            }
            $menu_list_item .= $menu_list_item2 . '</ul>';
        }

        $menu_list .= $menu_list_item . '</li>';
    }

    // construct nav output
    $nav_output = $menu_wrap_open . $menu_list . $menu_wrap_close;
    $filter_location = 'ipress_' . $location . '_menu_nav';

    // filter the navigation markup
    return apply_filters( $filter_location, $nav_output, $location, $args );
}

/**
 * Navigation display via locations 
 *
 * @param   string  $menu_name
 * @param   array   $args
 */
function ipress_menu_nav( $location = '', $args = [] ) {
    echo ipress_get_menu_nav( $location, $args );
}

/**
 * Navigation display via locations
 *
 * @param   string  $menu_name
 * @param   array   $args
 */
function ipress_get_menu( $menu_name, $args = [] ) {

    $defaults = [
        'class'         => '',
        'subclass'      => '',
        'submenuclass'  => '',
        'subclass2'     => '',
        'submenuclass2' => ''
    ];

    // set the menu name
    if ( empty( $menu_name ) ) { return; }

    // parse args and merge with defaults
    $args = wp_parse_args( $args, $defaults );

    // registered menu
    if ( ! ipress_has_menu( $menu_name ) ) { return; }

    // retrieve menu set against location
    $menu = wp_get_nav_menu_object( $menu_name );
    if ( false === $menu ) { return; }

    // retrieve menu items from menu
    $menu_items = wp_get_nav_menu_items( $menu->term_id );

    // no menu items?
    if ( empty( $menu_items ) ) { return; }

    // structure list class
    if ( !empty( $args['class'] ) ) {
        
        // array or string
        $class = ( is_array( $args['class'] ) ) ? join( ' ', $args['class'] ) : trim( $args['class'] );
        $class = ( empty( $class ) ) ? '' : sprintf( ' class="%s">', $class );

    } else { $class = ''; }

    // start menu... modify classes
    $menu_wrap_open = ipress_html( [ 
        'html'  => '<ul id="menu-' . $menu_name . '"' . $class . '>', 
        'echo'  => false
    ] );
    $menu_wrap_close = '</ul>'; 

    // add list items
    $count = 0; 
    $menu_list = '';
    foreach ( (array) $menu_items as $key => $menu_item ) {        

        // parent?
        if ( $menu_item->menu_item_parent > 0 ) { continue; }

        // submenu?
        $submenu = ipress_menu_subnav( $menu_items, $menu_item->ID );
        
        // menu class
        $item_class = array_filter( $menu_item->classes );

        // submenu?
        if ( $submenu ) {
            $subclass = ( isset( $args['subclass'] ) && !empty( $args['subclass'] ) ) ? $args['subclass'] : '';
            $submenuclass = ( isset( $args['submenuclass'] ) && !empty( $args['submenuclass'] ) ) ? sprintf( ' class="%s"', $args['submenuclass'] ) : '';
            $item_class[] = $subclass;
        }

        // set up class
        $class  = ( empty( $item_class ) ) ? '' : sprintf( ' class="%s"', join( ' ', $item_class ) );

        // menu construct
        $menu_list_item  = sprintf( '<li%s>', $class );
        $menu_list_item .= sprintf( '<a href="%s">%s</a>', $menu_item->url, $menu_item->title );

        // submenu?
        if ( $submenu ) {

            // menu construct
            $menu_list_item2 = sprintf( '<ul%s>', $submenuclass );
            foreach ( $submenu as $k=>$m ) {

                // submenu2?
                $submenu2 = ipress_menu_subnav( $menu_items, $m->ID );
                if ( $submenu2 ) {
                    $subclass2 = ( isset( $args['subclass2'] ) && !empty( $args['subclass2'] ) ) ? sprintf( ' class="%s"', $args['subclass2'] ) : '';
                    $submenuclass2 = ( isset( $args['submenuclass2'] ) && !empty( $args['submenuclass2'] ) ) ? sprintf( ' class="%s"', $args['submenuclass2'] ) : '';
                }

                // menu construct
                $menu_list_item2 .= ( $submenu2 ) ? sprintf( '<li%s>', $subclass2 ) : '<li>';
                $menu_list_item2 .= sprintf( '<a href="%s">%s</a>', $m->url, $m->title );

                // submenu2?
                if ( $submenu2 ) {
                    $menu_list_item3 = sprintf( '<ul%s>', $submenuclass2 );
                    foreach ( $submenu2 as $k2=>$m2 ) {
                        $menu_list_item3 .= sprintf( '<li><a href="%s">%s</a></li>', $m2->url, $m2->title );
                    }
                    $menu_list_item2 .= $menu_list_item3 . '</ul>';
                }

                $menu_list_item2 .= '</li>';
            }
            $menu_list_item .= $menu_list_item2 . '</ul>';
        }

        $menu_list .= $menu_list_item . '</li>';
    }

    // construct nav output
    $nav_output = $menu_wrap_open . $menu_list . $menu_wrap_close;
    $filter_location = 'ipress_' . $menu_name . '_menu';

    // filter the navigation markup.
    return apply_filters( $filter_location, $nav_output, $menu_name, $args );
}

/**
 * Navigation display via menu
 *
 * @param   string  $menu_name
 * @param   array   $args
 * @since   1.0
 */
function ipress_menu( $menu = '', $args = [] ) {
    echo ipress_get_menu( $menu, $args );
}

/**
 * Submenu items for parent menu
 *
 * @param   array   $menu
 * @param   string  $menu
 */  
function ipress_menu_subnav( $menu, $parent ) {

    // subnav items
    $nav = [];

    // has parent?        
    foreach ( $menu as $k=>$m ) {
        if ( (int)$m->menu_item_parent === $parent ) { 
            $nav[] = $menu[$k]; 
        }
    }

    // has menu
    return ( empty( $nav ) ) ? FALSE : $nav;   
}

/**
 * Navigation display via generic menu function
 *
 * @param   array $args
 * @return  string
 */
function ipress_get_nav_menu( $args = [] ) {

    $defaults = [
        'theme_location' => '',
        'menu'           => '',
        'container'      => '',
        'menu_class'     => 'menu nav-menu',
        'link_before'    => sprintf( '<span %s>', ipress_attr( 'nav-link' ) ),
        'link_after'     => '</span>',
        'nav-wrap'       => false,
        'echo'           => false
    ];

    // parse args and merge with defaults
    $args = wp_parse_args( $args, $defaults );

    // no menu assigned to theme location?
    if ( ! has_nav_menu( $args['theme_location'] ) ) { return; }

    // slugify the location for css
    $location = sanitize_key( $args['theme_location'] );

    // generic nav menu functionality
    $nav = wp_nav_menu( $args );

    // no nav?
    if ( ! $nav ) { return; }

    // nav container override
    if ( empty( $args['container'] ) ) {
        $nav_markup_open = ipress_html( [
            'html'      => '<nav %s>',
            'context'   => 'nav-' . $location,
            'echo'      => false
        ] );
        $nav_markup_close = '</nav>';
    } else {
        $nav_markup_open = $nav_markup_open = '';
    }

    // construct nav output
    $nav_output = ( $args['nav-wrap'] ) ? $nav_markup_open . $nav . $nav_markup_close : $nav;
    $filter_location = 'ipress_' . $location . '_nav_menu';

    // filter the navigation markup
    return apply_filters( $filter_location, $nav_output, $nav, $args );
}

/**
 * Navigation display via generic menu function
 *
 * @param   array $args
 */
function ipress_nav_menu( $args = [] ) {
    echo ipress_get_nav_menu( $args );
}

/**
 * Navigation display via generic menu function
 *
 * @param   string  menu location
 */
function ipress_nav_menu_location( $route ) {

    // defaults    
    $args = [
        'theme_location' => $route,
        'menu_class'     => 'menu nav-menu menu-' . $route,
        'nav-wrap'       => false
    ];

    // output nav menu
    echo ipress_get_nav_menu( $args );
}

/**
 * MegaNav via locations & menu
 *
 * @param   string  $menu_name
 * @param   array   $args
 * @return  string
 */
function ipress_get_mega_nav( $menu_name, $args = [] ) {

    // defaults
    $defaults = [
        'class'         => '',
        'subclass'      => '',
        'submenuclass'  => '',
        'cols'          => 4
    ];

    // set the menu name
    if ( empty( $menu_name ) ) { return; }

    // parse args and merge with defaults
    $args = wp_parse_args( $args, $defaults );

    // registered menu
    if ( ! ipress_has_menu( $menu_name ) ) { return; }

    // retrieve menu set against location
    $menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
    if ( false === $menu ) { return; }

    // retrieve menu items from menu
    $menu_items = wp_get_nav_menu_items( $menu->term_id );

    // no menu items?
    if ( empty( $menu_items ) ) { return; }

    // structure list class
    if ( !empty( $args['class'] ) ) {
        
        // array or string
        $class = ( is_array( $args['class'] ) ) ? join( ' ', $args['class'] ) : trim( $args['class'] );
        $class = ( empty( $class ) ) ? '' : sprintf( ' class="%s">', $class );

    } else { $class = ''; }

    // start menu... modify classes
    $menu_list_open = ipress_html( [ 
        'html'  => '<ul id="menu-' . $menu_name . '"' . $class . '>', 
        'echo'  => false
    ] );
    $menu_list_close = '</ul>'; 

    // add list items
    $count = 0;
    $menu_list = '';
    foreach ( (array) $menu_items as $key => $menu_item ) {        

        // parent?
        if ( $menu_item->menu_item_parent > 0 ) { continue; }

        // submenu?
        $submenu = ipress_menu_subnav( $menu_items, $menu_item->ID );
        
        // menu class
        $item_class = array_filter( $menu_item->classes );

        // submenu?
        if ( $submenu ) {
            $subclass = 'class="column-' . $args['cols'] . '"'; 
            $item_class[] = 'has-mega-menu';
            if ( isset( $args['subclass'] ) && !empty( $args['subclass'] ) ) {  
                $item_class[] = $args['subclass']; 
            }
        }

        // set up class
        $class  = ( empty( $item_class ) ) ? '' : ' class="' . join( ' ', $item_class ) . '"';

        // menu construct
        $menu_list_item  = sprintf( '<li%s>', $class );

        // start list
        if ( $submenu ) {
            $menu_list_item .= sprintf( '<a href=#>%s</a>', $menu_item->title );
            $menu_list_item2 = '<div class="mega-menu">';

            // calculate rows
            $items = count( $submenu );
            $rows = ( $items%$args['cols'] == 0 ) ? intval( $items / $args['cols'] ) : intval( $items / $args['cols'] ) + 1;

            for( $c=0; $c < $args['cols']; $c++ ) {
            
                $menu_list_item3 = sprintf( '<ul %s>', $subclass );

                for ( $r=0; $r < $rows; $r++ ) {
                    $mc = ( $c ) + ( $r * $args['cols'] );
                    if ( $mc >= $items ) { break; }
                    $menu_list_item3 .= sprintf( '<li><a href="%s">%s</a></li>', $submenu[$mc]->url, $submenu[$mc]->title );
                }
                
                $menu_list_item3 .= '</ul>';
                $menu_list_item2 .= $menu_list_item3;      
            }
            $menu_list_item .= $menu_list_item2 . '</div>';

        } else {
            $menu_list_item .= sprintf( '<a href="%s">%s</a>', $menu_item->url, $menu_item->title );
        }

        $menu_list .= $menu_list_item . '</li>';
    }

    // finish menu    
    $menu_list .= '</ul>';

    // output menu
    echo $menu_list;
}

/**
 * MegaNav via locations & menu
 *
 * @param   string  $menu_name
 * @param   array   $args
 * @return  string
 */
function ipress_mega_nav( $menu_name, $args = [] ) {
    echo ipress_get_mega_nav( $menu_name, $args );
}

//---------------------------------------------
// Navigation Action & Filter Functions
//---------------------------------------------

/**
 * Remove the <div> surrounding the dynamic navigation to cleanup markup
 *
 * @param   array
 * @raturn  array
 */
function ipress_nav_menu_args( $args = [] ) {

    // filterable menu args
    $nav_clean = apply_filters( 'ipress_nav_clean', false );
    if ( $nav_clean ) { $args['container'] = false; }

    // return menu args
    return $args;
}

// Remove surrounding <div> from WP Navigation
add_filter( 'wp_nav_menu_args', 'ipress_nav_menu_args' ); 

/**
 * Remove Injected classes, ID's and Page ID's from Navigation <li> items
 *
 * @param   array|string
 * @return  array|string
 */
function ipress_css_attributes_filter( $var ) {

    // filterable css attributes
    $css_attr       = (bool)apply_filters( 'ipress_css_attr', false );    
    $css_attr_val   = ( is_array( $var ) ) ? [] : '';

    // return attributes
    return ( $css_attr ) ? $css_attr_val : $var;
}

// Remove Navigation <li> injected classes (Commented out by default)
add_filter( 'nav_menu_css_class', 'ipress_css_attributes_filter', 99, 1 ); 
add_filter( 'nav_menu_item_id', 'ipress_css_attributes_filter', 99, 1 ); 
add_filter( 'page_css_class', 'ipress_css_attributes_filter', 99, 1 ); 

/**
 * Pass nav menu link attributes through attribute parser.
 * 
 * @param   object $item The current menu item.
 * @param   array $args An array of wp_nav_menu() arguments.
 * @param   array
 * @return  array Maybe modified menu attributes array.
 */
function ipress_nav_menu_link_attributes( $atts, $item, $args ) {

    // filterable nav assets
    $atts = ipress_parse_attr( 'nav-link', $atts );

    // return attributes
    return $atts;
}

// Adds nav menu link attributes
add_filter( 'nav_menu_link_attributes', 'ipress_nav_menu_link_attributes', 10, 3 );

//end
