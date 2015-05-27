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
 *      @var  string $class         Classes to add to the <ul> element. Default: ''
 * }	 
 */
extract( $params ); ?>

<nav id="<?php echo $id; ?>" role="navigation">
	<?php wp_nav_menu( array( 
		'theme_location' => $menu_location,
		'items_wrap' => '<ul id="%1$s" class="'.$classes.' %2$s">%3$s</ul>'
	));?>
</nav>