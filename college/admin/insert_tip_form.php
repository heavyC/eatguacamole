<div class="insertForm">
<form method="POST" action="insert.php" name="insertTip">
<fieldset>
	<?php
	echo '<input type="hidden" name="tip_vid" value='. getNewVID() .' />' . "\n";
	?>
	<input type="hidden" name="table_name" value="tip" />
	<input type="hidden" name="formName" value="insertTip" />
	<legend>Add New Tip</legend>
	
	<!--
	<label for="tip_heading" id="tip_heading_label">Heading:</label>
	<input type="text" value="" size="20" maxlength="100" name="tip_heading" id="tip_heading" /><br>
	-->
	<table border=1 cellpadding=5 cellspacing=0>
		<tr>
			<td><label for="tip_type_id" id="tip_type_id_label">Tip Group</label></td>
			<!--<td>Heading</td>-->
			<td><label for="tip_text1" id="tip_text1_label">Text 1</label></td>
			<!--<td>Text 2</td>-->
		</tr>
		<tr>
			<td valign="top">
<?php
	$tt_query = "select * from tip_type";
	$tt_result = mysql_query($tt_query);
	echo '<select name="tip_type_id" id="tip_type_id"> \n';
	echo '<option value="">Select...</option>';
	while ($row = mysql_fetch_assoc($tt_result)) {
		echo "<option value=\"{$row['tip_type_id']}\">${row['tip_type_title']} (${row['tip_type_id']})</option>\n";
	}
	echo "</select>";
?>
			</td>
			<!--
			<td valign="top">
				<textarea cols="30" rows="3" maxlength="400"  name="tip_heading"></textarea><br>
			</td>
			-->
			<td valign="top">
				<textarea cols="30" rows="5" maxlength="2500" name="tip_text1"></textarea><br>
			</td>
			<!--
			<td valign="top">
				<textarea cols="30" rows="5" maxlength="1000" name="tip_text2"></textarea><br>
			</td>
			-->
		</tr>
	</table>
	
	<input type="button" onClick="insertTip.submit()" value="Add Tip" />
</fieldset>
</form>
</div>