<?php 
/**
 * Page Content Template - tpl_content_page()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_content_page()
 *
 * 		@var ACFPost $page Post object wrapped in ACF object
 * }	
 * 
 */ 

extract( $params ); ?>

<article class="content page">
			
	<h1><?php echo $page->post_title; ?></h1>

	<?php echo $page->filterContent('post_content'); ?>
			
</article>