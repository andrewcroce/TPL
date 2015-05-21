<nav id="skip-links" class="screenreader">

	<?php $links = apply_filters('wp_starter_skiplinks', array() ); ?>

	<?php foreach( $links as $anchor => $label ) : ?>

		<a href="<?php echo $anchor; ?>"><?php echo $label; ?></a>
	
	<?php endforeach; ?>

</nav>