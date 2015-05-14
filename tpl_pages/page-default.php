<?php get_header(); ?>

<?php tpl('wrapper','content-start'); ?>

	<?php if( have_posts() ) : ?>
		
		<?php while( have_posts() ) : the_post(); ?>

			<?php tpl('content','page', new ACFPost($post)); ?>

		<?php endwhile; ?>

	<?php endif; ?>

<?php tpl('wrapper','content-end'); ?>

<?php get_footer(); ?>
