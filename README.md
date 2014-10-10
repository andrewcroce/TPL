# Wordpress Foundation Starter Theme

A starting point for Wordpress Themes, built on [Foundation](http://foundation.zurb.com/)

It is packaged as a submodule of [WordPress-Skeleton, Fork](https://bitbucket.org/andrewcroce/wordpress-skeleton)

## Packaged Plugins

  * [Advanced Custom Fields](http://www.advancedcustomfields.com/)
  * [Advanced Custom Fields Addon: Options Page](http://www.advancedcustomfields.com/add-ons/options-page/) **License Required**
  * [Advanced Custom Fields Addon: Repeater Field](http://www.advancedcustomfields.com/add-ons/repeater-field/) **License Required**
  * [Gravity Forms](http://www.gravityforms.com/) **License Required**

## Requirements

  * [Node.js](http://nodejs.org)
  * [bower](http://bower.io): `npm install bower -g`
  * [Codekit](https://incident57.com/codekit/)

## Installation

  * Run `bower install` to install the latest version of Foundation
  * Or, install Foundation's bower component through Codekit


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
