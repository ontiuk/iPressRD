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

<?php 

// cats
$post_cats = wp_get_post_terms( get_the_ID(), [ 'category' ] );
if ( $post_cats && !is_wp_error($post_cats) ) {
    $post_terms = [];  
    foreach ( $post_cats as $c ) { 
        $post_term_name = $c->name; 
        $post_term_link = get_term_link( $c );
        $post_terms[] = '<a href="'. $post_term_link.'">'.$post_term_name.'</a>';    
    }
    $post_terms = join( ', ', $post_terms );
} else { $post_terms = ''; }

?>
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
            <span class="author"><?php _e( 'Published by', 'ipress' ); ?> <?php the_author_posts_link(); ?></span>
        </div>
    </header>

    <section class=entry-content">
        <?php the_content(); ?>
    </section>

    <footer class="entry-footer">
        <?php if ( !empty( $post_terms ) ) : ?>
        <div class="item-cats">Posted in: <?= $post_terms; ?></div>
        <?php endif; ?>
        <?php
            edit_post_link(
                sprintf(
                    esc_html__( 'Edit %s', 'ipress' ),
                    the_title( '<span class="screen-reader-text">"', '"</span>', false )
                ),
                '<span class="edit-link">',
                '</span>'
            );
        ?>
    </footer>
</article>
