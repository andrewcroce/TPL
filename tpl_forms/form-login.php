<?php 
/**
 * Login form template - tpl_form_login()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_form_login() 
 *      @var string 	$form_id
 *      @var string 	$redirect_url
 * }	
 * 
 */ 
extract( $params ); ?>

<div class="row">
	<div class="large-8 large-centered medium-10 medium-centered small-12 columns">

		<?php if( ! is_user_logged_in() ) :

			wp_login_form(array(

				// (boolean) (optional) Display the results
				'echo' => 1,

				// (optional) URL to redirect to. Must be absolute (as in, http://example.com/mypage/)
				'redirect' => $redirect_url,

				// (optional) form_id
				'form_id' => $form_id,

				'label_username' => __( 'Email Address', 'theme' ),

				'label_password' => __( 'Password', 'theme' ),
					
				'label_remember' => __( 'Remember Me', 'theme' ),
				
				'label_log_in' => __( 'Log In', 'theme' ),

				'id_username' => 'user_login',
				
				'id_password' => 'user_pass',
				
				'id_remember' => 'rememberme',
				
				'id_submit' => 'wp-submit',

				// (optional) Whether to remember the values
				'remember' => 1,

				// (optional) value_username
				'value_username' => urldecode( get_query_var('login_email', null) ),

				// (optional)
				'value_remember' => 0,

			)); 

			tpl_link_reset_password();

		?>
	</div>
</div>
		
<?php endif ?>