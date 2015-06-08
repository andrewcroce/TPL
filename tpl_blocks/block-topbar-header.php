<?php 
/**
 * Implementation of Foundation's Topbar component
 * 
 */ ?>

<div class="tab-bar">
			
	<div role="banner" class="title-area">
		
		<div class="name">
			<span id="site-title"><a href="<?php echo home_url(); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span>
		</div>

		<a class="right-off-canvas-toggle" href="#" >Menu</a>


	</div>

</div>


<div class="right-off-canvas-menu">
		
	<?php get_search_form(); ?>

	<?php tpl_nav( 'main_menu', 'main-navigation', 'off-canvas-list' ); ?>

</div>