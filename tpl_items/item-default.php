<?php 
/**
 * Default Post Content Template - tpl_item()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_item()
 *
 * 		@var ACFPost $post 			Post object wrapped in ACF object
 * }	
 * 
 */ 

extract( $params );

// Buffer output so we can cache the readmore link template
ob_start();

// Include the link template
tpl_link_read_more( get_permalink( $post->ID ) );

// Assign it to the $permalink variable
$permalink = ob_get_clean(); ?>


<article class="item default" itemscope itemtype="http://schema.org/Article">
	
	<header>

		<h2 itemprop="headline" class="title"><?php echo $post->post_title; ?></h2>

		<span itemprop="author" itemscope itemtype="http://schema.org/Person" class="author meta">
			<span itemprop="name"><?php echo get_the_author_meta( 'display_name', $post->post_author ); ?></span>
		</span>
		
		<time itemprop="datePublished" datetime="<?php echo $post->post_date->format('c'); ?>" class="publish-date meta">
			<?php echo $post->post_date->format('F j, Y'); ?>
		</time>
		
	</header>		

	<div class="excerpt"><?php // Truncate the content, appending the permalink
		echo truncate( $post->post_content, array(
			'after' => ' ' . $permalink
		)); ?>
	</div>
	
	<?php // Additional structured meta data ?>
	<meta itemprop="articleBody" content="<?php echo htmlspecialchars( $post->filterContent('post_content') ); ?>">
	<meta itemprop="url" content="<?php echo get_permalink( $post->ID ); ?>">
			
</article>