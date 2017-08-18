<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Template for displaying the generic site footer
 * 
 * @package     iPress\Templates
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */
?>

<footer class="site-footer" <?php ipress_attr('footer'); ?>>
    <div class="footer-upper">
        <div><?php get_sidebar( 'footer-left' ); ?></div>
        <div><?php get_sidebar( 'footer-center' ); ?></div>
        <div><?php get_sidebar( 'footer-right' ); ?></div>
    </div>
    <div class="footer-lower">
        <span class="site-name"><?php get_bloginfo('name'); ?></span>
        <span class="copy">&copy; <?= date('Y'); ?></span>
    </div>
</footer>
