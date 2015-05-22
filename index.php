<?php 

// This is the only template file required for a WP theme to function.
// In most cases, this template will be overridden by a more specific template.
// Refer to http://codex.wordpress.org/images/1/18/Template_Hierarchy.png

?>
<?php get_header(); ?>

<?php if( have_posts() ) : ?>

	<?php tpl('wrapper','12col-start'); ?>

		<?php extract( get_paged_vars( $paged ) ); ?>

		<ol class="item-list page-<?php echo $page_number; ?>" start="<?php echo $start_number; ?>">

			<?php while( have_posts() ) : the_post(); ?>
				
				<li><?php tpl('item','default', array( 'item' => new ACFPost($post) )); ?></li>
			
			<?php endwhile; ?>

		</ol>

		<?php tpl('nav','pagination',$wp_query); ?>

	<?php tpl('wrapper','12col-end'); ?>

<?php endif; ?>

<?php get_footer(); ?>