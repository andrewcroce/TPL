<?php 
/**
 * Default Post Content Template - tpl_item()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_item()
 *
 * 		@var ACFPost $post 			Post object wrapped in ACF object
 * 		@var boolean $include_date	Show the date?
 * }	
 * 
 */ 

extract( $params );

// Buffer output so we can cache the readmore link template
ob_start();

// Include the link template
tpl_readmore( get_permalink( $post->ID ) );

// Assign it to the $permalink variable
$permalink = ob_get_clean(); ?>


<article class="item default">
	
	<header>

		<h1><?php echo $post->post_title; ?></h1>
		
		<?php if( $include_date ) : ?>
		
			<time datetime="<?php echo $post->post_date->format('Y-m-d H:i'); ?>">

				<?php echo $post->post_date->format('F j, Y'); ?>

			</time>

		<?php endif ?>
		
	</header>		

	<?php // Truncate the content, appending the permalink
	echo truncate( $post->post_content, array(
		'after' => ' ' . $permalink
	)); ?>
			
</article>