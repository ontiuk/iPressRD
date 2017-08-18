<?php if ( is_active_sidebar( 'secondary' ) ) : ?> 
<aside class="sidebar sidebar-secondary" role="complementary">
    <div class="sidebar-widget">
        <?php dynamic_sidebar( 'secondary' ); ?>
    </div>
</aside>
<?php endif; ?>
