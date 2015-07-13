<?php 

$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'template_settings'; ?>

<div class="wrap">
	
	<h2><?php echo __('TPL Configuration Settings','theme'); ?></h2>

	<h2 class="nav-tab-wrapper">  
    	<a href="?page=tpl-config&tab=template_settings" class="nav-tab <?php echo $active_tab == 'template_settings' ? 'nav-tab-active' : ''; ?>"><?php echo __('Pages & Templates','theme'); ?></a>  
    	<a href="?page=tpl-config&tab=member_tools_settings" class="nav-tab <?php echo $active_tab == 'member_tools_settings' ? 'nav-tab-active' : ''; ?>"><?php echo __('Member Tools','theme'); ?></a>  
    	<a href="?page=tpl-config&tab=media_settings" class="nav-tab <?php echo $active_tab == 'media_settings' ? 'nav-tab-active' : ''; ?>"><?php echo __('Media','theme'); ?></a>  
    	<a href="?page=tpl-config&tab=menu_settings" class="nav-tab <?php echo $active_tab == 'menu_settings' ? 'nav-tab-active' : ''; ?>"><?php echo __('Menus','theme'); ?></a>  
    </h2>  

	<form action='options.php' method="post" class="repeater">

	<?php
	    //settings_fields('tpl-config');
	    //do_settings_sections('tpl-config');

	    switch( $active_tab ) {
	    	
	    	case 'template_settings':
	    		settings_fields( 'tpl-config-template' );
                do_settings_sections( 'tpl-config-template' );
	    		break;

	    	case 'member_tools_settings':
	    		settings_fields( 'tpl-config-member-tools' );
                do_settings_sections( 'tpl-config-member-tools' );
	    		break;

	    	case 'media_settings':
	    		settings_fields( 'tpl-config-media' );
                do_settings_sections( 'tpl-config-media' );
	    		break;

	    	case 'menu_settings':
	    		settings_fields( 'tpl-config-menus' );
                do_settings_sections( 'tpl-config-menus' );
	    		break;
	    	
	    }
	    submit_button();
	?>

	</form>
	
	<?php 
	if( $active_tab == 'media_settings' ){ 
		echo '<hr><br>';
		echo "<h3>Regenerate Images</h3>";
		if( class_exists('ForceRegenerateThumbnails') ){
			echo '<p class="description">'.__('After adding or modifying image sizes, you\'ll need to regenerate the cropped images in your media library. This uses the great plugin <strong>Force Regenerate Thumbnails</strong>.').'</p>';
			echo '<a class="button-primary" href="tools.php?page=force-regenerate-thumbnails">'.__('Regenerate Images','theme').'</a>';
		} else {
			echo '<p class="description">'.__('After adding or modifying image sizes, you\'ll need to regenerate the cropped images in your media library. <strong><a href="plugin-install.php?tab=plugin-information&plugin=force-regenerate-thumbnails" target="_blank">Force Regenerate Thumbnails</a></strong> is highly recommended for this.').'</p>';
		}
	}?>

</div>