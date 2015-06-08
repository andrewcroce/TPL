<?php /**
 * This is the default template file for Wordpress.
 * We use it to display basic lists of posts, including search results.
 * In many cases, this will be overridden by a more specific template.
 * 
 **/

/**
 * Extract some useful pagination variables from get_paged_vars()
 * @var int $page_number	Current page number
 * @var int $start_number	The number of the first post on this page
 * @var int $end_number		The number of the last post on this page
 * @var int $total_number	The total number of posts found in the query
 */
extract( get_paged_vars( $wp_query ) ); ?>

<?php get_header(); ?>


<?php if( is_search() ) : ?>


	<?php tpl_wrapper_header_open(); ?>

		<header>

			<h1><?php echo sprintf(__('Search results for "%s"'), get_query_var('s')); ?></h1>
			
			<?php if( have_posts() ) : ?>
				<span class="meta"><?php echo sprintf(__('Showing %s - %s of %s results'), $start_number, $end_number, $total_number); ?></span>		
			<?php endif ?>
			
		</header>

	<?php tpl_wrapper_header_close(); ?>
	
	
<?php endif; ?>


<?php if( have_posts() ) : ?>


	<?php tpl_wrapper_content_open(); ?>

		<ol class="item-list page-<?php echo $page_number; ?>" start="<?php echo $start_number; ?>">

			<?php while( have_posts() ) : the_post(); ?>
				
				<li><?php tpl_item( new ACFPost($post) ); ?></li>
			
			<?php endwhile; ?>

		</ol>

		<?php tpl_nav_pagination( $wp_query ); ?>

	<?php tpl_wrapper_content_close(); ?>


<?php else: ?>


	<?php tpl_wrapper_content_open(); ?>

		<?php echo wpautop( sprintf(__('Sorry, your search for "%s" returned no results.'), get_query_var('s')) ); ?>

	<?php tpl_wrapper_content_close(); ?>


<?php endif; ?>

<?php get_footer(); ?>