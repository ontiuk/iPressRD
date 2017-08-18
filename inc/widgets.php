<?php 

/**
 * iPress - WordPress Base Theme                       
 * ==========================================================
 *
 * Theme widgets
 * 
 * @package     iPress\Widgets
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */

//------------------------------------------
//  Widget Loading 
//------------------------------------------

/**
 * Widget Autoload
 * - search parent & child theme for widgets
 *
 * @param   string $widget
 * @return  boolean
 */
function ipress_widget_autoload( $widget ) {

    // syntax for widget classname to file
    $classname = str_replace( '_', '-', strtolower( $widget ) );
    //todo: file name without wp_xxx?

    // create the actual filepath
    $file_path = IPRESS_WIDGETS_DIR . DIRECTORY_SEPARATOR . $classname . '.php';

    // check if the file exists in parent theme
    if ( file_exists( $file_path ) && is_file( $file_path ) ) { include $file_path; return TRUE; }

    // bad file or path?
    return false;
}

/**
 * Load & Initialise default widgets
 */
function ipress_widgets_init() {

    // contruct widgets list
    $widgets = apply_filters( 'ipress_widgets', [] );

    // register widgets
    foreach ( $widgets as $widget ) {

        // load widget file... spl_autoload might be better
        if ( ! ipress_widget_autoload( $widget ) ) { continue; }

        // register widget
        register_widget( $widget );
    }
}

//------------------------------------------
//  Load Widgets 
//------------------------------------------

// Core widget initialisation
add_action( 'widgets_init', 'ipress_widgets_init' );    

//end
