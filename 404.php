<?php get_header(); ?>

<?php tpl('wrapper','12col-start'); ?>

	<article class="error-404 page">
		
		<h1><?php echo __('Page Not Found') ?></h1>

		<p><?php echo _('There is a 404 error at this URL, which means the page does not exist. Please double check you entered the URL correctly, or try browsing the menu to find what you are looking for.') ?></p>

	</article>

<?php tpl('wrapper','12col-end'); ?>

<?php get_footer(); ?>