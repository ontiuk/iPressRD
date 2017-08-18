<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Template for displaying the main 404 page content
 * 
 * @package     iPress\Templates
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */
?>

<section id="error404">

    <article id="post-404">

        <h1><?php _e( 'Page not found', 'ipress' ); ?></h1>
        <h2><a href="<?= home_url(); ?>"><?php _e( 'Return home?', 'ipress' ); ?></a></h2>

    </article>

    <aside class="site-content"></aside>

</section>
