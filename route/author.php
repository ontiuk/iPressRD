<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Template for displaying generic author archives
 * 
 * @package     iPress\Templates
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */
?>

<?php get_header(); ?>

    <?php if ( have_posts() ) : the_post();?>

    <?php get_template_part( 'templates/author-header' ); ?>
    
    <?php rewind_posts(); ?>

    <section class="content-wrap">

        <?php get_template_part( 'templates/content-loop' ); ?>

    <?php else: ?>

    <section class="content-wrap">

        <?php get_template_part( 'templates/content-none' ); ?>

    <?php endif; ?>

    </section>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
