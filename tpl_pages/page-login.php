<?php 
/**
 * The login page template
 * 
 **/
get_header(); ?>

	<?php if( have_posts() ) : ?>
		
		<?php while( have_posts() ) : the_post(); 

			$page = new ACFPost($post);
			$redirect_page = get_page_by_path( get_query_var('redirect','profile') ); ?>

			<article class="login page" itemscope itemtype="http://schema.org/WebPage">


				<?php tpl_wrapper_header_open(); ?>

					<header>

						<h1 itemprop="headline" class="title"><?php echo $page->post_title; ?></h1>
						
						<?php echo edit_post_link( __('Edit post'), $before = '<span class="meta">', $after = '</span>', $page->ID ); ?>
						
					</header>

				<?php tpl_wrapper_header_close(); ?>


				<?php tpl_wrapper_content_open(); ?>


					<?php if( get_query_var( 'restricted', 0 ) ) {

						tpl_block_alert( sprintf(__('You must be logged in to view the %s page','theme'), $redirect_page->post_title ), 'alert' );

					} ?>

		
					<div itemprop="text"><?php echo $page->filterContent('post_content'); ?></div>

				<?php tpl_wrapper_content_close(); ?>
			

				
				<?php tpl_wrapper_secondary_open(); ?>

					<?php tpl_form_login( 'login-page', $redirect_page->post_name ); ?>

				<?php tpl_wrapper_secondary_close(); ?>


			</article>


		<?php endwhile; ?>

	<?php endif; ?>

<?php get_footer(); ?>