<?php

/**
 * Include the Read More link template
 * @param  string $url     URL to link to
 * @param  string $label   Link text. Default: 'Read More'
 * @param  string $classes Classes to add to the <a> tag. Default: 'read-more'
 */
function tpl_readmore( $url, $label = '', $classes = 'read-more' ) {

	if( empty( $label ) ) $label = __('Read More');

	tpl('link','read-more', array(
		'url' => $url,
		'label' => $label,
		'classes' => $classes
	));
}