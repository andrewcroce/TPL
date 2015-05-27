<?php 
/**
 * The default template for single pages
 * 
 **/
get_header(); ?>

<?php tpl('wrapper','12col-start'); ?>

	<?php if( have_posts() ) : ?>
		
		<?php while( have_posts() ) : the_post(); ?>
			
			<?php tpl_content_page( new ACFPost($post) ); ?>

		<?php endwhile; ?>

	<?php endif; ?>

<?php tpl('wrapper','12col-end'); ?>

<?php get_footer(); ?>
