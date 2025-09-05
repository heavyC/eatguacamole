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
			echo "<h2>Insert Failed</h2>";
		}
		else {
			// Generate Query
			$keys = array_keys($_POST);
			$insert = "insert into {$_POST['table_name']} (";
			$values = 'values (';
			
			foreach ($keys as &$key) {
				if (($key == 'submit') || ($key == 'table') || ($key == 'table_name') || ($key == 'formName'))
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
			//$insert .= "updated_date, created_date";
			//$values .= "curdate(), curdate()";
			$insert = trim($insert, ", ");
			$values = trim($values, ", ");
			
			$insert_query = $insert . ") " . $values . ")";	
			
			$result = mysql_query($insert_query);
			echo "insert SQL:<br>$insert_query<br>Result:$result<br>";
			if (!$result) {
				$message  = 'Invalid query: ' . mysql_error() . "<br />\n" . $insert_query . "<br />\n";
				die($message);
			}
			echo "Insert: success!<br /><hr>";
		} // else
	}
	
	// Forms
	include("insert_cat_form.php");
	include("insert_word_form.php");
	
	// Reset values on failed submit
	if (count($failed_fields) > 0) {
		populate_form_values($_POST, $combo_list);
		show_failed_form_fields($_POST['formName'], $failed_fields, $combo_list);
	}
	
	include('../footer.php');
?>
</div>
</body>
</html>
