<?php 
/**
 * Alert block template - tpl_block_alert()
 * 
 * @var array $params {
 *      
 *      Parameters passed into the template from tpl_block_alert() 
 *      
 *		 @var string $message Alert message to display
 *		 @var string $class alert-box class name
 * }	
 * 
 */ 
extract( $params ); ?>

<div class="alert-box <?php echo $class; ?>" data-alert tabindex="0" aria-live="assertive" role="dialogalert">
	
	<p><?php echo $message; ?></p>

	<button href="#" tabindex="0" class="close" aria-label="Close Alert">
		<span aria-hidden="true">&times;</span>
	</button>
	
</div>