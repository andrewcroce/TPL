<?php 
/**
 * Implementation of Foundation's Topbar component
 * 
 */ ?>

<div class="top-bar" data-topbar>
			
	<div role="banner" class="title-area">
		
		<div class="name">
			<span id="site-title"><a href="<?php echo home_url(); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span>
		</div>

		<span class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></span>

	</div>

	<section class="top-bar-section">
		<div class="right has-form">
			<?php get_search_form(); ?>
		</div>
	</section>

	<section class="top-bar-section">
		<?php tpl_nav( 'main_menu', 'main-navigation', 'right' ); ?>
	</section>
	

</div>