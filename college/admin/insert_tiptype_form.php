<div class="insertForm">
<form method="POST" action="insert.php" name="insertTipType">
<fieldset>
	<?php
	echo '<input type="hidden" name="tip_type_vid" value='. getNewVID() .' />' . "\n";
	?>
	<input type="hidden" name="table_name" value="tip_type" />
	<input type="hidden" name="formName" value="insertTip" />
	<legend>Add New Tip Group</legend>
	
	<!--
	<label for="tip_heading" id="tip_heading_label">Heading:</label>
	<input type="text" value="" size="20" maxlength="100" name="tip_heading" id="tip_heading" /><br>
	-->
	<table border=1 cellpadding=5 cellspacing=0>
		<tr>
			<td><label for="tip_type_title" id="tip_type_title_label">Group Title</label></td>
			<td>Display Order</td>
		</tr>
		<tr>
			<td valign="top">
			<textarea cols="60" rows="1" maxlength="100" name="tip_type_title"></textarea><br>
			</td>
			<td valign="top">
				<select name="tip_type_display_order" id="tip_type_display_order">
					<option value="">Select...</option>
					<option value="1">1 (first)</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10 (last)</option>
				</select>
			</td>
		</tr>
	</table>
	
	<input type="button" onClick="insertTipType.submit()" value="Add Tip Group" />
</fieldset>
</form>
</div>