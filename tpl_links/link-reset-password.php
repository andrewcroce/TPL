<?php 
/**
 * Reset Password Link Template - tpl_link_reset_password()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_link_reset_password()
 *
 * 		@var string $url     	URL to link to. Either the default wp_lostpassword_url()
 * 		     					or home_url('reset-password') if front-end login setting is enabled.
 * 		@var string $label   	Link text. Default: 'Reset Password'
 * 		@var string $classes 	Classes to add to the <a> tag. Default: 'reset-password'
 * }	
 * 
 */ 

extract( $params ); ?>

<a href="<?php echo esc_url( $url ); ?>" class="<?php echo $classes; ?>"><?php echo $label; ?></a>