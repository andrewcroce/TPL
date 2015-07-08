<?php 
/**
 * The home page template
 * 
 **/
get_header(); ?>

	<?php if( have_posts() ) : ?>
		
		<?php while( have_posts() ) : the_post(); 

			$page = new ACFPost( $post ); ?>

			<article class="home page" itemscope itemtype="http://schema.org/WebPage">

	
				<?php tpl_wrapper_header_open(); ?>

					<header>

						<h1 itemprop="headline" class="title"><?php echo $page->post_title; ?></h1>
						
					</header>

				<?php tpl_wrapper_header_close(); ?>



				<?php tpl_wrapper_content_open(); ?>


					<?php if( get_query_var('status',0) ) : ?>
						<div class="row">
							<div class="large-12-columns">
								<?php tpl_block_status( get_query_var('status') ) ?>
							</div>
						</div>
					<?php endif; ?>

					
					<div class="row">
						
						<div class="large-12 columns">
							<div itemprop="text"><?php echo $page->filterContent('post_content'); ?></div>
						</div>

					</div>

				<?php tpl_wrapper_content_close(); ?>

						
			</article>

		<?php endwhile; ?>

	<?php endif; ?>

<?php get_footer(); ?>