<?php 
/**
 * Logout Link Template - tpl_link_logout()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_link_logout()
 *
 * 		@var string $url     	The default wp_logout_url()
 * 		@var string $label   	Link text. Default: 'Logout'
 * 		@var string $classes 	Classes to add to the <a> tag. Default: 'logout'
 * }	
 * 
 */ 

extract( $params ); ?>

<a href="<?php echo esc_url( $url ); ?>" class="<?php echo $classes; ?>"><?php echo $label; ?></a>