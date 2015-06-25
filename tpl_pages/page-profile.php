<?php 
/**
 * The member profile template
 * 
 **/
get_header(); ?>

	<?php if( have_posts() ) : ?>
		
		<?php while( have_posts() ) : the_post(); 

			$page = new ACFPost($post);
			$user = get_userdata( get_current_user_id() ); ?>
			
			<article class="profile page" itemscope itemtype="http://schema.org/WebPage">


				<?php tpl_wrapper_header_open(); ?>

					<header>

						<h1 itemprop="headline" class="title"><?php echo $page->post_title; ?></h1>

						<?php if( current_user_can( 'manage_options' ) ) : ?>
							
							<span class="meta"><?php echo $user->display_name; ?></span>
							<span class="meta"><a href="<?php echo admin_url(); ?>"><?php echo __('Wordpress Admin','theme'); ?></a></span>
							<?php echo edit_post_link( __('Edit post'), $before = '<span class="meta">', $after = '</span>', $page->ID ); ?>
							
						<?php endif; ?>
												
					</header>

				<?php tpl_wrapper_header_close(); ?>



				<?php tpl_wrapper_content_open(); ?>
		
					<div itemprop="text"><?php echo $page->filterContent('post_content'); ?></div>

				<?php tpl_wrapper_content_close(); ?>
			

				
				<?php tpl_wrapper_secondary_open(); ?>

					<?php tpl_form_profile( $user ); ?>

				<?php tpl_wrapper_secondary_close(); ?>


			</article>

			<?php tpl_form_profile(); ?>

		<?php endwhile; ?>

	<?php endif; ?>

<?php get_footer(); ?>