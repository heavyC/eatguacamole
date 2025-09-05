<?php
## DEBUG lines
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	include('admin-header.php');
?>

<div class="formDiv">
<h2>Database Release</h2>

<?php
	if (isset($_GET['release_id'])) {
		// insert new version
		$insert_sql = 'insert into dbversion (dbversion_comment, dbversion_date) values ( "'. $_GET['comments'] .'", curdate())';
		echo "insert SQL:<br>$insert_sql<br>";
		$result = mysql_query($insert_sql);
		if (!$result) {
			$message  = 'Invalid query: ' . mysql_error() . "<br />\n" . $insert_sql . "<br />\n";
			die($message);
		}
		
		echo "New version successfully created!<br /><hr>";
	}
	else {
		// form
		$cur_com = "";
		$cur_rd = "";
		$cur_vid = 0;
		$new_vid = 0;
		
		$select_sql = "select dbversion_id as vid, DATE_FORMAT(dbversion_date, '%M %d, %Y') as rd, dbversion_comment as com from dbversion where dbversion_id = (select max(dbversion_id) from dbversion)";
echo "sel SQL: ". $select_sql ."<br>";
		$result = mysql_query($select_sql);
		if ($row = mysql_fetch_assoc($result)) {
			if ($row["com"] != NULL)
				$cur_com = $row["com"];
			if ($row["rd"] != NULL)
				$cur_rd = $row["rd"];
			if ($row["vid"] != NULL) {
				$cur_vid = $row["vid"];
				$new_vid = $row["vid"] + 1;
			}
		}
		
		echo '<form method=GET action="release.php">';
		echo '<input type="hidden" name="release_id" value="'. $new_vid . '"  />'."\n";
		echo "<table border=1>"."\n";
		
		echo '<tr><td>Latest Release Version:</td><td>';
		echo '<input type="text" value="'. $cur_vid . '" disabled /></td></tr>'."\n";
		
		echo '<tr><td>Last Release Comments:</td><td>';
		echo '<textarea name="comments" disabled cols=40>'. $cur_com . '</textarea></td></tr>'."\n";
		
		echo '<tr><td>Last Release Date:</td><td>';
		echo '<input type="text" value="'. $cur_rd . '" disabled /></td></tr>'."\n";
		
		echo '<tr><td>Comments:</td><td> <textarea name="comments" cols=40></textarea></td></tr>'."\n";
		
		echo "</table>\n";
		
		echo '<input type=submit value="Create New Version" /><br>'."\n";
		echo '</form>'."\n";
		
	}
	
	include('../footer.php');
?>
</div><!-- needed??  -->

</div>
</body>
</html>