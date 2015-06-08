<?php 
/**
 * Implementation of Foundation's Topbar component for Offcanvas
 * 
 */ ?>

<div class="tab-bar hide-for-large-up" aria-hidden="true">
				
	<section class="left tab-bar-section">
		<span class="site-title"><a href="<?php echo home_url(); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span>
	</section>

	<section class="right-small">
    	<a class="right-off-canvas-toggle menu-icon" ><span><span class="screenreader">Menu</span></span></a>
    </section>

</div>


<div class="right-off-canvas-menu">
	
	<?php get_search_form(); ?>

	<?php tpl_nav_offcanvas( 'main_menu', 'main-navigation' ); ?>

</div>