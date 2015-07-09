<?php

/**
 * Enter new password form - tpl_form_reset_password()
 * This form will load only if a valid activation_key and username are contained in the URL
 * Otherwise the reset-password form will be loaded
 *
 */ ?>


<form method="post" action="<?php the_permalink(); ?>" data-abide>

    <div class="row">
       <div class="large-5 large-push-2 medium-6 medium-push-1 small-12 columns">

            <input type="hidden" class="check-pass-strength-username" data-pass-id="reset-form-pass" name="params[username]" value="<?php echo $user->user_login; ?>">
            <input type="hidden" class="check-pass-strength-email" data-pass-id="reset-form-pass" name="params[user_email]" value="<?php echo $user->user_email; ?>">
            <input type="hidden" name="params[user_id]" value="<?php echo $user->ID; ?>">
            <input type="hidden" name="params[activation_key]" value="<?php echo get_query_var('activation_key'); ?>">
    
            <div class="form-field">
                <label for="password-input"><?php echo __('Enter a new password','theme') ?></label>
                <input type="password" id="password-input" class="check-pass-strength show-password-toggleable" data-pass-id="reset-form-pass" name="params[password]" data-abide-validator="strongPassword">
                <span class="secondary-text error"><?php echo __('Please enter a stronger password','theme'); ?></span>
            </div>
            
            <div class="form-field">
                <label for="confirm-password-input"><?php echo __('Enter your new password again','theme') ?></label>
                <input type="password" id="confirm-password-input" class="check-pass-strength-confirm show-password-toggleable" data-pass-id="reset-form-pass" name="params[confirm_password]" data-equalto="password-input">
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
                <input type="text" id="password-strength-meter" class="check-pass-strength-meter" data-pass-id="reset-form-pass" value="" readonly pattern="allowed_statuses">
            </div>

        </div>
    </div>

    <div class="row">
        <div class="large-8 large-centered medium-10 medium-centered columns">
            <?php wp_nonce_field( 'user_new_password', 'user_new_password_nonce' ); ?>
            <input type="hidden" name="form_action" value="user_new_password">
            <input type="submit" value="<?php echo __('Reset Password & Login','theme'); ?>">
        </div>
    </div>

</form>
