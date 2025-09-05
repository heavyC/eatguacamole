<div class="insertForm">
<form method="POST" action="insert.php" name="insertCollege">
<fieldset>
	<?php
	echo '<input type="hidden" name="college_vid" value='. getNewVID() .' />' . "\n";
	?>
	<input type="hidden" name="table_name" value="college" />
	<input type="hidden" name="formName" value="insertCollege" />
	<legend>Add New School</legend>
	
	<table border=1 cellpadding=5 cellspacing=0>
		<tr>
			<td><label for="college_name" id="college_name_label">Name</label></td>
			<td>Type</td>
			<td>URL</td>
			<td>Location</td>
			<td>Appl Deadline</td>
			<td>Appl Fees</td>
			<td>Essay</td>
		</tr>
		<tr>
			<td valign="top"><input type="text" name="college_name" size="35"></td>
			<td valign="top">
				<textarea cols="30" rows="3" maxlength="2500" name="college_type"></textarea><br>
			</td>
			<td valign="top"><input type="text" name="college_url" size="35"></td>
			<td valign="top"><input type="text" name="college_location" size="25"></td>
			<td valign="top">
				<textarea cols="40" rows="4" maxlength="840" name="college_deadline"></textarea><br>
			</td>
			<td valign="top"><input type="text" name="college_fee" size="25"></td>
			<td valign="top">
				<textarea cols="50" rows="7" maxlength="5400" name="college_essay"></textarea><br>
			</td>
		</tr>
	</table>
	
	<input type="button" onClick="insertCollege.submit()" value="Add School" />
</fieldset>
</form>
</div>