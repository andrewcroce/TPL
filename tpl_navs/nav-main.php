<?php 
/*
 * Main Navigation template
 * @var string $nav Class names to add to the menu <ul>
 *
 */ ?>

<nav id="main-navigation" role="navigation">
	<?php wp_nav_menu( array( 
		'theme_location' => 'main_menu',
		'items_wrap' => '<ul id="%1$s" class="'.$nav.' %2$s">%3$s</ul>'
	));?>
</nav>