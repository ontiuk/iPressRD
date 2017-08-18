<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Post list template when static file set as home page
 * 
 * @package     iPress\Templates
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */
?>

<?php get_header(); ?>

    <?php get_template_part( 'templates/home-header' ); ?> 

    <section class="content-wrap">

        <?php if ( have_posts() ) : ?>
        
            <?php get_template_part( 'templates/content-loop' ); ?>

        <?php else: ?>

            <?php get_template_part( 'templates/content-none' ); ?>

        <?php endif; ?>
        
    </section>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
