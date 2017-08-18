<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Template for displaying the generic archive template header
 * 
 * @package     iPress\Templates
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */
?>

<section class="content-header">
    <h3 class="title"><?= get_the_archive_title(); ?></h3>
    <div class="description"><?= get_the_archive_description(); ?></div>
</section>
