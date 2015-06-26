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

		<div class="row">

			<input type="hidden" class="check-pass-strength-username" data-pass-id="profile-form-pass" value="<?php echo $user->user_login; ?>">

			<div class="large-4 large-push-2 medium-5 medium-push-1 small-12 columns">
				<label for="firstname-input"><?php echo __('First Name') ?></label>
				<input type="email" id="firstname-input" name="params[meta][first_name]" value="<?php echo $user_meta->first_name; ?>">
			</div>

			<div class="large-4 large-pull-2 medium-5 medium-pull-1 small-12 columns">
				<label for="firstname-input"><?php echo __('First Name') ?></label>
				<input type="email" id="firstname-input" name="params[meta][first_name]" value="<?php echo $user_meta->first_name; ?>">
			</div>
			
		</div>
		
		<div class="row">
			<div class="large-8 large-centered medium-10 medium-centered small-12 columns">
				<label for="email-input"><?php echo __('Email') ?></label>
				<input type="email" id="email-input" name="params[data][user_email]" class="check-pass-strength-email" data-pass-id="profile-form-pass" value="<?php echo $user->user_email; ?>">
			</div>
		</div>

		<div class="row">

			<div class="large-5 large-push-2 medium-6 medium-push-1 small-12 columns">
				<label for="password-input"><?php echo __('Change your password') ?></label>
				<input type="password" id="password-input" class="check-pass-strength" data-pass-id="profile-form-pass" name="params[data][user_pass]" >

				<label for="confirm-password-input"><?php echo __('Confirm your new password') ?></label>
				<input type="password" id="confirm-password-input" class="check-pass-strength-confirm" data-pass-id="profile-form-pass" name="params[data][confirm_pass]" >
			</div>

			<div class="large-3 large-pull-2 medium-4 medium-pull-1 small-12 columns">
				<label for="password-strength-meter"><?php echo __('Password strength','theme'); ?></label>
				<input type="text" id="password-strength-meter" class="check-pass-strength-meter" data-pass-id="profile-form-pass" value="" readonly>
			</div>
		</div>


	</form>

<?php endif; ?>

<?php dump( $user ); ?>

<?php dump( $user_meta ); ?>