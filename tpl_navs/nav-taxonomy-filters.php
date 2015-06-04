<?php 
/**
 * Taxonomy Filters Template - tpl_nav_taxonomy_filters()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_nav_taxonomy_filters()
 *
 * 		@var  array 			$taxonomies 	Array of taxonomy objects available for filtering. 
 *                           					Each taxonomy object may be supplemented by a "filter_style" property, either "single" or "multi".
 *                                				We will default to "single" if this property isn't present
 *      @var  object/string 	$post_type  	Post type object or post type name
 *      @var  WP_Query 			$index 			WP Query object these filters apply to	
 * }
 * @see js/_taxonomy_filters.js for corresponding JS functionality
 * 
 */ 

extract( $params ); ?>

<?php 

// Loop through each enabled taxonomy
foreach( $taxonomies as $taxonomy ) : 

	// Get the current query vars for this taxonomy
	$current_query = isset($index->tax_query->queried_terms[$taxonomy->name])
						? $index->tax_query->queried_terms[$taxonomy->name]['terms']
						: array();

	$has_term_filters = !empty( $current_query ); ?>
	

	<?php // The dropdown button for this taxonomy ?>
	<button href="#" data-dropdown="<?php echo $taxonomy->query_var; ?>-filter" aria-controls="<?php echo $taxonomy->query_var; ?>-filter" aria-expanded="false" class="tiny secondary dropdown button">
		
		<?php echo sprintf(__('Filter %s by %s'), $post_type->labels->name, $taxonomy->labels->singular_name ); ?>
	
	</button>
	
	
	<?php // The dropdown button's corresponding list, this will become the dropdown itself ?>
	<ul id="<?php echo $taxonomy->query_var; ?>-filter" data-taxonomy="<?php echo $taxonomy->query_var; ?>" data-dropdown-content class="f-dropdown taxonomy-filter-list" aria-hidden="true">
		
		<?php // Loop through each term on this taxonomy
		foreach( get_terms( $taxonomy->name, array( 'hide_empty' => true ) ) as $term ) : 

			// Prepare a boolean variable to control whether the item is currenly checked
			$checked = in_array( $term->slug, $current_query ); ?>

			<li>
				<?php // A multi-select picker (checkboxes)
				if( $taxonomy->filter_style == 'multi' ) : ?>

					<input 
						class="taxonomy-filter-input" 
						type="checkbox" 
						id="filter-checkbox-<?php echo $taxonomy->query_var . '-' . $term->slug; ?>" 
						data-taxonomy="<?php echo $taxonomy->query_var; ?>" 
						data-term="<?php echo $term->slug; ?>" 
						<?php if( $checked ){ echo 'checked'; }?>>
					<label for="filter-checkbox-<?php echo $taxonomy->query_var . '-' . $term->slug; ?>"><?php echo $term->name; ?></label>
					
				<?php // A single-select picker, the default (radio buttons)
				else : ?>
					
					<input 
						class="taxonomy-filter-input" 
						type="radio" 
						name="filter-radio-<?php echo $taxonomy->query_var; ?>" 
						id="filter-radio-<?php echo $taxonomy->query_var . '-' . $term->slug; ?>" 
						data-taxonomy="<?php echo $taxonomy->query_var; ?>" 
						data-term="<?php echo $term->slug; ?>" 
						<?php if( $checked ){ echo 'checked'; }?>>
					<label for="filter-radio-<?php echo $taxonomy->query_var . '-' . $term->slug; ?>"><?php echo $term->name; ?></label>

				<?php endif; ?>
				
			</li>
		<?php endforeach; ?>
	</ul>
	
<?php endforeach; ?>

<?php // Button to apply new filters, hidden by default. It will show when any new filters are checked. ?>
<button id="apply-taxonomy-filters-button" class="tiny secondary button" style="display:none;"><?php echo __('Apply Filters'); ?></button>

<?php // If there are current filters, show a button to clear them
if( $has_term_filters ) : ?>

	<button id="clear-taxonomy-filters-button" class="tiny secondary button"><?php echo __('Clear Filters'); ?></button>

<?php endif; ?>