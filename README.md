iPress RD - WordPress Theme Framework 
=======================================

http://on.tinternet.co.uk

## About

iPress-RD is a Rapid Development Theme Framework for WordPress

Taking inspiration from other skeleton such as underscores, bones, and html5blank, as well as the latest default WordPress themes, this takes in the best practices in theme development to create a configurable and modular theme with a minimalist footprint.

- Theme set up with placeholders for important functionality 
- Bloat of standard theme initialization turned off by default
- Modular file structure with separation of concerns
- Clean folder and file structure
- Simple default theme that can be easily replaced with your own
- Lots of helpful stuff: helper functions, shortcodes, menus etc

Note: this is primarily a development version for personal projects and for understanding how a WordPress theme is structured. While it contains a very basic template, it's likely this will be replace all or in part by a more detailed template. It's there primarily to show the theme structure.

## Install

1. Upload the theme folder via FTP to your wp-content/themes/ directory.
2. Go to your WordPress dashboard and select Appearance.

## User Manual

I'll be updating this asap with details of available filters

## Widget Areas

* Primary Sidebar - This is the primary sidebar.
* Secondary Sidebar - This is the secondary sidebar.
* Header Sidebar - This is the widgeted area for the top right of the header.

## Support

Please visit the github page: https://github.com/ontiuk

## Folder Structure

Out of the box it works as a standard theme with a very basic template. There is an option to further tidy up the file/folder structure of the theme, with one main caveat. 
In order to use a clean folder structure template partials can been moved to /partials e.g. header.php / footer.php. 
A custom locate_template() function should replace the one in wp-includes/template.php, which adds the 2 filters used in theme/inc/template.php. The function is in the theme/lib folder in the file provided.
Obviously this is against WordPress standards, so this should be used carefully and not on any client sites. Any WordPress update may break the theme if the file is changed, so the custom function must be readded.
See https://core.trac.wordpress.org/ticket/13239 for potential/hopeful changes.

To use a modified template structure:
- header.php, footer.php, sidebar.php (and other sidebar files ) should be moved to /partials from the theme root.
- get_template_part() which currently uses a relative path to the templates directory e.g. get_template_part( 'templates/site-header' ) can revert to get_template_part( 'site-header' ).
- get_header() & get_footer() will automatically search for the header.php & footer.php files in the partials folder.

Any suggestions / issues etc please log an issue.

## Other Stuff

This is he precursor to 3 additional themes:
iPress-PT - Parent Theme. As iPress-RD but with filterable / customisable theme options and a more refined file structure. To be used as a parent theme with customization / setup / styling via a child theme
iPress-PT2 - Parent Theme. As iPress-PT but primary options moved to the theme customizer for greater admin micro management.
iPress-NG - Theme Framework integrating iPress with Angular and the WosrPress WP-API

