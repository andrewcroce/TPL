# Wordpress Foundation Starter Theme

A starting point for Wordpress Themes, built with [Foundation](http://foundation.zurb.com/) and Bourbon mixin library.

It is packaged as a submodule of [WordPress-Skeleton, Fork](https://bitbucket.org/andrewcroce/wordpress-skeleton)

## Requirements

  * [bower](http://bower.io): `npm install bower -g` Manage your libraries.
  * A SASS compiler. I recommend [Codekit](https://incident57.com/codekit/).
  * A Javascript compiler. I also recommend [Codekit](https://incident57.com/codekit/).

## Required/Recommended Plugins

The theme will prompt you to install these plugins automatically. Thanks to Thomas Griffen's [Plugin Activation Class](https://github.com/thomasgriffin/TGM-Plugin-Activation).

### Required
  * [Advanced Custom Fields](http://www.advancedcustomfields.com/). Fantastic plugin for creating custom fields. I never leave home without it. Some built in theme functionality will not work without it, but it won't fail catastrophically without it, however.
  * [Advanced Custom Fields Objects](https://bitbucket.org/bneff84/advanced-custom-fields-objects). A powerful OOP library for using ACF custom fields. Requires ACF, naturally.
  * [Custom Post Type UI](https://wordpress.org/plugins/custom-post-type-ui/). Not strictly necessary, but I deem it required! It makes creating custom post types and taxonomies a breeze.

### Recommended
  * [ACF Flexible Content](http://www.advancedcustomfields.com/resources/flexible-content/). Add multiple layouts with unique subfields (paid ACF add-on).
  * [ACF Options Page](http://www.advancedcustomfields.com/add-ons/options-page/). Add custom global site options (paid ACF add-on).
  * [ACF Repeater Field](http://www.advancedcustomfields.com/add-ons/repeater-field/). Add repeatable fields with subfields.
  * [(Advanced) Post Types Order](http://www.nsp-code.com/premium-plugins/wordpress-plugins/advanced-post-types-order/). Re-order your posts/custom post types. Pro version requires a license.
  * [Gravity Forms](http://www.gravityforms.com/). Probably the best form plugin for WP. Probably. Requires license.
  * [Admin Menu Editor (Pro)](http://adminmenueditor.com/). Edit the WP Admin menus to your heart's content. Pro version requires a license.
  * [iThemes Security](https://wordpress.org/plugins/better-wp-security/). Formerly Better WP Security. A good idea, in general. Unless you want hackers.
  * [W3 Total Cache](https://wordpress.org/plugins/w3-total-cache/). I mainly use this for managing a generic mirror CDN on my local site copies. But it provides some powerful caching too.
  * [Manual Image Crop](https://wordpress.org/plugins/manual-image-crop/). This should be part of the core media library functionality. Lets you crop your images individually for each defined image size.
  * [Force Regenerate Thumbnails](https://wordpress.org/plugins/force-regenerate-thumbnails/). Like it says, regenerates all your image sizes.

## Installation

  * Run `bower install` to install the latest version of Foundation and its dependencies, and Bourbon mixin library.
  * Or, preferably, install Foundation and Bourbon bower components through Codekit


## Use It

This theme presumes you have a working knowledge of how to create Wordpress themes.

  * [Learn about theme development](http://codex.wordpress.org/Theme_Development)
  * [Handy cheat sheet for template hierarchy and file naming](http://codex.wordpress.org/images/1/18/Template_Hierarchy.png)

### Theme Class File

*theme.class.php*

This file contains and initializes our main theme class. All theme setup and customizations should go here.

### Theme Functions File

*functions.php*

This file is loaded by Wordpress when the theme is loaded, so we bootstrap here.

It is also used as the name suggests, to create *theme functions* to be used in template files.

### The tpl() Function

*Located in functions.php*

This is a general purpose template include function. It will look for a PHP file matching the naming stucture {$prefix}-{$name}.php within a directory tpl_{$prefix}s (note the 's' at the end, the pluralization of the $prefix). So calling tpl('foo','bar'); will look for tpl_foos/foo_bar.php. If an object is passed in the third parameter, it will be available in the template as a variable matching the passed $prefix string. So calling tpl('foo','bar',$post); will make $post available in the template as a variable called $foo.

### Helper functions file

*includes/helpers.php*

This file contains a few helpful functions to use in your theme

*get_paged_vars()* Get an array of useful variables associated to the current page number.
*truncate()* Truncate any string of HTML. The function is aware of HTML tags, and will auto-close any broken elements.
*dump()* Echo some preformatted data for dev/debug.