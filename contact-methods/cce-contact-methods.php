<?php

/* Additional contact methods in user profiles */
add_filter('user_contactmethods', 'cce_add_contactmethods', 10, 1);
		
function cce_add_contactmethods( $contactmethods ) {
	$contactmethods['twitter'] 		= 'Twitter';
	$contactmethods['facebook'] 	= 'Facebook';
	$contactmethods['instagram'] 	= 'Instagram';
	$contactmethods['gplus'] 		= 'Google Plus';
	$contactmethods['pinterest'] 	= 'Pinterest';
	$contactmethods['tumblr'] 		= 'Tumblr';

	return $contactmethods;
}