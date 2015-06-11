<?php 
/**
 * The default template for single pages
 * 
 **/
get_header(); ?>

	<?php if( have_posts() ) : ?>
		
		<?php while( have_posts() ) : the_post(); 

			$page = new ACFPost($post); ?>
			
			<?php tpl_content_page( $page ); ?>
			
			<?php if( page_has_family_tree() ) tpl_nav_page_tree( $page ); ?>

		<?php endwhile; ?>

	<?php endif; ?>

<?php get_footer(); ?>
