<?php /* Template Name: Index 
*
* This page template is meant as a general purpose replacement for WP's archive template.
* By assigning it to a page, you can select a post type for which to list a paginated index.
* It gives you the flexibility of a standard page, with the pagination features of an archive.
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

	<?php if( have_posts() ) : ?>
		
		<?php while( have_posts() ) : the_post(); 

			/**
			 * The page
			 */
			$page = new ACFPost( $post );

			/**
			 * Extract variables for the index
			 * @var WP_Query $index 	Query object for our post type index
			 * @var object $post_type 	Information about our indexed post type
			 * @var array $taxonomies 	List of taxonomy objects that we can filter by
			 */
			extract( get_index_vars( $page, $paged ) ); ?>

			<?php tpl_wrapper_header_start() ?>
				<h1><?php echo $page->post_title; ?></h1>
			<?php tpl_wrapper_header_end() ?>


			<?php tpl_block_current_taxonomy_filters( $index ); ?>


			<?php if( $page_number == 1 ) echo $page->filterContent('post_content'); // Presumably we should only display the content on the first page ?>
						
			
			<?php if( !empty( $taxonomies ) ) : ?>
				
				<?php tpl_nav_taxonomy_filters( $taxonomies, $post_type, $index ); ?>
					
			<?php endif; ?>


			<?php if( $index->have_posts() ) : // If our subquery has posts... ?>

				<ol class="item-list page-<?php echo $page_number; ?>" start="<?php echo $start_number; ?>">

					<?php while( $index->have_posts() ) : $index->the_post(); // Sub loop... ?>
						
						<li><?php

							// If theres an item template function for this post type
							// i.e tpl_item_{post_type}()
							if( function_exists( 'tpl_item_' . $post->post_type ) ) {

								// Call that template function
								call_user_func( 'tpl_item_' . $post->post_type, new ACFPost($post) );
							
							} else {
								
								// Otherwise use the default
								tpl_item( new ACFPost($post) );
							
							} ?>

						</li>
					
					<?php endwhile; wp_reset_postdata(); // Reset our loop back to the original page post ?>

				</ol>

				<?php tpl_nav_pagination( $index ); ?>

			<?php else : ?>

				<?php echo wpautop( sprintf(__('Sorry there are no matching %s, please try changing your selected filters.'), strtolower( $post_type->label )) ); ?>

			<?php endif; ?>

		<?php endwhile; ?>

	<?php endif; ?>

<?php get_footer(); ?>