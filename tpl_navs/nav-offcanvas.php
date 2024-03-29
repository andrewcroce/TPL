<?php
/**
 * Offcanvas navigation menu template - tpl_nav_offcanvas()
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
		'items_wrap' => '<ul id="%1$s-offcanvas" class="off-canvas-list %2$s">%3$s</ul>',
		'container' => false,
		'walker' => new Offcanvas_Walker_Nav_Menu()
	));?>

</nav>