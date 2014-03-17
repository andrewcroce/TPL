# Wordpress Foundation Starter Theme

A starting point for Wordpress Themes, built on [Foundation](http://foundation.zurb.com/)

It is packaged as a submodule of [WordPress-Skeleton, Fork](https://bitbucket.org/andrewcroce/wordpress-skeleton)

## Packaged Plugins

  * [Advanced Custom Fields](http://www.advancedcustomfields.com/)
  * [Advanced Custom Fields Addon: Options Page](http://www.advancedcustomfields.com/add-ons/options-page/) **License Required**
  * [Advanced Custom Fields Addon: Repeater Field](http://www.advancedcustomfields.com/add-ons/repeater-field/) **License Required**
  * [Gravity Forms](http://www.gravityforms.com/) **License Required**

## Requirements

  * Ruby 1.9+
  * [Node.js](http://nodejs.org)
  * [compass](http://compass-style.org/): `gem install compass`
  * [bower](http://bower.io): `npm install bower -g`

## Installation

  * [Download or clone this starter compass project](https://bitbucket.org/andrewcroce/wordpress-foundation-starter-theme)
  * Run `bower install` to install the latest version of Foundation
  
Then when you're working on your project, just run the following command:

```bash
compass watch
```

## Upgrading

If you'd like to upgrade to a newer version of Foundation down the road just run:

```bash
bower update
```

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


