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

		echo "<br><font size=-1>sql: {$update}</font><br>";

		$result = mysql_query($update);
		if (!$result) {
			$message  = 'Invalid query: ' . mysql_error() . "<br />SQL: " . $update . "<br />";
			die($message);
		}
		echo "<b>Success!</b><br />";
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
				if ($key == "place_type") {
					echo '<td><select name="place_type" />';
					echo '<option value="">Select...</option>';
					foreach ($place_types as $type) {
						if ($type[0] == $row[$key])
							echo '<option value="'. $type[0] .'" SELECTED>'. $type[1] .'</option>';
						else
							echo '<option value="'. $type[0] .'">'. $type[1] .'</option>';
					}
					echo '</select></td>';
				}
				else if ($key == "category") {
					echo '<td><select name="category" />';
					echo '<option value="">Select...</option>';
					foreach ($categories as $cat) {
						if ($cat[0] == $row[$key])
							echo '<option value="'. $cat[0] .'" SELECTED>'. $cat[1] .'</option>';
						else
							echo '<option value="'. $cat[0] .'">'. $cat[1] .'</option>';
					}
					echo '</select></td>';
				}
				else if (in_array($key, $combo_list)) {
					$str = parse_id_name($_GET['table_name'], $key);
					echo "<td>";
					echo create_combo_from_sql("offer", "offer_parent_id", "select offer_id, offer_title from offer order by offer_title asc", "No Parent", "", $row[$key]);
					echo "</td><script>\n";
					//select_combo_by_value("edit_rows", $key, $row[$key]);
					echo "</script>\n";
				}
				else { // text field
					// test for readonly
					if (in_array($key, $readonly_fields)) {
						echo "<td>{$row[$key]}";
						echo '<input type="hidden" name="'.$key.'" value="'.$row[$key]."\" /></td>\n";
					}
					else if ($key == 'offer_desc') {
						echo '<td>';
						echo '<textarea cols=50 rows=3 name="'.$key.'">';
						echo $row[$key];
						echo '</textarea>';
						echo "</td>\n";
					}
					else {
						echo '<td><input type="text" name="'.$key.'" value="'.$row[$key]."\" /></td>\n";
					}
				}
			}
			echo "</tr>\n";
		}
		echo "</table>\n";
		echo '<input type="submit" name="edit_submit" value="Submit Changes">';
		echo '</form>' . "\n";

		echo '<form action="view.php"><input type="submit" value="Cancel" /></form>';
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
		echo "<font size=-1>delete sql: {$delete_query}</font><br>\n";

		$result = mysql_query($delete_query);
		if (!$result) {
			// TODO: transaction rollback
			$message  = 'Invalid query: ' . mysql_error() . "<br />\n" . $delete_query . "<br />\n";
			die($message);
		}

		echo "<b>Success!</b><br />";
	}
	else if (isset($_GET['view_table'])) {
		// DELETE form
		$table_select = "select * from " . $_GET['table_name'] . " order by offer_id desc";
		echo "<br><font size=-1>[sql: {$table_select}]</font><br><br>\n";

		echo '<form action="view.php" name="myform">' ."\n";
		echo '<input type=submit name="delete_rows" value="Delete Selected Row" onClick="return confirmSubmit()" />' ."\n";
		echo '<input type=submit name="edit_rows" value="Edit Selected Row" />' ."\n";
		echo '<input type="hidden" name="table_name" value="' . $_GET['table_name'] . '" />' . "\n";

		// Generate SELECT
		$keys = array_keys($_GET);

		$result = mysql_query($table_select);
		echo '<table border="1" cellpadding=4 cellspacing=0>' . "\n";
		$headers = true;
		while ($row = mysql_fetch_assoc($result)) {
			// print headers
			if ($headers) {
				$keys = array_keys($row);
				echo "<tr><th></th>\n";
				foreach($keys as $col_title) {
					if ($col_title == "offer_parent_id")
						echo "<th>parent</th>";
					else
						echo "<th>{$col_title}</th>";
				}
				echo "</tr>\n";
				$headers = false;
			}
			echo "<tr>\n";
			$id = $_GET['table_name'].'_id';

			//echo '<td><input type="radio" name="datarow' . $row[$id] . '" value="' . $row[$id] . '" /></td>';
			echo '<td><input type="radio" name="datarow" value="' . $row[$id] . '" /></td>';
			foreach($keys as $key) {
				if ($key == "offer_desc") {
					echo "<td><pre width=40>";
					echo $row[$key];
					echo "</pre></td>\n";
				}
				else {
					echo "<td nowrap>{$row[$key]}</td>\n";
				}
			}
			echo "</tr>\n";
		}
		echo "</table>\n";
		echo '<input type=submit name="delete_rows" value="Delete Selected Row" onClick="return confirmSubmit()" />';
		echo '<input type=submit name="edit_rows" value="Edit Selected Row" />';
		echo "</form><p>\n";
	}
?>
	</form>
	</div>

</div>
</body>
</html>
