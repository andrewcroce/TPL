<?php get_header(); ?>

<?php tpl_wrapper('content','start'); ?>

<?php if( have_posts() ) : ?>
	
	<?php while( have_posts() ) : the_post(); ?>

		<article class="default page">

			<?php dump( $post ); ?>
		
		</article>

	<?php endwhile; ?>

<?php endif; ?>

<?php tpl_wrapper('content','end'); ?>

<?php get_footer(); ?>
