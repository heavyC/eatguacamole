<?php
	function getNewVID() {
		$id = 0;
		$id_sql = "select max(dbversion_id) as maxid from dbversion";
		$result = mysql_query($id_sql);
		if (!$result) {
			$message  = 'Invalid query: ' . mysql_error() . "<br />\n" . $id_sql . "<br />\n";
			die($message);
		}
		if ($row = mysql_fetch_assoc($result)) {
			$id = $row["maxid"];
		}
		return $id+1;
	}
	
	
	
	function getExt($str) {
		$i = strrpos($str,".");
		if (!$i) { return ""; } 
		$l = strlen($str) - $i;
		$ext = substr($str,$i+1,$l);
		return $ext;
 	}
	function getBase($str) {
		$i = strrpos($str,".");
		if (!$i) { return ""; }
		$base = substr($str,0,$i);
		return $base;
 	}

	function test_required($req_list, $inputs, $rfiles, $inputfiles, $combo_list) {
		$retval = array();
		foreach ($req_list as $req) {
			$val = $inputs[$req];
			if ((in_array($req, $combo_list)) && (is_null($val))) {
				array_push($retval, $req);
			}
			else if (($val == NULL) || (strlen($val) == 0)) {
				array_push($retval, $req);
			}
		}
		foreach ($rfiles as $fname) {
			$val = $inputfiles[$fname];
			if (is_null($val)) {
				array_push($retval, $fname);
			}
		}
		return $retval;
	}
	
	function populate_form_values($values_array, $combo_list) {
		$form_name = $values_array['formName'];
		echo "<script>\n";
		foreach (array_keys($values_array) as $key) {
			if (in_array($key, $combo_list)) {
				//echo "document.{$values_array['formName']}.{$key}.value='{$values_array[$key]}';\n";
				select_combo_by_value($form_name, $key, $values_array[$key]);
			}
			else {
				echo "document.{$values_array['formName']}.{$key}.value='{$values_array[$key]}';\n";
				// text field
			}
		}
		echo "</script>\n";
	}
	
	// TODO: select the element by FORM name/FIELD name
	function show_failed_form_fields($form_name, $failed_fields, $combo_list) {
		echo "<script>\n";
		foreach ($failed_fields as $failed) {
			if (in_array($failed, $combo_list)) {
				// combo box
				echo "document.getElementById('{$failed}_label').style.color='red';\n";
			}
			else {
				// text field
				echo "document.getElementById('{$failed}_label').style.color='red';\n";
			}
		}
		echo "</script>\n";
	}
	
	
	function select_combo_by_value($form, $combo_name, $value) {
		if ((is_null($value)) || ($value == "")) {
			return;
		}
		echo "for (var i=0; i < document.{$form}.{$combo_name}.length; i++) {";
		echo "	if (document.{$form}.{$combo_name}[i].value == {$value}) {";
		echo "		document.{$form}.{$combo_name}[i].selected = true;";
		echo "	}";
		echo "}";
	}
	
	function create_combo_by_tablename($sourcetable, $targettable, $intro_text, $label_text) {
		$retval = "";
		//echo $sourcetable . "::";
		//echo $targettable . "::";
		//echo $intro_text . "<br>";
		
		$query = "select * from {$targettable}";
		$result = mysql_query($query);
		if ($sourcetable != null)
			$val = $sourcetable . "_" .$targettable;
		else
			$val = $targettable;
		
		
		$retval .= '<label for="' . $val .'_id" id="' . $val .'_id_label">'.$label_text.'</label>';
		$retval .=  '<select name="' . $val .'_id" id="'. $val .'_id">';
		$retval .=  '<option value="-1">' . $intro_text . '</option>';
		while ($row = mysql_fetch_assoc($result)) {
			$id = $row[$targettable . '_id'];
			$name  = $row[$targettable . '_name'];
			//$name2 = $row[$targettable . '_name2'];
			$retval .=  "<option value=\"{$id}\">{$name} ({$id})</option>\n";
		}
		$retval .=  '</select>';
		
		return $retval;
	}
	
	function parse_id_name($prefix, $str) {
		//$str = str_replace($prefix, "", $str);
		$str = preg_replace("/{$prefix}/", "", $str, 1);
		$str = str_replace("id", "", $str);
		$str = str_replace("_", "", $str);
		return $str;
	}
	
?>