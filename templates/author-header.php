<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Template for displaying the author archive template header
 * 
 * @package     iPress\Templates
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */
?>

<section class="content-header">
    <h3>Author: <?php echo get_the_author(); ?></h3>
</section>

<section class="author">
    <?php if ( get_the_author_meta('description') ) : ?>
    <h3><?php _e( 'About ', 'ipress' ); echo get_the_author() ; ?></h3>
    <?php echo wpautop( get_the_author_meta('description') ); ?>
    <?php endif; ?>
</section>
