<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Template for displaying the generic page template header
 * 
 * @package     iPress\Templates
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */
?>

<?php global $post; ?>

<section id="header" class="site-header">
    <h1><?= get_the_title( $post->ID ); ?></h1>
</section>
