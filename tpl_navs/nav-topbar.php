<?php
/**
 * Default navigation menu template - tpl_nav()
 *
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_nav()
 *
 * 		@var  string $menu_location A registered WP menu location. Default: 'main_menu'
 *      @var  string $id            ID to add to the <nav> element. Default: value of $menu_location
 * }	 
 */
extract( $params ); ?>

<nav id="<?php echo $id; ?>" role="navigation">
	<?php wp_nav_menu( array( 
		'theme_location' => $menu_location,
		'items_wrap' => '<ul id="%1$s-topbar" class="right %2$s">%3$s</ul>',
		'container' => false,
		'walker' => new Topbar_Walker_Nav_Menu()
	));?>
</nav>