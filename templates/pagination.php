<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Template for displaying main pagination
 * 
 * @package     iPress\Templates
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */

global $wp_query;

if ( $wp_query->max_num_pages > 1 ) : ?>
<!-- pagination --> 
<section id="pagination" class="paginate">
    <ul class="pager">
        <li class="previous pull-left">
            <?= get_next_posts_link( 'More', $wp_query->max_num_pages ); ?>
        </li>
        <li class="next pull-right">
            <?= get_previous_posts_link( 'Back' ); ?>        
        </li>
    </ul>
</section>
<!-- //pagination --> 
<?php endif; ?>
