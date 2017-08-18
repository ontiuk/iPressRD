<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Display single post content
 * 
 * @package     iPress\Templates
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */
?>

<?php get_header(); ?>

    <section class="content-wrap">

    <?php if ( have_posts() ) : the_post(); ?>

        <?php get_template_part( 'templates/post-header' ); ?>
   
        <?php get_template_part( 'templates/post-item' ); ?>
    
    <?php else: ?>

        <?php get_template_part( 'templates/post-none' ); ?>

    <?php endif; ?>

    </section>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
