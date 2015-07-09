<?php 
/**
 * Read More Link Template - tpl_link_read_more()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_link_read_more()
 *
 * 		@var string $url     URL to link to
 * 		@var string $label   Link text. Default: 'Read More'
 * 		@var string $classes Classes to add to the <a> tag. Default: 'read-more'
 * }	
 * 
 */ 

extract( $params ); ?>

<a href="<?php echo esc_url( $url ); ?>" class="<?php echo $classes; ?>"><?php echo $label; ?></a>