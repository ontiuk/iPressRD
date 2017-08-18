<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Template for displaying the portfolio archive template header
 * 
 * @package     iPress\Templates
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */
?>

<?php global $wp_query; ?>

<section class="content-header">
    <h3>Archive: <?= sprintf( __( '%s Search Results for ', 'ipress' ), $wp_query->found_posts ); echo get_search_query(); ?></h3>
</section>
