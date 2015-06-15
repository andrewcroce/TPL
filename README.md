# Wordpress Foundation Starter Theme

A starting point for Wordpress Themes, built with [Foundation](http://foundation.zurb.com/) and Bourbon mixin library.

It is packaged as a submodule of [WordPress-Skeleton, Fork](https://bitbucket.org/andrewcroce/wordpress-skeleton)

## Requirements

  * [bower](http://bower.io): `npm install bower -g` Manage your libraries.
  * A SASS and Javascript compiler. I recommend you use Gulp with the provided gulpfile.js, or [Codekit](https://incident57.com/codekit/).

## Required/Recommended Plugins

The theme will prompt you to install these plugins automatically. Thanks to Thomas Griffen's [Plugin Activation Class](https://github.com/thomasgriffin/TGM-Plugin-Activation).

### Required
  * [Advanced Custom Fields](http://www.advancedcustomfields.com/). Fantastic plugin for creating custom fields. I never leave home without it. Some built in theme functionality will not work without it, but it won't fail catastrophically without it, however.
  * [Advanced Custom Fields Objects](https://bitbucket.org/bneff84/advanced-custom-fields-objects). A powerful OOP library for using ACF custom fields. Requires ACF, naturally. Thanks to [Brian Neff](https://bitbucket.org/bneff84) for this.
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
  * [Relevanssi](https://wordpress.org/plugins/relevanssi/) Replaces the standard WordPress search with a better search engine, with lots of features and configurable options.

## Installation

  * Make sure you have Node, NPM and Bower installed on your system. Gulp is also recommended for building, but you could use Codekit or some other build tool your grandma made.
  * Run `bower install` to install the latest version of Foundation and its dependencies, Bourbon mixin library, and other JS dependencies.
  * If you're using Gulp, run `npm install` to install node module dependencies for build processes.


## Use It

This theme presumes you have a working knowledge of how to create Wordpress themes.

  * [Learn about theme development](http://codex.wordpress.org/Theme_Development)
  * [Handy cheat sheet for template hierarchy and file naming](http://codex.wordpress.org/images/1/18/Template_Hierarchy.png)

If you are using Gulp...

  * Rename gulpfile-sample.js to gulpfile.js, and replace `%%YOUR LOCAL URL HERE%%` with your localhost development URL.
  * Run `gulp server` task to start the BrowserSync server and start watching files for changes.
  * SCSS and Javascript files will automatically be compiled, and changes will appear immediately.
  * See gulpfile.js for additional tasks you may want to run manually.
  * Do your thing.

### Theme Class File

*theme.class.php*

This file contains and initializes our main theme class. All theme setup and customizations should go here.

### Theme Functions File

*functions.php*

This file is loaded by Wordpress when the theme is loaded, so we bootstrap here.

It is also used as the name suggests, to create *theme functions* to be used in template files.


### The tpl() Function

*Located in functions.php*

This is a general purpose template include function. It will look for a PHP file matching the naming stucture `{$prefix}-{$name}.php` within a directory `tpl_{$prefix}s` *(note the 's' at the end, the pluralization of the $prefix)*.

So calling `tpl('foo','bar');` will look for `tpl_foos/foo_bar.php`.

The function also has an optional `$params` array, so you can pass any custom parameters to that function. It is recommended that you `extract($params)` at the beginning of each template file, so the parameters will be available as variables in the template, but you can reference them directly from the `params` array if you really want to.

### tpl_{name}() Functions

*Located in tpl_{prefix}s/{prefix}_functions.php*

Individual templates may (or may not) have more specific template functions that make use of the tpl() function. Each template type may have its own functions file with functions for one or more templates within that directory. This allows you to create custom template functions with unique parameters that follow the same conventions.

*It is highly recommended that you mark up the template functions and files with detailed comments that explain the available variables.*

### The index template

*tpl_templates/tpl_index.php*

This is an admin-selectable page template which allows any page to be used as a post type "index" page. It is meant as a replacement for WP's standard index and archive templates, which are just shy of useless.

By selecting this template for a page, an additional select-box is displayed allowing the user to select a public post type. This page will then display a paginated list of posts from that post type below its main content.

The ACF field group for this is automatically registered in *includes/acf_index_post_type_fields.php*. The select box is populated with post types in the `_acf_load_index_post_type()` function in *functions.php*.

### Theme Helper functions

*functions.php*

A few helpful functions to use in your theme

`get_index_vars()` Used on the index page template to get several variables related to the index.

`get_paged_vars()` Get an array of useful variables associated to the current page number.

`page_has_family_tree()` Check if a page has a family tree, i.e. it has children or ancestors

`get_page_tree()` Get the page hierarchy associated with a page

`add_descendents()` Recursive function to add an array of child posts to any post object

`nested_list()` Generic recursive function to generate a nested list of posts or taxonomy terms

`truncate()` Truncate any string of HTML. The function is aware of HTML tags, and will auto-close any broken elements. Thanks to [Brian Neff](https://bitbucket.org/bneff84) for this.

`dump()` Echo some preformatted data for dev/debug.