<div class="wrap">
	
	<h2><?php echo __('Functionality Settings','theme'); ?></h2>

	<form action='options.php' method="post">

	<?php
	    settings_fields('functionality-settings');
	    do_settings_sections('functionality-settings');
	    submit_button();
	?>

	</form>

</div>