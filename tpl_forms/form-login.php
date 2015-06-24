<?php 
/**
 * Login form template - tpl_form_login()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_form_login() 
 *      @var string $form_id
 *      @var string $redirect
 * }	
 * 
 */ 
extract( $params ); ?>

<?php if( ! is_user_logged_in() ) : ?>

	<section id="member-login-section">

		<?php tpl_wrapper_secondary_open(); ?>

			<h3><?php echo __('Member Login','theme'); ?></h3>

			<form id="<?php echo $form_id ?>" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

				<label for="login-username-email_<?php echo $form_id; ?>"><?php echo __('Email or Username','theme'); ?></label>
				<input id="login-username-email_<?php echo $form_id; ?>" type="text" name="params[login_username_email]">

				<label for="login-password_<?php echo $form_id; ?>"><?php echo __('Password','theme'); ?></label>
				<input id="login-password_<?php echo $form_id; ?>" type="password" name="params[login_password]">
				
				<input type="hidden" name="params[redirect]" value="<?php echo $redirect; ?>">
				
				<?php wp_nonce_field( 'handle_member_login', 'member_login_nonce' ); ?>
				
				<input type="hidden" name="form_action" value="handle_member_login">
				<button type="submit"><?php echo __('Login','theme'); ?></button>
				
			</form>

			<?php 

			// wp_login_form(array(

			// 	// (boolean) (optional) Display the results
			// 	'echo' => 1,

			// 	// (optional) URL to redirect to. Must be absolute (as in, http://example.com/mypage/)
			// 	'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],

			// 	// (optional) form_id
			// 	'form_id' => 'loginform',

			// 	'label_username' => __( 'Username', 'theme' ),

			// 	'label_password' => __( 'Password', 'theme' ),
       			
   //     			'label_remember' => __( 'Remember Me', 'theme' ),
        		
   //      		'label_log_in' => __( 'Log In', 'theme' ),

   //      		'id_username' => 'user_login',
        		
   //      		'id_password' => 'user_pass',
        		
   //      		'id_remember' => 'rememberme',
        		
   //      		'id_submit' => 'wp-submit',

   //      		// (optional) Whether to remember the values
			// 	'remember' => 1,

			// 	// (optional) value_username
			// 	'value_username' => null,

			// 	// (optional)
			// 	'value_remember' => 0,

			// )); 

			?>

		<?php tpl_wrapper_secondary_close(); ?>

	</section>
		
<?php endif ?>