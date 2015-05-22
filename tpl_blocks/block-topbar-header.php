<div class="top-bar" data-topbar>
			
	<div role="banner" class="title-area">
		
		<div class="name">
			<span id="site-title"><a href="<?php echo home_url(); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span>
		</div>

		<span class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></span>

	</div>

	<section class="top-bar-section">
		<?php tpl('nav','main', array( 'classes' => 'right' )); ?>
	</section>

</div>