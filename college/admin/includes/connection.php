<?php
## Squid EatGuacamole.com
# Database name  	 525657_college  	 
# Hostname 	mysql50-85.wc1.dfw1.stabletransit.com
# Please use the hostname for all connection settings inside your code.

## Squid - EatGuacamole.com
define("DB_NAME", "525657_college");
define("DB_SERVER", "mysql50-85.wc1.dfw1.stabletransit.com");
#define("DB_USER", "525657_admin");
#define("DB_PASS", "Swag001!");
define("DB_USER", "525657_hornbeek");
define("DB_PASS", "mikecarr");

## localhost
#define("DB_NAME", "college");
#define("DB_SERVER", "127.0.0.1");
#define("DB_USER", "root");
#define("DB_PASS", "admin");

## Squid - College
#define("DB_NAME", "geiser_poker");
#define("DB_SERVER", "localhost:3306");
#define("DB_USER", "hornbeek");
#define("DB_PASS", "mikecarr");

## Squid - Horse Spanish
#define("DB_NAME", "geiser_poker");
#define("DB_SERVER", "localhost:3306");
#define("DB_USER", "kari");
#define("DB_PASS", "Hedge%0g");

## Squid - Poker
#define("DB_NAME", "geiser_poker");
#define("DB_SERVER", "localhost:3306");
#define("DB_USER", "hornbeek");
#define("DB_PASS", "mikecarr");

## Squid - David Geiser
#define("DB_NAME", "david_geiser");
#define("DB_SERVER", "localhost:3306");
#define("DB_USER", "david");
#define("DB_PASS", "bumpy");
	
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