<?php
	$DEBUG = false;
	
	if ($DEBUG) ini_set('display_errors', 1);
	if ($DEBUG) error_reporting(E_ALL);
	
	include('admin-header.php');
?>

<div class="formDiv">

<?php
	$limit = 25; // Num of records to be shown per page.
	$start = 0;
	if (isset($_GET['start'])) {
		if (strlen($_GET['start']) > 0 and is_numeric($_GET['start'])) {
			$start = $_GET['start'];
		}
	}
	$thispage = $start + $limit;
	$back = $start - $limit;
	$nextpage = $start + $limit;
	
	if ($DEBUG) echo "start:$start<br>";
	if ($DEBUG) echo "thispage:$thispage<br>";
	if ($DEBUG) echo "back:$back<br>";
	if ($DEBUG) echo "nextpage:$nextpage<br>";
	
	
	
	
	$table_name = "college";
	echo '<form action="view.php" name="editForm">';
	echo '<input type="hidden" name="table_name" value="' . $table_name . '" />' . "\n";
	echo '<input type="hidden" name="edit_rows">' . "\n";
	
	// Generate SELECT
	$keys = array_keys($_GET);
		
	$table_select1 = "select college_id from college  ";
	$result1 = mysql_query($table_select1);		
	$num_rows_in_db = mysql_num_rows($result1);
	echo "Total records: $num_rows_in_db<br>";
	include('paging.php'); // requires $num_rows_in_db
	echo "<br><br><br>";

	if ($DEBUG) echo "<font size=-1>[query:  {$table_select1}]</font><p>\n";

	//$table_select = "select college_apl_id, college_name, apl_name from college_apl ca, 
	//	college c, apl a where ca.college_id = c.college_id and ca.apl_id = a.apl_id
	//	order by college_name limit $start, $limit ";
	$table_select = "select * from college order by college_name limit $start, $limit ";
	$result = mysql_query($table_select);
	
	if ($DEBUG) echo "<font size=-1>[query:  {$table_select}]</font><p>\n";
	
	echo '<input DISABLED type=button onClick="validateDelete()" name="delete_rows" value="Delete Selected Row" />';
	echo '<input type=button onClick="validateForm()"   name="edit_rows" value="Edit Selected Row" /> ';
	
	echo '<table border="1" cellpadding=5 cellspacing=0 width=4500>' . "\n";
	$headers = true;
	while ($row = mysql_fetch_assoc($result)) {
		// print headers
		if ($headers) {
			$keys = array_keys($row);
			echo "<tr><th></th>\n";
			foreach($keys as $col_title) {
				if (in_array($col_title, $skip_columns)) {
					continue;
				}
				$title = $column_titles["{$col_title}"];
				echo "<th align=left>{$title}</th>";
			}
			echo "</tr>\n";
			$headers = false;
		}
		echo "<tr>\n";
		$id = $table_name.'_id';
		
		
		// Admin login safety...
		echo '<td width=10><input type="radio" name="editId" value="datarow' . $row[$id] . '" /></td>';
		
		foreach($keys as $key) {
			if (in_array($key, $skip_columns)) {
				continue;
			}
			if (in_array($key, $combo_list)) {
				echo "<td>c{$row[$key]}</td>\n";
			}
			if ($key == "college_url") {
				echo "<td><a href=\"{$row[$key]}\" target=_new>{$row[$key]}</a></td>\n";
			}
			else {
				echo "<td>{$row[$key]}</td>\n";
			}
		}
		echo "</tr>\n";
	}
	echo "</table>\n";
	
	echo '<input DISABLED type=button onClick="validateDelete()" name="delete_rows" value="Delete Selected Rows" />';
	echo '<input type=button onClick="validateForm()"   name="edit_rows" value="Edit Selected Rows" />';
	echo "</form>\n";
	echo "<p>\n";
	
	include('paging.php');
	
	echo "</td></tr></table>";
?>

	<br>
	<div class="footerDiv">
		<script language="JavaScript" type="text/javaScript" src="http://www.EatGuacamole.com/copyright.js"></script>
	</div>
	
	</form>
	</div>

</div>
</body>
</html>
