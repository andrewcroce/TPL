<?php $mail = wp_mail(
	'andrew.croce@vius.co',
	'Test email',
	'This is a test with mail()',
	'From: Andrew Croce <andrew.croce@gmail.com>'
);

print $mail;