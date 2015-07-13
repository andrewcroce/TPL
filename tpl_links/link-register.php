<?php 
/**
 * Registration Link Template - tpl_link_register()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_link_register()
 *
 * 		@var string $url     	The default home_url('register')
 * 		@var string $label   	Link text. Default: 'Register'
 * 		@var string $classes 	Classes to add to the <a> tag. Default: 'register'
 * }	
 * 
 */ 

extract( $params ); ?>

<a href="<?php echo esc_url( $url ); ?>" class="<?php echo $classes; ?>"><?php echo $label; ?></a>