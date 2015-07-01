<?php
/**
 * Skip link template
 * 
 * To add items to the skiplinks, append items in the matching filter hook function
 * in theme.class.php, _add_skiplinks().
 * Or add another filter hook to 'wp_starter_skiplinks' somewhere else.
 *
 * @var array $links {
 *
 *		Array of array of links with the format:
 *		@var array {
 *		     $anchor => $label
 *		}
 * }
 */ ?>
<nav id="skip-links" class="screenreader">

	<?php $links = apply_filters('skiplinks', array() ); ?>

	<?php foreach( $links as $anchor => $label ) : ?>

		<a href="<?php echo $anchor; ?>"><?php echo $label; ?></a>
	
	<?php endforeach; ?>

</nav>