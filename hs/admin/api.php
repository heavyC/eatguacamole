<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	require_once("includes/constants.php");
	require_once("includes/session.php");
	require_once("includes/connection.php");
	require_once("includes/functions.php");
	require_once("includes/form_functions.php");
	
	if (isset($_GET['isUpdateForVersion'])) {
		$last_loaded = $_GET['isUpdateForVersion'];
		$retval = ""; //"no new version available";
		
		$query = 'select dbversion_id, dbversion_date, dbversion_comment from dbversion where dbversion_id = (select max(dbversion_id) from dbversion)';
		$result = mysql_query($query);
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$latest_update_available = "{$row['dbversion_id']}";
			if ($last_loaded < $latest_update_available) {
				$retval = "{$row['dbversion_id']}&&{$row['dbversion_date']}&&{$row['dbversion_comment']}";
			}
		}
		echo $retval;
	}
	else if (isset($_GET['getUpdatesForVersion'])) {
		$last_loaded = $_GET['getUpdatesForVersion'];
		$retval = "";
		
		$query = "select cat_id, cat_seqno, cat_name, cat_desc from cat where cat_vid <= (select max(dbversion_id) from dbversion) and cat_vid > " . $last_loaded;
		$result = mysql_query($query);
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$retval .= "cat&&{$row['cat_id']}&&{$row['cat_seqno']}&&{$row['cat_name']}&&{$row['cat_desc']}|";
		}
		
		$query = "select word_id, english, spanish, literal, word_cat_id from word where word_vid <= (select max(dbversion_id) from dbversion) and word_vid > " . $last_loaded;
		$result = mysql_query($query);
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$retval .= "word&&{$row['word_id']}&&{$row['word_cat_id']}&&{$row['english']}&&{$row['spanish']}&&{$row['literal']}|";
		}
		
		
		
		$query = "select del_table, del_rowid from dbdelete where del_vid <= (select max(dbversion_id) from dbversion) and del_vid > " . $last_loaded;
		$result = mysql_query($query);
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$retval .= "delete&&{$row['del_table']}&&{$row['del_rowid']}|";
		}
		
		
		
		$retval = trim($retval, "|");
		echo $retval;
	}
	
?>
























