<?php 
/**
 * Default Post Content Template - tpl_content()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_content()
 *
 * 		@var ACFPost $post Post object wrapped in ACF object
 * }	
 * 
 */ 

extract( $params ); ?>

<article class="content default">
	
	<header>

		<h1><?php echo $post->post_title; ?></h1>

		<time datetime="<?php echo $post->post_date->format('Y-m-d H:i'); ?>">

			<?php echo $post->post_date->format('F j, Y'); ?>

		</time>
		
	</header>		

	<?php echo $post->filterContent('post_content'); ?>
			
</article>