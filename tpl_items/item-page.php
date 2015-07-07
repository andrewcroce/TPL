<?php 
/**
 * Page Content Template - tpl_item_page()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_item()
 *
 * 		@var ACFPost $page 			Post object wrapped in ACF object
 * }	
 * 
 */ 

extract( $params );

// Buffer output so we can cache the readmore link template
ob_start();

// Include the link template
tpl_link_read_more( get_permalink( $page->ID ) );

// Assign it to the $permalink variable
$permalink = ob_get_clean(); ?>


<article class="item page">
	
	<header>

		<h2 class="title"><?php echo $page->post_title; ?></h2>
		
	</header>		

	<div class="excerpt"><?php // Truncate the content, appending the permalink
		echo truncate( $page->post_content, array(
			'after' => ' ' . $permalink
		)); ?>
	</div>
			
</article>