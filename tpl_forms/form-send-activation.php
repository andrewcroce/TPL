<?php

/**
 * Resend email activation form - tpl_form_send_activation()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_form_send_activation() 
 *      
 *      @var string $status_message
 *      @var string $alert_level
 * }  
 * 
 */ 
extract( $params ); ?>


<?php if( !empty( $status_message ) ){ 
    
    tpl_block_alert( $status_message, $alert_level );

} ?>

<form method="post" action="<?php the_permalink(); ?>" data-abide>

    <div class="row">
        <div class="large-8 large-centered medium-10 medium-centered small-12 columns">

            <div class="form-field">
                <label for="username-email-input"><?php echo __('Email Address','theme'); ?></label>
                <input required type="text" name="params[username_email]" id="username-email-input" value="">
                <span class="secondary-text error"><?php echo __('Please enter your email address','theme'); ?></span>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="large-8 large-centered medium-10 medium-centered columns">
            <?php wp_nonce_field( 'user_send_activation', 'user_send_activation_nonce' ); ?>
            <input type="hidden" name="form_action" value="user_send_activation">
            <input type="submit" value="<?php echo __('Send Acivation Email','theme'); ?>">
        </div>
    </div>

</form>