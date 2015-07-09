<?php 

$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'template_settings'; ?>

<div class="wrap">
	
	<h2><?php echo __('TPL Configuration Settings','theme'); ?></h2>

	<h2 class="nav-tab-wrapper">  
    	<a href="?page=tpl-config&tab=template_settings" class="nav-tab <?php echo $active_tab == 'template_settings' ? 'nav-tab-active' : ''; ?>"><?php echo __('Pages & Templates','theme'); ?></a>  
    	<a href="?page=tpl-config&tab=member_tools_settings" class="nav-tab <?php echo $active_tab == 'member_tools_settings' ? 'nav-tab-active' : ''; ?>"><?php echo __('Member Tools','theme'); ?></a>  
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

	    	case 'menu_settings':
	    		settings_fields( 'tpl-config-menus' );
                do_settings_sections( 'tpl-config-menus' );
	    		break;
	    	
	    }
	    submit_button();
	?>

	</form>

</div>