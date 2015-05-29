<?php
/**
 * Default Comments Template - tpl_comments()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_content()
 *
 * 		@var ACFPost $post Post object wrapped in ACF object
 * 		@var array $comments Array of comments from get_comments()
 * 		@var string $user_identity User display name string, if someone is logged in
 * 		@var array $commenter {
 * 		    Commenter info submitted by user
 *
 * 			@var string $comment_author
 * 			@var string $comment_author_email
 * 			@var string $comment_author_url
 * 		}
 * 		@var bool $required Are name and email fields required?
 * 		@var string $aria_required Aria required field attribute, or nothing, depending if $required is true
 * }	
 * 
 */ 
extract( $params );

// If this is a private post, and a password hasn't been supplied, just return
if( post_password_required( $post->ID ) ) return; ?>

<section class="comments-section">

	<?php if( !empty( $comments ) ) : ?>
		
		<h3><?php echo __('Comments'); ?></h3>
		


		<?php /**
		 * ================
		 * The Comment List
		 * ================
		 */ ?>
		<ol class="item-list">
			
			<?php wp_list_comments( array(

				// ( Walker object ) Provide a custom Walker class object to use when rendering the comments. This is the primary method of customizing comment HTML.
				'walker' => null,

				// ( integer ) How deep (in comment replies) should the comments be fetched.
				'max_depth' => '',

				// ( string ) Can be either 'div', 'ol', or 'ul' (the default). Note that any containing tags that must be written explicitly. For instance:
				'style' => 'ol',

				// ( callback ) The name of a custom function to use to open and display each comment. 
				// Using this will make your custom function get called to display each comment, bypassing all internal WordPress functionality in this respect. 
				// Use to customize comments display for extreme changes to the HTML layout. 
				// Note that your callback must include the opening <div>, <ol>, or <ul> tag (corresponding with the style parameter), but not the closing tags. 
				// WordPress will supply the closing tag automatically, or you can use end-callback to override this default. 
				// The callback is separate from the end-callback to facilitate hierarchical comments. Use with caution.
				'callback' => 'tpl_comment',

				// ( callback ) The name of a custom function to use to close each comment. 
				// Using this will make your custom function get called to at the end of each comment, bypassing the WordPress default of using </div>, </ol>, or </li> based on the style parameter. 
				// Use to customize the ending tags for a comment. 
				// The callback is separate from the end-callback to facilitate hierarchical comments. Use with caution.
				'end-callback' => 'tpl_comment_end',

				// ( string ) The type of comment(s) to display. Can be 'all', 'comment', 'trackback', 'pingback', or 'pings'. 'pings' is both 'trackback' and 'ping back' together.
				'type' => 'all',

				// ( string ) Text to display in each comment as a reply link. (This isn't an argument of this function but it gets passed to the get_comment_reply_link function.)
				'reply_text' => __('Reply'),

				// ( integer ) The current page in the pagination to display.
				'page' => '',

				// 'per_page' 
				'per_page' => '',

				// ( integer ) Size that the avatar should be shown as, in pixels. http://gravatar.com/ supports sizes between 1 and 512. Use 0 to hide avatars.
				'avatar_size' => 40,

				// ( boolean ) Setting this to true will display the most recent comment first then going back in order.
				'reverse_top_level' => null,

				// ( boolean ) Setting this to true will display the children (reply level comments) with the most recent ones first, then going back in order.
				'reverse_children' => '',

				// ( boolean ) This can be set to 'html5' or 'xhtml' - it defaults to your theme's current_theme_supports( 'html5' ) setting.
				'format' => 'html5',

				// ( boolean ) Whether you want to use a short ping.
				'short_ping' => false,

				// ( boolean ) Whether to echo the list or just return it.
        		'echo' => true

			), $comments ); ?>

		</ol>

	<?php endif; ?>

	

	<?php // If comments are closed, but there are comments, leave a note to that effect
		if( !comments_open( $post->ID ) && get_comments_number( $post->ID ) && post_type_supports( get_post_type( $post->ID ), 'comments' ) ) : ?>
		<p class="no-comments"><?php echo __( 'Comments are closed.'); ?></p>
	<?php endif; ?>



	<?php /**
	 * ================
	 * The Comment Form
	 * ================
	 */ ?>

	<?php comment_form( array(

		// (string) (optional) value of the id attribute of form element (<form> tag).
		'id_form' => 'comment-form',

		// (string) (optional) value of the id attribute of submit button.
		'id_submit' => 'comment-submit',

		// (string) (optional) value of the class attribute of submit button.
		'class_submit' => 'submit button',

		// (string) (optional) The title of comment form (when not replying to a comment, see comment_form_title).
		'title_reply' => __('Leave a Comment'),

		// (string) (optional) The title of comment form (when replying to a comment, see comment_form_title).
		'title_reply_to' => __('Reply to %s'),

		// (string) (optional) link label to cancel reply.
		'cancel_reply_link' => __('Cancel Comment'),

		// (string) (optional) the name of submit button.
		'label_submit' => __('Post Your Comment'),

		// (string) (optional) The textarea and the label of comment body.
		'comment_field' =>  '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) .
							'</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true">' .
    						'</textarea></p>',

    	// (string) (optional)
    	'must_log_in' => 	'<p class="must-log-in">' .
    						sprintf(__( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )) . 
    						'</p>',

    	// (string) (optional)
    	'logged_in_as' => 	'<p class="logged-in-as">' .
    						sprintf(__( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ),
								admin_url( 'profile.php' ),
      							$user_identity,
      							wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )) . 
    						'</p>',

    	// (string) (optional) Text or HTML to be displayed before the set of comment form fields if the user is not logged in.
    	'comment_notes_before' => 	'<p class="comment-notes">' .
    								__( 'Your email address will not be published.' ) .
    								'</p>',

    	// (string) (optional) Text or HTML to be displayed after the set of comment fields (and before the submit button)
    	'comment_notes_after' => 	'<p class="form-allowed-tags">' .
    								sprintf(__( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ),
										' <code>' . allowed_tags() . '</code>') . 
    								'</p>',

    	// (array) (optional) Input fields: 'author', 'email', 'url'.
    	'fields' => apply_filters( 'comment_form_default_fields', array(
    		'author' =>	'<label for="author">' . __( 'Name', 'domainreference' ) . ( $required ? '<span class="required">*</span>' : '' ) . '</label> ' .
    					'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
    					'" size="30"' . $aria_required . ' />',
  			'email' =>	'<label for="email">' . __( 'Email', 'domainreference' ) . ( $required ? '<span class="required">*</span>' : '' ) . '</label> ' .
    					'<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
    					'" size="30"' . $aria_required . ' />',
  			'url' =>	'<label for="url">' . __( 'Website', 'domainreference' ) . '</label>' .
    					'<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
    					'" size="30" />',
    	)),

	), $post->ID ); ?>

</section>