<?php 
/**
 * Login Link Template - tpl_link_login()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_link_login()
 *
 * 		@var string $url     	URL to link to. Either the default wp_login_url()
 * 		     					or home_url('login') if front-end login setting is enabled.
 * 		@var string $label   	Link text. Default: 'Login'
 * 		@var string $classes 	Classes to add to the <a> tag. Default: 'login'
 * }	
 * 
 */ 

extract( $params ); ?>

<a href="<?php echo esc_url( $url ); ?>" class="<?php echo $classes; ?>"><?php echo $label; ?></a>