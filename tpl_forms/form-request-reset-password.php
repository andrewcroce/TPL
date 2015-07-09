<?php

/**
 * Request password reset form - tpl_form_reset_password()
 * 
 */ ?>

<form method="post" action="<?php the_permalink(); ?>" data-abide>

    <div class="row">
        <div class="large-8 large-centered medium-10 medium-centered small-12 columns">

            <div class="form-field">
                <label for="username-email-input"><?php echo __('Email Address','theme'); ?></label>
                <input required type="text" name="params[username_email]" id="username-email-input" value="">
                <span class="secondary-text error"><?php echo __('Please enter the email address you used when registering.','theme'); ?></span>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="large-8 large-centered medium-10 medium-centered columns">
            <?php wp_nonce_field( 'user_reset_password', 'user_reset_password_nonce' ); ?>
            <input type="hidden" name="form_action" value="user_reset_password">
            <input type="submit" value="<?php echo __('Send Password Reset Instructions','theme'); ?>">
        </div>
    </div>

</form>