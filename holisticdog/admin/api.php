<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	require_once("includes/constants.php");
	require_once("includes/session.php");
	require_once("includes/connection.php");
	require_once("includes/functions.php");
	require_once("includes/form_functions.php");

//	$query = "select * from offer ";
//	$query = "select *, (select count(*) from offer o2 where o1.offer_parent_id = o2.offer_id) as num_parents from offer o1  ";
	$query = "select *, (select count(*) from offer o2 where  o2.offer_parent_id = o1.offer_id) as num_children from offer o1  ";


	if (isset($_GET['parent_id'])) {
		$query .= " where offer_parent_id=" . $_GET['parent_id'];
	}
	else {
		$query .= " where offer_parent_id is NULL";
	}

	$retval = "{ \"status\":\"ok\", \"offers\": ";
	$retval .= sql2json($query);
	$retval .= "}";
	echo $retval;


	// Functions
	function sql2json($query) {
		$data_sql = mysql_query($query) or die("'';//" . mysql_error());// If an error has occurred,
				//    make the error a js comment so that a javascript error will NOT be invoked
		$json_str = ""; //Init the JSON string.

		if($total = mysql_num_rows($data_sql)) { //See if there is anything in the query
			$json_str .= "[\n";

			$row_count = 0;
			while($data = mysql_fetch_assoc($data_sql)) {
				if(count($data) > 1) $json_str .= "{\n";

				$count = 0;
				foreach($data as $key => $value) {
					//If it is an associative array we want it in the format of "key":"value"
					if(count($data) > 1) $json_str .= "\"$key\":\"$value\"";
					else $json_str .= "\"$value\"";

					//Make sure that the last item don't have a ',' (comma)
					$count++;
					if($count < count($data)) $json_str .= ",\n";
				}
				$row_count++;
				if(count($data) > 1) $json_str .= "}\n";

				//Make sure that the last item don't have a ',' (comma)
				if($row_count < $total) $json_str .= ",\n";
			}

			$json_str .= "]\n";
		}

		//Replace the '\n's - make it faster - but at the price of bad redability.
		$json_str = str_replace("\n","",$json_str); //Comment this out when you are debugging the script

		//Finally, output the data
		return $json_str;
	}

?>
























