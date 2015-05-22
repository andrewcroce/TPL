<?php

// Buffer output so we can cache the readmore link template
ob_start();

// Include the link template
tpl( 'link', 'read-more', array( 'url' => get_permalink( $params['item']->ID ) ));

// Assign it to the $permalink variable
$permalink = ob_get_clean(); ?>


<article class="item default">
	
	<header>

		<h1><?php echo $params['item']->post_title; ?></h1>

		<time datetime="<?php echo $params['item']->post_date->format('Y-m-d H:i'); ?>">

			<?php echo $params['item']->post_date->format('F j, Y'); ?>

		</time>
		
	</header>		

	<?php // Truncate the content, appending the permalink
	echo truncate( $params['item']->post_content, array(
		'after' => ' ' . $permalink
	)); ?>
			
</article>