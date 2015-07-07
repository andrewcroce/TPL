<?php 
/**
 * Profile Link Template - tpl_link_profile()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_link_profile()
 *
 * 		@var string $url     	URL to link to. Either the default admin_url('profile.php')
 * 		     					or home_url('profile') if front-end profile setting is enabled.
 * 		@var string $label   	Link text. Default: 'Profile'
 * 		@var string $classes 	Classes to add to the <a> tag. Default: 'profile'
 * }	
 * 
 */ 

extract( $params ); ?>

<a href="<?php echo esc_url( $url ); ?>" class="<?php echo $classes; ?>"><?php echo $label; ?></a>