<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Template for displaying the main page article content
 * 
 * @package     iPress\Templates
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */
?>

<main id="main" class="content" <?php ipress_attr('main'); ?>>

    <article id="post-<?php the_ID(); ?>" <?php post_class('entry'); ?>>

        <?php 
        if ( has_post_thumbnail() ) :
            $image_id = get_post_thumbnail_id( get_the_ID() );
            $image = wp_get_attachment_image_src( $image_id, 'thumbnail' ); 
            if ( $image ) :
        ?>
        <div class="entry-image">
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                <img src="<?= $image[0]; ?>" />
            </a>
        </div>
        <?php   
            endif; 
        endif;
        ?>

        <header class="entry-header">
            <h1><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
            <div class="entry-meta">
                <time datetime="<?php the_time('Y-m-d'); ?> <?php the_time('H:i'); ?>">
                    Posted On: <?php the_date(); ?> <?php the_time(); ?>
                </time>
            </div>
        </header>

        <section class=entry-content">
        <?php 
            the_content();
            wp_link_pages( array(
                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ipress' ),
                'after'  => '</div>',
            ) );
        ?>
        </section>

        <footer class="entry-footer">
        <?php
            edit_post_link(
                sprintf(
                    /* translators: %s: Name of current post */
                    esc_html__( 'Edit %s', 'ipress' ),
                    the_title( '<span class="screen-reader-text">"', '"</span>', false )
                ),
                '<span class="edit-link">',
                '</span>'
            );
        ?>
        </footer>
    </article>
</main>
