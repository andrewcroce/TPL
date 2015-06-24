<!DOCTYPE html>
<!--[if lte IE 8]>
<html class="lt-ie9" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
	
	<!--[if lte IE 8]>
	<link rel="stylesheet" href="<?= get_stylesheet_directory_uri() ?>/stylesheets/lt-ie9.css"/>
	<![endif]-->
	
</head>
<body <?php body_class(); ?>>

	<?php tpl('nav','skiplinks'); ?>

	

	<div class="off-canvas-wrap" data-offcanvas>
		<div class="inner-wrap">

			<main id="main-container" role="main">

		
				<header id="main-header">

					<?php tpl('block','topbar-header'); ?>

					<?php tpl('block','offcanvas-header'); ?>
					
				</header>

				<div id="main-content">
			