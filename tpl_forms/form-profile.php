<?php 
/**
 * Profile form template - tpl_form_profile()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_form_profile() 
 *      
 *		 @var WP_User $user WP User object
 *		 @var onject $user_meta Object containing all usermeta fields
 * }	
 * 
 */ 
extract( $params ); ?>

<?php if( is_user_logged_in() ) : ?>
	
	<form method="post" action="<?php the_permalink(); ?>">
		
		<label for="email-input"><?php echo __('Email') ?></label>
		<input type="email" id="email-input" name="params[data][user_email]" value="<?php echo $user->user_email; ?>">

		<label for="firstname-input"><?php echo __('First Name') ?></label>
		<input type="email" id="firstname-input" name="params[meta][first_name]" value="<?php echo $user_meta->first_name; ?>">

		<?php echo $user->user_email; ?>

	</form>

<?php endif; ?>

<?php dump( $user ); ?>

<?php dump( $user_meta ); ?>