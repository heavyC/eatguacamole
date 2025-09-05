<div class="insertForm">
<form method="POST" action="insert.php" name="insertAplMap">
<fieldset>
	<?php
	echo '<input type="hidden" name="college_apl_vid" value='. getNewVID() .' />' . "\n";
	?>
	<input type="hidden" name="table_name" value="college_apl" />
	<input type="hidden" name="formName" value="insertAplMap" />
	<legend>add College to ApplicationType Mapping</legend>
	
	<table border=1 cellpadding=5 cellspacing=0>
		<tr>
			<td><label for="college_id" id="college_id_label">College</label></td>
			<td><label for="apl_id" id="apl_id_label">Application Type</label></td>
		</tr>
		<tr>
			<td valign="top">
<?php
	$tt_query = "select college_id, college_name from college order by UPPER(college_name) asc";
	$tt_result = mysql_query($tt_query);
	echo '<select name="college_id" id="college_id"> \n';
	echo '<option value="">Select...</option>';
	while ($row = mysql_fetch_assoc($tt_result)) {
		echo "<option value=\"{$row['college_id']}\">${row['college_name']} (${row['college_id']})</option>\n";
	}
	echo "</select>";
?>
			</td>
			<td valign="top">
<?php
	$tt_query = "select apl_id, apl_name from apl order by UPPER(apl_name) asc";
	$tt_result = mysql_query($tt_query);
	echo '<select name="apl_id" id="apl_id"> \n';
	echo '<option value="">Select...</option>';
	while ($row = mysql_fetch_assoc($tt_result)) {
		echo "<option value=\"{$row['apl_id']}\">${row['apl_name']} (${row['apl_id']})</option>\n";
	}
	echo "</select>";
?>
			</td>
		</tr>
	</table>
	
	<input type="button" onClick="insertAplMap.submit()" value="Add Mapping" />
</fieldset>
</form>
</div>