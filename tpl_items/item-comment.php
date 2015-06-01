<?php
/**
 * Single Comment Template
 * @see wp/wp-includes/comment-template.php: class Walker_Comment for reference to original markup
 *
 * @var array $params {
 *      Parameters passed into the template from tpl_item()
 *
 * 		@var  object $comment {
 * 		      WP Comment object
 * 		      
 * 		      @var int $comment_ID
 * 		      @var int $comment_post_ID
 * 		      @var string $comment_author
 * 		      @var string $comment_author_email
 * 		      @var string $comment_author_IP
 * 		      @var string $comment_date
 * 		      @var string $comment_date_gmt
 * 		      @var string $comment_content
 * 		      @var int $comment_karma
 * 		      @var bool $comment_approved
 * 		      @var string $comment_agent
 * 		      @var string $comment_type
 * 		      @var int $comment_parent
 * 		      @var int $user_id
 * 		}
 * 		@var  array $args    	Arguments from wp_list_comments()
 * 		@var  int $depth   		Comment depth, i.e. is it a top-level comment, a reply, or a reply to a reply, etc.
 * }	
 */
extract( $params ); ?>

<li>
	<article itemprop="comment" itemscope itemtype="http://schema.org/Comment" class="comment level-<?php echo $depth; ?>" id="comment-<?php echo $comment->comment_ID; ?>">

		<div class="row">
			
			<div class="large-4 medium-4 columns">

				<footer>

					<div class="row">

					<?php if( $args['avatar_size'] !== 0 ) : ?>
						<div class="small-2 columns avatar-column">
							<?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
						</div>
					<?php endif; ?>

					<?php if( $args['avatar_size'] !== 0 ) : ?>
						<div class="small-10 columns">
					<?php else : ?>
						<div class="small-12 columns">
					<?php endif; ?>

							<span itemscope itemprop="author" itemtype="http://schema.org/Person" class="comment-author">
								<span itemprop="name"><?php echo get_comment_author(); ?></span>
							</span>

							<time itemprop="dateCreated" datetime="<?php comment_time( 'c' ); ?>" class="comment-publish-date meta">
								<?php printf( _x( '%1$s at %2$s', '1: date, 2: time' ), get_comment_date(), get_comment_time() ); ?>
							</time>

						</div>
					
					</div>

				</footer>

			</div>

			<div itemprop="text" class="large-8 medium-8 columns">
				<?php echo wpautop( $comment->comment_content . ' ' .
						get_comment_reply_link( array_merge( $args, array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth']
						) ) )
					); ?>

			</div>

		</div>
		
	</article>