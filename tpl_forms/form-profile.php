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
	

	<?php if( get_query_var('profile_error',0) ){
		tpl_block_alert( __('There was a problem saving your profile.','theme'), 'alert' );
	} ?>
	
	<?php if( get_query_var('update',0) ){
		tpl_block_alert( __('Your profile has been updated.','theme') );
	} ?>	

	<form method="post" action="<?php the_permalink(); ?>" data-abide>

		<div class="row">

			<input type="hidden" class="check-pass-strength-username" data-pass-id="profile-form-pass" name="params[username]" value="<?php echo $user->user_login; ?>">
			<input type="hidden" name="params[user_id]" value="<?php echo $user->ID; ?>">

			<div class="large-4 large-push-2 medium-5 medium-push-1 small-12 columns">

				<div class="form-field">
					<label for="firstname-input"><?php echo __('First Name') ?></label>
					<input type="text" id="firstname-input" name="params[meta][first_name]" value="<?php echo $user_meta->first_name; ?>">
				</div>

			</div>

			<div class="large-4 large-pull-2 medium-5 medium-pull-1 small-12 columns">

				<div class="form-field">
					<label for="lastname-input"><?php echo __('Last Name') ?></label>
					<input type="text" id="lastname-input" name="params[meta][last_name]" value="<?php echo $user_meta->last_name; ?>">
				</div>

			</div>
			
		</div>


		<div class="row">
			<div class="large-8 large-centered medium-10 medium-centered small-12 columns">

				<div class="form-field">
					<label for="display-name-input"><?php echo __('Display Name','theme') ?> <span class="secondary-text"><?php echo __('Required','theme'); ?>. <?php echo __('How will your name be displayed publicly wherever it is visible on the site?','theme'); ?></span></label>
					<input required type="text" id="display-name-input" name="params[data][display_name]" value="<?php echo $user->display_name; ?>">
					<span class="secondary-text error"><?php echo __('An display name is required'); ?></span>
				</div>

			</div>
		</div>

		
		<div class="row">
			<div class="large-8 large-centered medium-10 medium-centered small-12 columns">

				<div class="form-field">
					<label for="email-input"><?php echo __('Email') ?> <span class="secondary-text"><?php echo __('Required','theme'); ?></span></label>
					<input required type="email" id="email-input" name="params[data][user_email]" class="check-pass-strength-email" data-pass-id="profile-form-pass" value="<?php echo $user->user_email; ?>">
					<span class="secondary-text error"><?php echo __('An email address is required'); ?></span>
				</div>

			</div>
		</div>

		<div class="row">

			<div class="large-5 large-push-2 medium-6 medium-push-1 small-12 columns">

				<div class="form-field">
					<label for="password-input"><?php echo __('Change your password') ?></label>
					<input type="password" id="password-input" class="check-pass-strength" data-pass-id="profile-form-pass" name="params[password]" data-abide-validator="strongPassword">
					<span class="secondary-text error"><?php echo __('Please enter a stronger password','theme'); ?></span>
				</div>
				
				<div class="form-field">
					<label for="confirm-password-input"><?php echo __('Confirm your new password') ?></label>
					<input type="password" id="confirm-password-input" class="check-pass-strength-confirm" data-pass-id="profile-form-pass" name="params[confirm_password]" data-equalto="password-input">
					<span class="secondary-text error"><?php echo __('Password confirmation does not match','theme'); ?></span>
				</div>

			</div>

			<div class="large-3 large-pull-2 medium-4 medium-pull-1 small-12 columns">

				<div class="form-field">
					<label for="password-strength-meter"><?php echo __('Password strength','theme'); ?></label>
					<input type="text" id="password-strength-meter" class="check-pass-strength-meter" data-pass-id="profile-form-pass" value="" readonly pattern="allowed_statuses">
				</div>

			</div>
		</div>


		<div class="row">
			<div class="large-8 large-centered medium-10 medium-centered columns">
				<?php wp_nonce_field( 'user_save_profile', 'user_save_profile_nonce' ); ?>
				<input type="hidden" name="form_action" value="user_save_profile">
				<input type="submit" class="submit-gate-protected" data-open="1" value="<?php echo __('Save Profile','theme'); ?>">
			</div>
		</div>


	</form>

<?php endif; ?>