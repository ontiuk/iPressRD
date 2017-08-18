<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Template for the generic archive article content
 * 
 * @package     iPress\Templates
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */
?>

<main id="main" class="content" <?php ipress_attr('main'); ?>>
<?php 
    while ( have_posts() ) : the_post(); 
        get_template_part( 'content', get_post_format() );
    endwhile; 
?>
</main>
<?php get_template_part( 'pagination' ); ?>
