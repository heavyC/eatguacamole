<?php
## DEBUG lines
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	
	include('admin-header.php');
?>

<div class="formDiv">

<?php
	if (isset($_GET['edit_submit'])) {
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
					else
						$update .= $key . " = '" .$_GET[$key]. "', ";
			}
		}
		$update = trim($update, ", ");
		$idstr = $_GET['table_name']."_id";
		$update .= " where {$idstr}=" . $_GET[$idstr];
		
		echo "<br>SQL: {$update}";
		echo "<hr>";
		
		$result = mysql_query($update);
		if (!$result) {
			$message  = 'Invalid query: ' . mysql_error() . "<br />SQL: " . $update . "<br />\n";
			die($message);
		}
		echo "Update succeeded!<br />";
	}
	else if (isset($_GET['edit_rows'])) {
		$idlist = "";
		foreach (array_keys($_GET) as $getkey) {
			if (substr($getkey, 0, 7) == "datarow") {
				$idlist .= $_GET[$getkey];
				$idlist .= ", ";
			}
		}
		$idlist = trim($idlist, ", ");
		$select = "select * from " . $_GET['table_name'] . " where " . $_GET['table_name'] . "_id in ({$idlist})";
		echo "SQL: {$select}<br>\n\n";
		$result = mysql_query($select);
		echo '<form action="view.php" name="edit_rows">' . "\n";
		echo '<input type="hidden" name="table_name" value="'. $_GET['table_name'] .'">' . "\n";
		echo '<table border="1" cellspacing="0" cellpadding="2">' . "\n";
		$headers = true;
		while ($row = mysql_fetch_assoc($result)) {
			// print header
			if ($headers) {
				// headers
				$keys = array_keys($row);
				echo "<tr>\n";
				foreach($keys as $col_title) {
					echo "<th>{$col_title}</th>";
				}
				echo "</tr>\n";
				$headers = false;
			}
			echo "<tr>\n";
			$id = $_GET['table_name'].'_id';
			foreach($keys as $key) {
				if ($key == "is_visible") {
					// special case
					echo '<td><select name="is_visible"><option value="1">Display</option><option value="0">Hide</option></select></td>';
					echo "<script>\n";
					select_combo_by_value("edit_rows", $key, $row[$key]);
					echo "</script>\n";
				}
				else if (in_array($key, $combo_list)) {
					$str = parse_id_name($_GET['table_name'], $key);
					echo "<td>";
					echo create_combo_by_tablename($_GET['table_name'], parse_id_name($_GET['table_name'], $key), "No Parent", "");
					echo "</td><script>\n";
					select_combo_by_value("edit_rows", $key, $row[$key]);
					echo "</script>\n";
				}
				else { // text field
					// test for readonly
					if (in_array($key, $readonly_fields)) {
						echo "<td>{$row[$key]}";
						echo '<input type="hidden" name="'.$key.'" value="'.$row[$key]."\" /></td>\n";
					}
					else {
						echo '<td><input type="text" name="'.$key.'" value="'.$row[$key]."\" /></td>\n";
					}
				}
			}
			echo "</tr>\n";
		}
		echo "</table><hr>\n";
		echo '<input type="submit" name="edit_submit" value="Submit Changes">';
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
		// Add row to DBDELETE table to record the update
		if ($tablename != "dbdelete") {
			$insert_del = 'insert into dbdelete (del_table, del_rowid, del_vid) values ("'. $tablename .'", '. $idlist .', ' . getNewVID() . ') ';
			echo "add delete: {$insert_del}<br>";
			$result = mysql_query($insert_del);
			if (!$result) {
				$message  = 'Invalid query: ' . mysql_error() . "<br />\n". 'Whole query: ' . $delete_query . "<br />\n";
				die($message);
			}
		}
		
		//foreach (array_keys($_GET) as $getkey) {
		//	if (substr($getkey, 0, 7) == "datarow") {
		//		$idlist .= $_GET[$getkey];
		//		$idlist .= ", ";
		//	}
		//}
		$idlist = trim($idlist, ", ");
		$delete_query = "delete from {$tablename} where {$tablename}_id in ({$idlist})";
		echo "DELETE:<br>{$delete_query}<br><hr>\n\n";
		
		$result = mysql_query($delete_query);
		if (!$result) {
			// TODO: transaction rollback
			$message  = 'Invalid query: ' . mysql_error() . "<br />\n" . $delete_query . "<br />\n";
			die($message);
		}
		
		echo "Delete succeeded!<br />";
	}
	else if (isset($_GET['view_table'])) {
		// DELETE form
		echo '<form action="view.php" name="myform">' ."\n";
		echo '<input type=submit name="delete_rows" value="Delete Selected Rows" />' ."\n";
		echo '<input type=submit name="edit_rows" value="Edit Selected Rows" />' ."\n";
		echo '<input type="hidden" name="table_name" value="' . $_GET['table_name'] . '" />' . "\n";
		
		// Generate SELECT
		$keys = array_keys($_GET);
		$table_select = "select * from " . $_GET['table_name'];
		if ($_GET['table_name'] == "word")
			$table_select .= " order by english";
		if ($_GET['table_name'] == "cat")
			$table_select .= " order by cat_name";
		
		echo "<br>SQL: {$table_select}<br>\n";
			
		$result = mysql_query($table_select);
		echo '<table border="1">' . "\n";
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
			
//			echo '<td><input type="radio" name="datarow' . $row[$id] . '" value="' . $row[$id] . '" /></td>';
			echo '<td><input type="radio" name="datarow" value="' . $row[$id] . '" /></td>';
			
			foreach($keys as $key) {
				if (in_array($key, $combo_list)) {
					echo "<td>c{$row[$key]}</td>\n";
				}
				else {
					echo "<td>{$row[$key]}</td>\n";
				}
			}
			echo "</tr>\n";
		}
		echo "</table>\n";
		echo '<input type=submit name="delete_rows" value="Delete Selected Rows" />';
		echo '<input type=submit name="edit_rows" value="Edit Selected Rows" />';
		echo "</form><p>\n";
	}

	include('../footer.php');
?>	
	</form>
	</div>

</div>
</body>
</html>
