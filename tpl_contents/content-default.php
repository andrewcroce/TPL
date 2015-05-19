<article class="content-default">
	
	<header>

		<h1><?php echo $content->post_title; ?></h1>

		<time datetime="<?php echo $content->post_date->format('Y-m-d H:i'); ?>">

			<?php echo $content->post_date->format('F j, Y'); ?>

		</time>
		
	</header>		

	<?php echo $content->filterContent('post_content'); ?>
			
</article>