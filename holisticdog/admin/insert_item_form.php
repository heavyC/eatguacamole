
<div class="insertForm">
<form method="POST" action="insert.php" name="createItem">
	<input type="hidden" name="table_name" value="offer" />
	<input type="hidden" name="formName" value="createItem" />

<fieldset>
<legend>Add an Item</legend>

	<table border=0 cellpadding=2 cellspacing=0>
		<tr><td>
			<label for="offer_title" id="offer_title_label"><b>Title/Name (reqd):</b></label>
		</td><td>
			<input type="text" value="" size="50" maxlength="100" name="offer_title" id="offer_title" /><br>
		</td></tr>

		<tr><td>SubTitle:</td><td>
		<input type="text" value="" size="90" maxlength="100" name="offer_subtitle" />
		</td></tr>

		<tr><td>Description:</td><td>

		<textarea cols=90 rows=5  maxlength="1000" name="offer_desc" /></textarea>
		</td></tr>

		<tr><td>
			<b>Category (reqd):</b>
		</td><td>
			<?php
			echo '<select name="category" id="category" />' . "\n";
			echo '<option value="">Select...</option>';
			foreach ($categories as $cat) {
				echo '<option value="'. $cat[0] .'">'. $cat[1] . "</option> \n";
			}
			echo "</select>\n";
			?>
		</td></tr>
		<tr><td>
			<b>Parent (optional):</b>
		</td><td>
			<select id="offer_parent_id" name="offer_parent_id" />
				<option value="">Select...</option>
				<?php
					//$select = "select offer_title, offer_subtitle, offer_id from offer where offer_parent_id is NULL";
					$select = "select offer_title, offer_subtitle, offer_id from offer order by offer_title asc";
					$result = mysql_query($select);
					while ($row = mysql_fetch_assoc($result)) {
						echo '<option value="'.$row["offer_id"].'">'.$row["offer_title"].' - '.$row["offer_subtitle"].  " (id:" . $row["offer_id"]. ")" .  '</option>';
					}
				?>
			</select>

<?php
	$children = 0;
	$no_children = array();

	if (isset($_GET['parent_cat_id'])) {
		echo "<!-----------------------------------------------------------------> \n";
		//echo "<br>Entered parent id ". $_GET['parent_cat_id']. "<br>\n";
		echo '<script language="javascript">' . "\n";
		echo 'document.getElementById("offer_parent_id").value = ' . $_GET['parent_cat_id'] . ";\n";
		echo 'document.getElementById("offer_parent_id").disabled = true' . ";\n";
		echo '</script>';
	}
?>

		</td></tr>
	</table>
</fieldset>

<p>
<!--
<fieldset>
<legend>Parent (Optional)</legend>
	<select name="offer_parent_id" />
		<option value="">Select...</option>
<?php
	//$select = "select offer_title, offer_subtitle, offer_id from offer where offer_parent_id is NULL";
	$select = "select offer_title, offer_subtitle, offer_id from offer order by offer_title asc";
	$result = mysql_query($select);
	while ($row = mysql_fetch_assoc($result)) {
		echo '<option value="'.$row["offer_id"].'">'.$row["offer_title"].' - '.$row["offer_subtitle"].  " (id:" . $row["offer_id"]. ")" .  '</option>';
	}
?>
	</select>
</fieldset>
-->
<p>

<fieldset>
<legend>(Optional - for business  or location only)</legend>
	<table border=0	 cellpadding=2 cellspacing=0>
		<tr><td>Place Type:</td><td>
			<?php
			echo '<select name="place_type" />' . "\n";
			echo '<option value="">Select...</option>';
			foreach ($place_types as $type) {
				echo '<option value="'. $type[0] .'">'. $type[1] ."</option>\n";
			}
			echo "</select> \n";
			?>
		</td></tr>

		<tr><td align=right>Address:</td><td> <input type="text" value="" size="60" maxlength="100" name="place_address" /><br></td></tr>
		<tr><td align=right>City:</td><td> <input type="text" value="" size="30" maxlength="100" name="place_city" />
		&nbsp;State: <input type="text" value="" size="5" maxlength="100" name="place_state" />
		&nbsp;ZIP: <input type="text" value="" size="9" maxlength="100" name="place_zip" /></td></tr>
		<tr><td align=right>Phone:</td><td> <input type="text" value="" size="60" maxlength="100" name="place_phone" /></td></tr>
		<tr><td align=right>URL:</td><td> <input type="text" value="" size="60" maxlength="100" name="place_url" /></td></tr>
		<tr><td align=right>Image (url):</td><td> <input type="text" value="" size="60" maxlength="100" name="place_image" /></td></tr>
	</table>
</fieldset>
<p>



<p>
<input type="submit" value="Submit" name="submit" onClick="return confirmAdd()" />

</form>
</div>