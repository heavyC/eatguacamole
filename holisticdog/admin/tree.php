<?php
## DEBUG lines
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	include('admin-header.php');
?>

<head>
	<title>Tree Menu</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<style>
		li{
			list-style:none;
			/*color:blue;*/
			color:black;
		}
	</style>

	<script language="javascript">
	function show(subCategory, path) {
		var thesub;
		var theimage;
		var imageid;
		var testingdiv;

		thesub = document.getElementById(subCategory);
		imageid = "img_"+subCategory;
		theimage = document.getElementById(imageid);
		testingdiv = document.getElementById("testing");

		if (thesub.style.display == 'block'){
			//collapse...
			thesub.style.display = 'none';
			theimage.src = path+'/images/c.gif';
		}
		else {
			//expand/display...
			thesub.style.display = 'block';
			theimage.src = path+'/images/e.gif';
		}
	}
	</script>
</head>
<body onLoad="nokids();">
<b>
To edit the Category, click on the link text.<br>
To expand or collapse, click the <img src="images/c.gif"> and <img src="images/e.gif"> buttons to the left of the category name.<br>
</b>

<br><br>

<?php
	$children = 0;
	$no_children = array();

	if (isset($_GET['cat'])) {
		echo "Category: <b>". $_GET['cat'] ."</b><p>";
	}
	else {
		echo "Category: <b>All</b><p>";
	}
// $parent is the parent of the children we want to see
// $level is increased when we go deeper into the tree, used to display a nice indented tree
function display_children($parent, $level) {
	global $children;
	global $no_children;

	// retrieve all children of $parent
	$sql = "SELECT offer_title, offer_id, offer_subtitle, category FROM offer";
	if ($level == 0) {
		$sql = $sql . " WHERE offer_parent_id is NULL";
	}
	else {
		$sql = $sql . " WHERE offer_parent_id = " . $parent;
	}
	if (isset($_GET['cat'])) {
		$sql = $sql . " and category = \"". $_GET['cat'] ."\"";
	}
	$sql = $sql . " order by category";
	$result = mysql_query($sql);

	//if this is a sub category nest the list...
	if($level > 0) {
		echo "<ul id='$parent' style='display:none;'>\n";
	}

	$list_id='';
	// display each child
	while ($row = mysql_fetch_array($result)) {
		$children++;
		$list_id = 'list_'.$children;

		//display each child
		echo '<li id="'.$list_id.'">';
		echo '<a href="#" onClick="show('. $row['offer_id'] .', \''.HTTP_PATH.'\')">';
		echo '<img src="'.HTTP_PATH.'/images/c.gif" title="expand" border="0" id="img_'.$list_id.'"></a>  ';

		//echo $row['offer_title'] ." [".$list_id."]";
		echo $row['offer_title'];
		//echo '<a href="view.php?edit_rows=Edit+Selected+Row&table_name=offer&datarow='. $row['offer_id'] .'" title="Edit This Category">';
		echo '&nbsp;&nbsp;<a href="view.php?edit_rows=Edit+Selected+Row&table_name=offer&datarow='. $row['offer_id'] .'" title="Edit This Category">';
		echo "Edit</a> &nbsp; ";
		//echo " (" . $row['category'] . ") ";
		echo '<a href="insert.php?parent_cat_id='. $row['offer_id'] .'">Add Item to this Category (id: '. $row['offer_id'] .')</a>';

		echo '</li>';

		// call this function again to display this child's children
		display_children($row['offer_id'], $level+1);
	}

	//if this is a sub offer nest the list...
	if($level > 0) {
		echo "</ul>\n";
	}
	$no_children[] =  'list_'.$children;
}

echo "<ul style='list-style:none;'>";
display_children('',0);
echo "</ul>";

?>
<!--<div style="display:block; border: 1px solid red;" id="testing"></div>-->

<script language="javascript" type="text/javascript">
function nokids(){
	var kidnot;
	kidnot = new Array();
	<?php
		for($i=0; $i<count($no_children); $i++){
			print("kidnot.push(\"".$no_children[$i]."\");\n");
		}
	?>

	for(i in kidnot){
		var theid;
		theid = kidnot[i];
		//document.getElementById("testing").innerHTML += theid+", ";
		document.getElementById(kidnot[i]).style.color = "#333";
		document.getElementById('img_'.concat(kidnot[i])).src = "./images/ats.gif";
		//document.getElementById('img_'.concat(kidnot[i])).src = "./images/l-item.gif";
	}
}
</script>

</body>
</html>
