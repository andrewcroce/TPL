<nav id="main-menu" role="navigation">
	<?php wp_nav_menu( array( 
		'theme_location' => 'main_menu',
		'items_wrap' => '<ul id="%1$s" class="inline-list %2$s">%3$s</ul>'
	));?>
</nav>