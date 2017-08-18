<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Template for the single post article
 * 
 * @package     iPress\Templates
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */
?>

<?php
// post thumbnail
if ( has_post_thumbnail() ) :
    $image_id = get_post_thumbnail_id( get_the_ID() );
    $image = wp_get_attachment_image_src( $image_id, 'deal' ); 
endif;
?>

<main id="main" role="main" class="article">

    <article id="post-<?php the_ID(); ?>" <?php post_class('item'); ?>>

        <div class="image">
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                <img src="<?= $image[0]; ?>" />
            </a>
        </div>

        <h1><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>

        <span class="date">
            <time datetime="<?php the_time('Y-m-d'); ?> <?php the_time('H:i'); ?>">
                <?php the_date(); ?> <?php the_time(); ?>
            </time>
        </span>

        <?php the_content(); ?>

        <?php the_tags( __( 'Tags: ', 'ipress' ), ', ', '<br>'); ?>

        <p><?php _e( 'Categorised in: ', 'ipress' ); the_category(', '); ?></p>
    
    </article>

</main>
