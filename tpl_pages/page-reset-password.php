<?php

/**
 * Set Vars
 */
global  $wpdb, $user_ID;

$status_message = '';


/**
 * Helper functions
 */
function tg_validate_url() {
    global $post;
    $page_url = esc_url(get_permalink( $post->ID ));
    $urlget = strpos($page_url, "?");
    if ($urlget === false) {
        $concate = "?";
    } else {
        $concate = "&";
    }
    return $page_url.$concate;
}


function load_base_template( $status_message = '' ){
?>

<?php get_header(); ?>

<?php if( have_posts() ) : ?>

  <?php while( have_posts() ) : the_post(); $page = new ACFPost( $post ); ?>

    <article class="login page" itemscope itemtype="http://schema.org/WebPage">

      <div class="row">

        <section class="large-5 columns">
          <h1 class="large-heading">Reset Password</h1>
        </section>

        <section class="large-5 columns form-content">

          <form class="user_form" id="wp_pass_reset" action="" method="post">
            <?php if ($status_message != ''): ?>
                <div class="status-message neg">
                  <p><?php echo $status_message; ?></p>
                </div>
            <?php endif; ?>
            <p class="login-password">
              <label for="user-email">Email Address</label>
              <input type="text" class="text" name="user_input" id="user-email" value="" />
            </p>

            <input type="hidden" name="action" value="tg_pwd_reset" />
            <input type="hidden" name="tg_pwd_nonce" value="<?php echo wp_create_nonce("tg_pwd_nonce"); ?>" />

            <p class="login-submit">
              <input type="submit" id="submitbtn" class="reset_password button-primary" name="submit" value="Reset Password" />
            </p>

          </form>

          <div id="result"></div> <!-- To hold validation results -->

          <script type="text/javascript">
            $("#wp_pass_reset").submit(function() {
              $('#result').html('<span class="loading">Validating...</span>').fadeIn();
              var input_data = $('#wp_pass_reset').serialize();
              $.ajax({
                type: "POST",
                url:  "<?php echo get_permalink( $post->ID ); ?>",
                data: input_data,
                success: function(msg){
                  $('.loading').remove();
                  $('<div>').html(msg).appendTo('div#result').hide().fadeIn('slow');
                }
              });
              return false;
            });
          </script>

        </section>

      </div>

    </article><!-- content -->

  <?php endwhile; ?>

<?php endif; ?>


<?php get_footer(); ?>




<?php
} // end load_base_template



/**
 * Logged in users should not be here
 */
if ($user_ID) {
    //block logged in users
    wp_redirect( home_url() ); exit;
}




/**
 * Process page states
 */

// state 1 if a user has a key and the request action is reset password
// add a new password to the DB and send the user the email with the information in it.
if(isset($_GET['key']) && $_GET['action'] == "reset_pwd") {



    $reset_key = $_GET['key'];
    $user_login = $_GET['login'];
    $user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));

    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;

    if(!empty($reset_key) && !empty($user_data)) {
        $new_password = wp_generate_password(7, false);
            //echo $new_password; exit();
            wp_set_password( $new_password, $user_data->ID );
            //mailing reset details to the user
        $message = __('Your new password for the account at:') . "\r\n\r\n" . '<br>';
        $message .= get_home_url() . "\r\n\r\n" . '<br>';
        $message .= sprintf(__('Username: %s'), $user_email) . "\r\n\r\n" . '<br>';
        $message .= sprintf(__('Password: %s'), $new_password) . "\r\n\r\n" . '<br>';
        $message .= __('You can now login with your new password at: ') . get_option('siteurl')."/login" . "\r\n\r\n" . '<br>';
        $message .= '<br><hr /><br>';

        $headers[] = 'From: Admin';
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        if ( $message && !wp_mail($user_email, 'Password Reset Request', $message, $headers) ) {
            //get_header();
            $status_message = 'Email failed to send for some unknown reason';
            load_base_template($status_message);
            //get_footer();
            exit();
        }
        else {
            $redirect_to = get_bloginfo('url')."/reset-password?action=reset_success";
            wp_safe_redirect($redirect_to);
            exit();
        }
    }
    else exit('Not a Valid Key.');

}
// end state one
//


// state two, this is the action for the inital password set
if($_POST['action'] == "tg_pwd_reset"){


    if ( !wp_verify_nonce( $_POST['tg_pwd_nonce'], "tg_pwd_nonce")) {
        load_base_template('No Tricks Please!');
        exit();
    }
    if(empty($_POST['user_input'])) {
        load_base_template('Please Enter Your Email Address');
        exit();
    }
    $user_input = trim($_POST['user_input']);

    if ( strpos($user_input, '@') ) {
        //$user_data = get_user_by_email($user_input);
       $user_data = get_user_by( 'email', $user_input );
        if( empty($user_data) ) { //delete the condition $user_data->caps[administrator] == 1, if you want to allow password reset for admins also
            load_base_template('Invalid Email Address');
            exit();
        }
    } else {
        $user_data = get_userdatabylogin($user_input);
        if( empty($user_data) ) { //delete the condition $user_data->caps[administrator] == 1, if you want to allow password reset for admins also
            load_base_template('Invalid Username');
            exit();
        }
    }

    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;

    $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
    if(empty($key)) {
        //generate reset key
        $key = wp_generate_password(20, false);
        $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
    }

    //mailing reset details to the user
    $message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n" . '<br>';
    $message .= get_home_url() . "\r\n\r\n" . '<br>';
    $message .= sprintf(__('Username: %s'), $user_email) . "\r\n\r\n" . '<br>';
    $message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n" . '<br>';
    $message .= __('To reset your password, visit the following address:') . "\r\n\r\n" . '<br>';
    $message .= tg_validate_url() . "action=reset_pwd&key=$key&login=" . rawurlencode($user_login) . "\r\n" . '<br>';
    $message .= '<br><hr /><br>';

    $headers[] = 'From: Admin';
    $headers[] = 'Content-Type: text/html; charset=UTF-8';

    if ( $message && !wp_mail($user_email, 'Password Reset Request', $message, $headers) ) {
        load_base_template("Email failed to send, please contact the site Admin");
        exit();
    } else {
        load_base_template("We have just sent you an email with Password reset instructions");
        exit();
    }

}
// end state two
//

// State Three
// This should usually go to the login page, but intially the reset password page will support this messaging.
if ($_GET['action'] == 'reset_success'){
    load_base_template("You will receive an email with your new password shortly.");
    exit();
}
// end state three
//

//
// end processing
//

// initialize the page...
load_base_template();
?>
