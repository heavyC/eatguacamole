<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

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
		
		// college
		$c_query = "select college_id, college_name, college_type, college_location, college_deadline, college_test, college_fee, college_rec, college_essay, college_interview ";
		$c_query .= " from college where college_vid <= (select max(dbversion_id) from dbversion) and college_vid > " . $last_loaded;
		$result = mysql_query($c_query);
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$retval .= "college&&{$row['college_id']}&&{$row['college_name']}&&{$row['college_type']}&&{$row['college_location']}&&{$row['college_deadline']}&&{$row['college_test']}&&{$row['college_fee']}&&{$row['college_rec']}&&{$row['college_essay']}&&{$row['college_interview']}|";
		}
		
		// college_apl
		$ca_query = "select college_apl_id, college_id, apl_id from college_apl where college_apl_vid <= (select max(dbversion_id) from dbversion) and college_apl_vid > " . $last_loaded;
		$result = mysql_query($ca_query);
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$retval .= "college_apl&&{$row['college_apl_id']}&&{$row['college_id']}&&{$row['apl_id']}|";
		}
		
		// tip_type
		$tt_query = "select tip_type_id, tip_type_display_order, tip_type_title, tip_type_short_title from tip_type where tip_type_vid <= (select max(dbversion_id) from dbversion) and tip_type_vid > " . $last_loaded;
		$result = mysql_query($tt_query);
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$retval .= "tip_type&&{$row['tip_type_id']}&&{$row['tip_type_display_order']}&&{$row['tip_type_title']}&&{$row['tip_type_short_title']}|";
		}
		
		
		// tip
		$t_query = "select tip_id, tip_display_order, tip_heading, tip_text1, tip_text2 from tip where tip_vid <= (select max(dbversion_id) from dbversion) and tip_vid > " . $last_loaded;
		$result = mysql_query($t_query);
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$retval .= "tip_type&&{$row['tip_id']}&&{$row['tip_display_order']}&&{$row['tip_heading']}&&{$row['tip_text1']}&&{$row['tip_text2']}|";
		}
		
		$retval = trim($retval, "|");
		echo $retval;
	}	
?>