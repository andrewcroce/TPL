<?php /**
 * This is the default template file for Wordpress.
 * We use it to display basic lists of posts, including search results.
 * In many cases, this will be overridden by a more specific template.
 * 
 **/

?>
<?php get_header(); ?>

<?php if( have_posts() ) : ?>

	<?php tpl('wrapper','12col-start'); ?>
		
		<?php // If this is a search, extract some useful pagination variables and display a search header
		if( is_search() ) : extract( get_paged_vars( $wp_query ) ); ?>

			<header>

				<h1><?php echo sprintf(__('Search results for "%s"'), get_query_var('s')); ?></h1>

				<p><?php echo sprintf(__('Showing %s - %s of %s results'), $start_number, $end_number, $total_number); ?></p>
				
			</header>
			
		<?php endif; ?>

		<ol class="item-list page-<?php echo $page_number; ?>" start="<?php echo $start_number; ?>">

			<?php while( have_posts() ) : the_post(); ?>
				
				<li><?php tpl_item( new ACFPost($post) ); ?></li>
			
			<?php endwhile; ?>

		</ol>

		<?php tpl_pagination( $wp_query ); ?>

	<?php tpl('wrapper','12col-end'); ?>

<?php endif; ?>

<?php get_footer(); ?>