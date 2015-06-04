<?php
/**
 * Block to display a query's currently applied taxonomy filters
 *
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_content()
 *
 * 		@var  WP_Query $query A WP Query object
 * }
 */
extract( $params ); ?>

<?php if( !empty( $query->tax_query->queried_terms ) ) : ?>

	<section class="current-terms">

	<?php foreach( $query->tax_query->queried_terms as $tax_name => $tax_query ) : 

		$taxonomy = get_taxonomy( $tax_name ); ?>

		<span class="meta-label"><?php echo sprintf(__('Current %s'),$taxonomy->label) ?></span>

		<?php foreach( $tax_query['terms'] as $term_slug ) : 

			$term = get_term_by( 'slug', $term_slug, $tax_name ); ?>

			<span class="round secondary label"><?php echo $term->name; ?></span>

		<?php endforeach; ?>
		
	<?php endforeach; ?>

	</section>
	
<?php endif; ?>