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

<article class="content page" itemscope itemtype="http://schema.org/WebPage">

	
	<?php tpl_wrapper_header_open(); ?>

		<header>

			<h1 itemprop="headline" class="title"><?php echo $page->post_title; ?></h1>
			
			<meta itemprop="datePublished" content="<?php echo $page->post_date->format('c'); ?>">

			<span itemprop="author" itemscope itemtype="http://schema.org/Person">
				<meta itemprop="name" content="<?php echo get_the_author_meta( 'display_name', $page->post_author ); ?>">
			</span>
			
			<?php echo edit_post_link( __('Edit post'), $before = '<span class="meta">', $after = '</span>', $page->ID ); ?>
			
		</header>

	<?php tpl_wrapper_header_close(); ?>



	<?php tpl_wrapper_content_open(); ?>

		<div itemprop="text"><?php echo $page->filterContent('post_content'); ?></div>

	<?php tpl_wrapper_content_close(); ?>

			
</article>