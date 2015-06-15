<?php


/**
 * Include the bar foo template
 {{param_docs}}
 */
function tpl_foo_bar($abc, $def, $ghi) {
	tpl( 'foo' , 'bar' , array(
		'abc' => $abc
,		'def' => $def
,		'ghi' => $ghi
	) );
}