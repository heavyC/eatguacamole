<?php
	$conn = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
	setlocale(LC_MONETARY, 'en_US');
	date_default_timezone_set('America/New_York');
	if (!$conn) {
		die("Could not connect to database: " . mysql_error());
	}
	$db_select = mysql_select_db(DB_NAME, $conn);
	if (!$db_select) {
		die("Database connection error: " . mysql_error());
	}
	session_start();
?>