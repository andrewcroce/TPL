# Wordpress Foundation Starter Theme

A starting point for Wordpress Themes, built on [Foundation](http://foundation.zurb.com/). This theme has become largely dependent on [Codekit](https://incident57.com/codekit/), since its awesome.

It is packaged as a submodule of [WordPress-Skeleton, Fork](https://bitbucket.org/andrewcroce/wordpress-skeleton)

## Requirements

  * [Node.js](http://nodejs.org)
  * [bower](http://bower.io): `npm install bower -g`
  * [Codekit](https://incident57.com/codekit/)

## Recommended Plugins

The theme will prompt you to install these plugins automatically. Thanks to Thomas Griffen's [Plugin Activation Class](https://github.com/thomasgriffin/TGM-Plugin-Activation).

  * [Advanced Custom Fields](http://www.advancedcustomfields.com/). Fantastic plugin for creating custom fields. I never leave home without it. Some built in theme functionality will not work without it, but it won't fail catastrophically without it, however.
  * [Custom Post Type UI](https://wordpress.org/plugins/custom-post-type-ui/). Not strictly necessary, but it makes creating custom post types and taxonomies a breeze.
  * [iThemes Security](https://wordpress.org/plugins/better-wp-security/). Formerly Better WP Security. A good idea, in general. Unless you want hackers.
  * [W3 Total Cache](https://wordpress.org/plugins/w3-total-cache/). I mainly use this for managing a generic mirror CDN on my local site copies. But it provides some powerful caching too.
  * [Manual Image Crop](https://wordpress.org/plugins/manual-image-crop/). This should be part of the core media library functionality. Lets you crop your images individually for each defined image size.
  * [Force Regenerate Thumbnails](https://wordpress.org/plugins/force-regenerate-thumbnails/). Like it says, regenerates all your image sizes.
## Installation

  * Run `bower install` to install the latest version of Foundation and its dependencies
  * Or, preferably, install Foundation's bower component through Codekit


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