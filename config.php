<?php
	// Setting DB information.
	define('DB_HOST', 		'localhost');
	define('DB_USERNAME', 	'root');
	define('DB_PASSWORD', 	'root');
	define('DB_DB', 		'rest_api');

	$db = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD) or die('Failed Connect');
	mysqli_select_db($db, DB_DB) or die('Failed DB Select');
?>