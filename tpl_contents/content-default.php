<article class="content-default">
			
	<h1><?php echo $content->post_title; ?></h1>

	<time><?php echo $content->post_date->format('F j, Y'); ?></time>

	<?php echo $content->filterContent('post_content'); ?>
			
</article>