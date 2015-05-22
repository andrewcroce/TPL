<article class="content-default">
	
	<header>

		<h1><?php echo $params['content']->post_title; ?></h1>

		<time datetime="<?php echo $params['content']->post_date->format('Y-m-d H:i'); ?>">

			<?php echo $params['content']->post_date->format('F j, Y'); ?>

		</time>
		
	</header>		

	<?php echo $params['content']->filterContent('post_content'); ?>
			
</article>