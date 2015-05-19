<?php /* Template Name: Index 
*
* This page template is meant as a general purpose replacement for WP's archive template.
* By assigning it to a page, you can select a post type for which to list a paginated index.
* It gives you the flexibility of a standard page, with the pagination features of an archive.
* 
*/ ?>

<?php get_header(); ?>

<?php tpl('wrapper','12col-start'); ?>

	<?php if( have_posts() ) : ?>
		
		<?php while( have_posts() ) : the_post(); 

			// The page
			$page = new ACFPost( $post );

			// Create a new sub-query for our selected post type
			$index = new WP_Query(array(
				'post_type' => $page->index_post_type,
				'paged' => $paged
			)); 

			// Extract any relevant vars from our $paged var
			extract( get_paged_vars( $paged ) ) ?>

			<h1><?php echo $page->post_title; ?></h1>

			<?php if( $page_number == 1 ) echo $page->filterContent('post_content'); // Presumably we should only display the content on the first page ?>

			<?php if( $index->have_posts() ) : // If our subquery has posts... ?>

				<ol class="posts-list page-<?php echo $page_number; ?>" start="<?php echo $start_number; ?>">

					<?php while( $index->have_posts() ) : $index->the_post(); // Sub loop... ?>
						
						<li><?php tpl('item','default', new ACFPost($post)); ?></li>
					
					<?php endwhile; wp_reset_postdata(); // Reset our loop back to the original page post ?>

				</ol>

				<?php tpl('nav','pagination',$index); // Pagination ?>

			<?php endif; ?>

		<?php endwhile; ?>

	<?php endif; ?>

<?php tpl('wrapper','12col-end'); ?>

<?php get_footer(); ?>