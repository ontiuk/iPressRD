<form role="search" method="get" id="searchform" class="search-form" action="<?= home_url('/'); ?>">
    <div>
        <label class="screen-reader-text" for="s">Search for:</label>
        <input class="search-input" type="search" name="s" placeholder="<?php _e( 'To search, type and hit enter.', 'ipress' ); ?>">
        <button class="search-submit" type="submit" role="button"><?php _e( 'Search', 'ipress' ); ?></button>
    </div>
</form>
