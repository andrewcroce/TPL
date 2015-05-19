<?php 

// Buffer output so we can cache the readmore link template
ob_start();

// Include the link template
tpl( 'link', 'read-more', get_permalink( $item->ID ));

// Assign it to the $permalink variable
$permalink = ob_get_clean(); ?>


<article class="item-default">
	
	<header>

		<h1><?php echo $item->post_title; ?></h1>

		<time datetime="<?php echo $item->post_date->format('Y-m-d H:i'); ?>">

			<?php echo $item->post_date->format('F j, Y'); ?>

		</time>
		
	</header>		

	<?php // Truncate the content, appending the permalink
	echo truncate( $item->filterContent('post_content'), array(
		'after' => $permalink
	)); ?>
			
</article>