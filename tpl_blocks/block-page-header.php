<?php extract( $params ); ?>

<header class="page-header">
	
	<h1><?php echo $title ?></h1>

	<?php foreach( $meta_items as $meta ) : ?>
		
		<span class="meta"><?php echo $meta; ?></span>

	<?php endforeach ?>

</header>