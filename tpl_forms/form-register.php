<?php 
/**
 * Registration form template - tpl_form_register()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_form_register() 
 *      
 *		 
 * }	
 * 
 */ 
//extract( $params ); ?>


<form method="post" action="<?php the_permalink(); ?>" data-abide>

	<div class="row">

		<div class="large-4 large-push-2 medium-5 medium-push-1 small-12 columns">

			<input type="hidden" class="check-pass-strength-username" data-pass-id="register-form-pass" name="params[username]" value="">


			<div class="form-field">
				<label for="firstname-input"><?php echo __('First Name') ?> <span class="secondary-text"><?php echo __('Optional','theme'); ?></span></label>
				<input type="text" id="firstname-input" name="params[meta][first_name]" value="">
			</div>

		</div>

		<div class="large-4 large-pull-2 medium-5 medium-pull-1 small-12 columns">

			<div class="form-field">
				<label for="lastname-input"><?php echo __('Last Name') ?> <span class="secondary-text"><?php echo __('Optional','theme'); ?></span></label>
				<input type="text" id="lastname-input" name="params[meta][last_name]" value="">
			</div>

		</div>
		
	</div>


	<div class="row">
		<div class="large-8 large-centered medium-10 medium-centered small-12 columns">

			<div class="form-field">
				<label for="display-name-input"><?php echo __('Display Name','theme') ?> <span class="secondary-text"><?php echo __('Required','theme'); ?>. <?php echo __('How will your name be displayed publicly wherever it is visible on the site?','theme'); ?></span></label>
				<input required type="text" id="display-name-input" name="params[data][display_name]" value="">
				<span class="secondary-text error"><?php echo __('An display name is required. It doesn\'t have to be your real name, but we have to call you something.'); ?></span>
			</div>

		</div>
	</div>

	
	<div class="row">
		<div class="large-8 large-centered medium-10 medium-centered small-12 columns">

			<div class="form-field">
				<label for="email-input"><?php echo __('Email') ?> <span class="secondary-text"><?php echo __('Required','theme'); ?></span></label>
				<input required type="email" id="email-input" name="params[data][user_email]" class="check-pass-strength-email" data-pass-id="register-form-pass" value="">
				<span class="secondary-text error"><?php echo __('An email address is required. You will use this to login.'); ?></span>
			</div>

		</div>
	</div>

	<div class="row">

		<div class="large-5 large-push-2 medium-6 medium-push-1 small-12 columns">

			<div class="form-field">
				<label for="password-input"><?php echo __('Create a password','theme') ?></label>
				<input required type="password" id="password-input" class="check-pass-strength show-password-toggleable" data-pass-id="register-form-pass" name="params[password]" data-abide-validator="strongPassword">
				<span class="secondary-text error"><?php echo __('Please enter a stronger password','theme'); ?></span>
			</div>
			
			<div class="form-field">
				<label for="confirm-password-input"><?php echo __('Enter your password again','theme') ?></label>
				<input required type="password" id="confirm-password-input" class="check-pass-strength-confirm show-password-toggleable" data-pass-id="register-form-pass" name="params[confirm_password]" data-equalto="password-input">
				<span class="secondary-text error"><?php echo __('Password confirmation does not match','theme'); ?></span>
			</div>

			<div class="form-field">
				<label for="show-password">
					<input type="checkbox" id="show-password" class="show-password-toggle">
					<?php echo __('Show password','theme') ?>
				</label>
			</div>

		</div>

		<div class="large-3 large-pull-2 medium-4 medium-pull-1 small-12 columns">

			<div class="form-field">
				<label for="password-strength-meter"><?php echo __('Password strength','theme'); ?></label>
				<input type="text" id="password-strength-meter" class="check-pass-strength-meter" data-pass-id="register-form-pass" value="" readonly pattern="allowed_statuses">
			</div>

		</div>
	</div>


	<div class="row">
		<div class="large-8 large-centered medium-10 medium-centered columns">
			<?php wp_nonce_field( 'user_register', 'user_register_nonce' ); ?>
			<input type="hidden" name="form_action" value="user_register">
			<input type="submit" value="<?php echo __('Register','theme'); ?>">
		</div>
	</div>


</form>