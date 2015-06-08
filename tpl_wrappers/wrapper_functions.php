<?php
/**
 * These functions are used to create common wrappers to use in multiple templates.
 * Each function should be part of an open/close pair, to be used respectively before and after the enclosed HTML.
 * @see scss/_wrappers.scss for styling
 */



/**
 * ==============
 * Header Wrapper
 * ==============
 */

// Opening HTML tags for header wrapper
function tpl_wrapper_header_open() {
	echo '<div class="header-wrapper"><div class="row"><div class="large-12 columns">';
}

// Closing HTML tags for header wrapper
function tpl_wrapper_header_close() {
	echo '</div></div></div>';
}



/**
 * ===============
 * Content Wrapper
 * ===============
 */

// Opening HTML tags for content wrapper
function tpl_wrapper_content_open() {
	echo '<div class="content-wrapper"><div class="row"><div class="large-12 columns">';
}

// Closing HTML tags for content wrapper
function tpl_wrapper_content_close() {
	echo '</div></div></div>';
}



/**
 * =================
 * Secondary Wrapper
 * =================
 */

// Opening HTML tags for content wrapper
function tpl_wrapper_secondary_open() {
	echo '<div class="secondary-wrapper"><div class="row"><div class="large-12 columns">';
}

// Closing HTML tags for content wrapper
function tpl_wrapper_secondary_close() {
	echo '</div></div></div>';
}