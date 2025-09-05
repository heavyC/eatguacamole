<?php
	$DEBUG = false;
	
	if ($DEBUG) ini_set('display_errors', 1);
	if ($DEBUG) error_reporting(E_ALL);
	
	include('admin-header.php');
?>

<div class="formDiv">

<?php
	if (isset($_GET['insert_row'])) {
		if ($_GET['table_name'] == "tip") {
			echo 'tip...';
			include("insert_tip_form.php");
		}
		else if ($_GET['table_name'] == "tip_type") {
			echo 'tipType...';
			include("insert_tiptype_form.php");
		}
		else {
			echo "Cannot insert into table ".$_GET['table_name'];
		}
	}
	else if (isset($_GET['edit_submit'])) {
		$update = "update ".$_GET['table_name']." set ";
		foreach (array_keys($_GET) as $key) {
			switch ($key) {
				case "table_name":
					break;
				case "edit_submit":
					break;
				case $_GET['table_name']."_id":
					break;
				default:
					if (in_array($key, $numeric_fields)) {
						if (strlen($_GET[$key]) == 0)
							$update .= $key . " = NULL, ";
						else
							$update .= $key . " = " .$_GET[$key]. ", ";
					}
					else {
						$update .= $key . " = '" .$_GET[$key]. "', ";
					}
			}
		}
		$update = trim($update, ", ");
		$idstr = $_GET['table_name']."_id";
		$update .= " where {$idstr}=" . $_GET[$idstr];
		
		if ($DEBUG) echo "SQL: {$update} <hr>";
		
		$result = mysql_query($update);
		if (!$result) {
			$message  = 'Invalid query: ' . mysql_error() . "<br />\n";
			$message .= 'Whole query: ' . $update . "<br />\n";
			die($message);
		}
		echo "Update succeeded!<br />";
	}
	else if (isset($_GET['edit_rows'])) {
		$idlist = "";
		foreach (array_values($_GET) as $getkey) {
			if (substr($getkey, 0, 7) == "datarow") {
				$idlist .= substr($getkey, 7);
				$idlist .= ", ";
			}
		}
		$idlist = trim($idlist, ", ");
		$select = "select * from " . $_GET['table_name'] . " where " . $_GET['table_name'] . "_id in ({$idlist})";
		echo "SQL: {$select}<br>\n\n";
		$result = mysql_query($select);
		echo '<form action="view.php" name="edit_rows">' . "\n";
		echo '<input type="hidden" name="table_name" value="'. $_GET['table_name'] .'">' . "\n";
		
		// updated Version ID for the row
		echo '<input type="hidden" name="'. $_GET['table_name'] .'_vid" value="'. getNewVID() .'">' . "\n";		
		
		
		echo '<table width=4000 border="1" cellspacing="0" cellpadding="3">' . "\n";
		$headers = true;
		while ($row = mysql_fetch_assoc($result)) {
			// print header
			if ($headers) {
				$keys = array_keys($row);
				echo "<tr>\n";
				foreach($keys as $col_title) {
					if (in_array($col_title, $skip_columns)) {
						continue;
					}
					echo "<th align=left>{$col_title}</th>";
				}
				echo "</tr>\n";
				$headers = false;
			}
			echo "<tr>\n";
			$id = $_GET['table_name'].'_id';
			foreach($keys as $key) {
				if (in_array($key, $skip_columns)) {
					continue;
				}
				if (in_array($key, $skip_edit_cols)) {
					continue;
				}
				
				if ($key == "is_visible") {
					// special case
					echo "<td valign=top>";
					echo '<select name="is_visible"><option value="1">Display</option><option value="0">Hide</option></select>';
					echo "</td>";
					echo "<script>\n";
					select_combo_by_value("edit_rows", $key, $row[$key]);
					echo "</script>\n";
				}
				else if (in_array($key, $combo_list)) {
					$str = parse_id_name($_GET['table_name'], $key);
					echo "<td valign=top>";
					echo create_combo_by_tablename($_GET['table_name'], parse_id_name($_GET['table_name'], $key), "No Parent", "");
					echo "</td>";
					echo "<script>\n";
					select_combo_by_value("edit_rows", $key, $row[$key]);
					echo "</script>\n";
				}
				else { // text field
					// test for readonly
					if (in_array($key, $readonly_fields)) {
						echo "<td valign=top>{$row[$key]}";
						echo '<input type="hidden" name="'.$key.'" value="'.$row[$key]."\" /></td>\n";
					}
					else {
						$len = strlen($row[$key]);
						$row_width = 10;
						if ($len > 10) {
							$row_width = 20;
						}
						if ($len > 20) {
							$row_width = 40;
						}
						if ($len > 300) {
							$row_width = 100;
						}
						$num_rows = max(1, intval($len/$row_width));
						
						echo '<td valign=top><textarea name="'.$key.'" rows='.$num_rows.' cols='.$row_width.'>'.$row[$key].'</textarea></td>';
					}
				}
			}
			echo "</tr>\n";
		}
		echo "</table><hr>\n";
		echo '<input type="submit" name="edit_submit" value="Submit Changes">';
		echo '<input type="button" value="Back" onClick="history.go(-1);return true;">';
		echo '<input type="button" name="Cancel" value="Cancel" onclick="window.location =\'.\' " /> ';
		echo '</form>' . "\n";
		
	}
	else if (isset($_GET['delete_rows'])) {
		// compile delete query
		$tablename = $_GET['table_name'];
		$idlist = "";
		foreach (array_keys($_GET) as $getkey) {
			if (substr($getkey, 0, 7) == "datarow") {
				$idlist .= $_GET[$getkey];
				$idlist .= ", ";
			}
		}
		$idlist = trim($idlist, ", ");
		$delete_query = "delete from {$tablename} where {$tablename}_id in ({$idlist})";
		echo "DELETE:<br>{$delete_query}<br><hr>\n\n";
		
		$result = mysql_query($delete_query);
		if (!$result) {
			$message  = 'Invalid query: ' . mysql_error() . "<br />\n";
			$message .= 'Whole query: ' . $delete_query . "<br />\n";
			die($message);
		}
		echo "Delete succeeded!<br />";
	}
	else if (isset($_GET['view_table'])) {
		echo '<form action="view.php" name="editForm">';
		//echo '<form action="view.php">';
		echo '<input type="hidden" name="table_name" value="' . $_GET['table_name'] . '" />' . "\n";
		echo '<input type="hidden" name="edit_rows">' . "\n";
		

		// Generate SELECT
		$keys = array_keys($_GET);
		
		
		$table_select = "";
		if ($_GET['table_name'] == "college_apl") {
			$table_select = "select college_apl_id, college_name, apl_name from college_apl ca, 
			college c, apl a where ca.college_id = c.college_id and ca.apl_id = a.apl_id
			order by college_name";
		}
		else {
			$table_select = "select * from " . $_GET['table_name'];
		}
		echo "<font size=-1>[query:  {$table_select}]</font><p>\n";
		$result = mysql_query($table_select);
		
		
		echo '<input DISABLED type=button onClick="validateDelete()" name="delete_rows" value="Delete Selected Rows" />';
		echo '<input type=button onClick="validateForm()" name="edit_rows" value="Edit Selected Rows" />';
		//echo '<input type="submit" name="insert_row" value="Insert Row" />';

		
		$table_width = 1000;
		if ($_GET['table_name'] == "college_apl")
			$table_width = 500;
		else if ($_GET['table_name'] == "apl")
			$table_width = 3000;
		else if ($_GET['table_name'] == "apl_type")
			$table_width = 2500;
		else if ($_GET['table_name'] == "tip")
			$table_width = 1000;
		else if ($_GET['table_name'] == "tip_type")
			$table_width = 700;
		
		
		echo "\n <table border=1 width={$table_width}> \n";
		$headers = true;
		while ($row = mysql_fetch_assoc($result)) {
			// print headers
			if ($headers) {
				$keys = array_keys($row);
				echo "<tr><th></th>\n";
				foreach($keys as $col_title) {
					echo "<th>{$col_title}</th>";
				}
				echo "</tr>\n";
				$headers = false;
			}
			echo "<tr>\n";
			$id = $_GET['table_name'].'_id';
			
			// Admin login safety...
			echo '<td width=10><input type="radio" name="editId" value="datarow' . $row[$id] . '" /></td>';
			
			foreach($keys as $key) {
				if (in_array($key, $combo_list)) {
					echo "<td width=100>{$row[$key]}</td>\n";
				}
				else {
					$len = strlen($row[$key]);
					$row_width = 10;
					if ($len > 10) {
						$row_width = 20;
					}
					if ($len > 20) {
						$row_width = 40;
					}
					if ($len > 300) {
						$row_width = 100;
					}
					
					echo "<td width={$len}>{$row[$key]}</td>\n";
				}
			}
			echo "</tr>\n";
		}
		echo "</table>\n";
		
		// Admin login safety...
		echo '<input DISABLED type=button onClick="validateDelete()" name="delete_rows" value="Delete Selected Rows" />';
		echo '<input type=button onClick="validateForm()" name="edit_rows" value="Edit Selected Rows" />';
		//echo '<input type="submit" name="insert_row" value="Insert Row" />';
		echo "</form>\n";
		echo "<p>\n";
	}
	
	
// NOTE: this requires information_schema tables to be exposed to HTTP server.
// Laughing Squid does not allow this.
/*
	// Tables available for display
	$table_query = "select table_name from information_schema.tables where table_schema='david_geiser' limit 0, 30 ";
	// Link to select statement
	$results = mysql_query($table_query);
	while ($row = mysql_fetch_assoc($results)) {
		echo "<a href=\"view.php?view_table={$row['table_name']}&table_name={$row['table_name']}\">{$row['table_name']}</a><br>\n";
	}
*/
/*
	// table listing is denied on Laughing Squid, so kludge this with array of known table names
	$tablenames = array("college", "apl", "adm", "college_apl", "tip_type", "tip", "intro");
	foreach ($tablenames as $tname) {
		echo "Database Table: <a href=\"view.php?view_table={$tname}&table_name={$tname}\">{$tname}</a><br>\n";
	}
*/
?>
	<div class="footerDiv">
		<script language="JavaScript" type="text/javaScript" src="http://www.EatGuacamole.com/copyright.js"></script>
	</div>
	
	</form>
	</div>

</div>
</body>
</html>
