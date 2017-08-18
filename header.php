<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= get_template_directory_uri(); ?>/favicon.ico">
    <link rel="apple-touch-icon" href="<?= get_template_directory_uri(); ?>/img/icons/touch.png">
    <?php wp_head(); ?>
</head>

<body <?php ipress_attr('body'); ?>>
    <div class="site-container">

        <?php get_template_part( 'templates/site-header' ); ?>

        <div id="content" class="site-content">
