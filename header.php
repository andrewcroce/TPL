<!DOCTYPE html>
<!--[if lte IE 8]>
<html class="lt-ie9" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php
		/*
		 * Print the <title> tag based on what is being viewed.
		 */
		global $page, $paged;

		wp_title( '|', true, 'right' );

		// Add the blog name.
		bloginfo( 'name' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			echo " | $site_description";

		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			echo ' | ' . sprintf( __( 'Page %s', 'wp-starter' ), max( $paged, $page ) );

		?></title>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
	
	<!--[if lte IE 8]>
	<link rel="stylesheet" href="<?= get_stylesheet_directory_uri() ?>/stylesheets/lt-ie9.css"/>
	<![endif]-->
	
</head>
<body <?php body_class(); ?>>
	
	<div id="skip-links" class="screenreader">
		<a href="#main-navigation"><?= __('Go to navigation','theme') ?></a>
		<a href="#main-content"><?= __('Go to content','theme') ?></a>
	</div>
	
	<main id="main-container" role="main">
		
		<header id="main-header">

			<?php tpl('wrapper','12col-start'); ?>
			
				<div id="site-branding" role="banner">
					<span id="site-title"><a href="<?php echo home_url(); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span>
					<span id="site-description"><?php bloginfo( 'description' ); ?></span>
				</div>
				
				<?php tpl('nav','main'); ?>

			<?php tpl('wrapper','12col-end'); ?>
			
		</header>
		<div id="main-content">
			