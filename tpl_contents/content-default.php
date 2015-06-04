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

<article class="content default" itemscope itemtype="http://schema.org/Article">
	
	<header>

		<h1 itemprop="headline" class="title"><?php echo $post->post_title; ?></h1>
		
		<time itemprop="datePublished" datetime="<?php echo $post->post_date->format('c'); ?>" class="publish-date meta">
			<?php echo $post->post_date->format('F j, Y'); ?>
		</time>

		<span itemprop="author" itemscope itemtype="http://schema.org/Person" class="author meta">
			<span itemprop="name"><?php echo get_the_author_meta( 'display_name', $post->post_author ); ?></span>
		</span>
		
		<?php echo edit_post_link( __('Edit post'), $before = '<span class="meta">', $after = '</span>', $post->ID ); ?>
		
	</header>		

	<div itemprop="articleBody"><?php echo $post->filterContent('post_content'); ?></div>

	<?php if( comments_open( $post->ID ) || get_comments_number( $post->ID ) ) : ?>
		<?php tpl_block_comments( $post ); ?>
	<?php endif; ?>

	<?php // Additional structured meta data ?>
	<meta itemprop="url" content="<?php echo get_permalink( $post->ID ); ?>">
			
</article>