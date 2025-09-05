
<div class="insertForm">
<form method="POST" action="insert.php" name="createCat">
<fieldset>
	<?php
	echo '<input type="hidden" name="cat_vid" value='. getNewVID() .' />' . "\n";
	?>
	<input type="hidden" name="table_name" value="cat" />
	<input type="hidden" name="formName" value="createCat" />
	<legend>Create Category</legend>
	
	<label for="cat_name" id="cat_name_label">title:</label>
	<input type="text" value="" size="20" maxlength="100" name="cat_name" id="cat_name" /><br>
	
	description: <input type="text" value="" size="30" maxlength="100" name="cat_desc" /><br>
	seq no:<input type="text" value="" size="6" maxlength="4" name="cat_seqno" /><br>
	</select>
	<br />
	<input type="submit" value="Add Category" name="submit" />
</fieldset>
</form>
</div>