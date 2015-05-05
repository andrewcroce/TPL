<?php 

// This is the only template file required for a WP theme to function.
// In most cases, this template will be overridden by a more specific template.
// Refer to http://codex.wordpress.org/images/1/18/Template_Hierarchy.png

?>
<?php get_header(); ?>

<?php if( have_posts() ) : ?>

	<?php while( have_posts() ) : the_post(); ?>
		

		<?php content_wrapper_start(); ?>
		
		<article>
			
			<h1><?php the_title(); ?></h1>
			
			<?php the_content(); ?>
			
		</article>

		<?php content_wrapper_end(); ?>


	<?php endwhile; ?>

<?php endif; ?>

<?php get_footer(); ?>