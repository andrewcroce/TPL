<?php
/**
 * Page tree template - tpl_nav_page_tree()
 *
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_nav_page_tree()
 *
 * 		@var  array $tree Hierarchical array representing the page's family tree
 * }	 
 */
extract( $params ); ?>

<div class="hide">
	<nav class="page-tree move-me" data-break="0" data-move-name="sidebar">
		<?php echo nested_list( $tree ); ?>
	</nav>
</div>