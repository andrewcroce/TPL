<?php 
/**
 * The default template for single posts
 * 
 **/
get_header(); ?>

	<?php if( have_posts() ) : ?>
		
		<?php while( have_posts() ) : the_post(); ?>

			<?php tpl_content( new ACFPost($post) ); ?>

		<?php endwhile; ?>

	<?php endif; ?>

<?php get_footer(); ?>