<?php

function tpl_wrapper_header_start(){
	switch( true ){
		default :
			tpl('wrapper','fullwidth-start');
	}
}

function tpl_wrapper_header_end(){
	switch( true ){
		default :
			tpl('wrapper','fullwidth-end');	
	}
}