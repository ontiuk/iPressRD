<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Template for displaying the comments
 * 
 * @package     iPress\Templates
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */
?>

<section id="comments" class="comments">

    <?php if ( post_password_required() ) : ?>
    <p><?php _e( 'Post is password protected. Enter the password to view any comments.', 'ipress' ); ?></p>
</section>
    <?php return; endif; ?>

    <?php if ( have_comments() ) : ?>
        <h3><?php comments_number(); ?></h3>
        <ul>
            <?php wp_list_comments('type=comment&callback=ti_comments'); ?>
        </ul>
    <?php elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p><?php _e( 'Comments are closed here.', 'ipress' ); ?></p>
    <?php endif; ?>
    <?php comment_form(); ?>

</section>
