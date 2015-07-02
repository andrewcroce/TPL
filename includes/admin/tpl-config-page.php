<div class="wrap">
	
	<h2><?php echo __('TPL Configuration Settings','theme'); ?></h2>

	<form action='options.php' method="post" class="repeater">

	<?php
	    settings_fields('tpl-config');
	    do_settings_sections('tpl-config');
	    submit_button();
	?>

	</form>

</div>