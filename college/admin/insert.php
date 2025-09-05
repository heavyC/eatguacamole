<?php
## DEBUG lines
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	include('admin-header.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<div class="formDiv">

<?php
	if (isset($_POST['table_name'])) {
		$failed_fields = test_required($req_fields, $_POST, array(), $_FILES, $combo_list);
		if (count($failed_fields) > 0) {
			echo "<center>";
			echo "<h3>Insert Failed</h3>";
			echo '<b>One or more required fields were left blank.<br>They are marked <font color="red">Red</font> below:</b>';
			echo "</center><p>";
		}
		else {
			// File to be uploaded - get this first because we need the name of the file to
			// create the Query.
			if (isset($_FILES['imgFile'])) {
				$fname = basename( $_FILES['imgFile']['name']);			
				$base = getBase($fname);
				$ext = getExt($fname);
			}
			
			// Generate Query
			$keys = array_keys($_POST);
			$insert = "insert into {$_POST['table_name']} (";
			$values = 'values (';
			
			// add the file name
			if (isset($_FILES['imgFile'])) {
				$insert .= "image_file, ";
				$values .= "'{$fname}', ";
			}
			
			foreach ($keys as &$key) {
				if ($key == 'submit')
					continue;
				if ($key == 'table')
					continue;
				if ($key == 'table_name')
					continue;
				if ($key == 'formName')
					continue;
				
				// all clear, process
				$insert .= $key . ", ";
				// do not quote numeric values
				if (in_array($key, $numeric_fields)) {
					if (strlen($_POST[$key]) == 0)
						$values .= "NULL, ";
					else
						$values .= "$_POST[$key], ";
				}
				else {
					$values .= "'$_POST[$key]', ";
				}
			}
			$insert = trim($insert, ", ");
			$values = trim($values, ", ");
			$insert_query = $insert . ") " . $values . ")";
			echo "<font size=-1>[$insert_query]</font><br>";			
			$result = mysql_query($insert_query);
			if (!$result) {
				$message  = 'Invalid query: ' . mysql_error() . "<br />\n";
				$message .= 'Whole query: ' . $insert_query . "<br />\n";
				die($message);
			}
			echo "<h3 align=center>Insert Successful: 1 row added</h3>";
		}
	}
	
	
	
	// Forms
	include("insert_school_form.php");
	include("insert_aplmap_form.php");
	include("insert_tip_form.php");
	include("insert_tiptype_form.php");
	
	
	
	// Reset values on failed submit
	if (count($failed_fields) > 0) {
		// re-populate failed form
		populate_form_values($_POST, $combo_list);
		// Notify excluded fields
		show_failed_form_fields($_POST['formName'], $failed_fields, $combo_list);
	}
?>
<div class="footerDiv">
	<script language="JavaScript" type="text/javaScript" src="http://www.EatGuacamole.com/copyright.js"></script>
</div>
	
</div>
</body>
</html>
