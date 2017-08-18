<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Google Analytics shortcode
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
//  Google Analytics Shortcodes
//  - cutting edge browsers with IE degradation 
//  - preloads analytics
//---------------------------------------------

/**
 * Insert Google Analytics Code - place above </body> tag
 * <?= do_shortcode('[ipress_analytics code="UA-xxxx"]'); ?>
 *
 * @param   array|string $atts 
 * @return  string
 */
function ipress_analytics_shortcode( $atts ) {

    $defaults = [ 'code' => '' ];
    $atts = shortcode_atts( $defaults, $atts, 'ipress_analytics' );

    // No code?
    if ( empty( $atts['code'] ) ) { return; }
    
    // Start data
    ob_start();
?>
    <!-- Google Analytics -->
    <script>
        window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
        ga('create', <?= $atts['code']; ?>, 'auto');
        ga('send', 'pageview');
    </script>
    <script async src='https://www.google-analytics.com/analytics.js'></script>
    <!-- End Google Analytics -->
<?php

    // Store data
    $output = ob_get_clean();

    return apply_filters( 'ipress_analytics_shortcode', $output, $atts );
}

// Get current user - should be used via do_shortcode
add_shortcode( 'ipress_analytics', 'ipress_analytics_shortcode' );

//end
