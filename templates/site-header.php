<?php 

/**
 * iPress - WordPress Theme Framework                       
 * ==========================================================
 *
 * Template for displaying the generic site header & menu
 * 
 * @package     iPress\Templates
 * @author      Stephen Betley
 * @copyright   OnTiUK
 * @link        http://on.tinternet.co.uk
 * @license     GPL-2.0+
 */
?>

<header class="site-header" <?php ipress_attr('header'); ?>>
    <div>
        <?php if ( ipress_is_home_page() ) : ?>
            <h1 class="site-title"><a href="<?= esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
        <?php else : ?>
            <p class="site-title"><a href="<?= esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
        <?php endif; ?>

        <?php $description = get_bloginfo( 'description', 'display' ); ?>
        <?php if ( $description ) : ?>
            <h2 class="site-description"><?= $description; ?></h2>
        <?php endif; ?>
    </div>
    <aside class="header-search">
        <?php get_search_form(); ?>
    </aside>
</header><!-- /header -->

<?php if ( has_nav_menu( 'primary' ) ) : ?>
<nav class="nav-menu nav-primary" <?php ipress_attr('navigation'); ?> >
    <?php ipress_menu_nav( 'primary' ); ?>
</nav>
<?php endif; ?>

<?php if ( has_nav_menu( 'secondary' ) ) : ?>
<nav class="nav-menu nav-secondary" <?php ipress_attr('navigation'); ?> >
    <?php ipress_menu_nav( 'secondary', [ 'itemclass' => 'menu-item' ] ); ?>
</nav>
<?php endif; ?>
