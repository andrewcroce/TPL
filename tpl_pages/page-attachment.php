<?php
/**
 * The default template for single pages
 *
 **/
get_header(); ?>

	<?php if( have_posts() ) : ?>

		<?php while( have_posts() ) : the_post();

      $page = new ACFPost($post); ?>

      <?php tpl_content_attachment( $page ); ?>

		<?php endwhile; ?>

	<?php endif; ?>

<?php get_footer(); ?>
