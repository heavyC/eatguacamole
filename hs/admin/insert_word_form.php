
<div class="insertForm">
<form method="POST" action="insert.php" name="createWord">
<fieldset>
	<?php
	echo '<input type="hidden" name="word_vid" value='. getNewVID() .' />' . "\n";
	?>
	<input type="hidden" name="table_name" value="word" />
	<input type="hidden" name="formName" value="createShow" />
	<legend>Add New Word</legend>
	
	<label for="english" id="english_label">word (english):</label>
	<input type="text" value="" size="30" maxlength="100" name="english" id="english" />
	literal text (optional):<input type="text" value="" size="30" maxlength="100" name="literal" /><br>
	spanish (optional):<input type="text" value="" size="30" maxlength="100" name="spanish" /><br>
<?php
	$cat_query = "select * from cat";
	echo create_combo_by_tablename("word", "cat", "Category...", "Select Category");
?>
	<br />
	<input type="submit" value="Add Word" name="submit" />
</fieldset>
</form>
</div>