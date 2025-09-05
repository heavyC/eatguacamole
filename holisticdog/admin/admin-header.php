<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	require_once("includes/constants.php");
	require_once("includes/session.php");
	require_once("includes/connection.php");
	require_once("includes/functions.php");
	require_once("includes/form_functions.php");

	confirm_logged_in();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Holistic Dog admin</title>
    <link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
    <link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" type="text/css" href="../style.css" />
	<link rel="stylesheet" type="text/css" href="http://www.geiser.net/guac/style-copyright.css" />
</head>
<!-- style for View page -->
<style type="text/css">
	pre {
		white-space: pre-wrap; /* css-3 */
		white-space: -moz-pre-wrap; /* Mozilla, since 1999 */
		white-space: -pre-wrap; /* Opera 4-6 */
		white-space: -o-pre-wrap; /* Opera 7 */
		word-wrap: break-word; /* Internet Explorer 5.5+ */
	}
</style>
<body>

<script language="javaScript">
<!--
function confirmSubmit() {
	var conf = confirm("Delete this Item (this action cannot be undone)?");
	if (conf)
		return true ;
	else
		return false ;
}

function confirmAdd() {
	if (document.getElementById("offer_title").value == "") {
		document.getElementById('offer_title').focus()
		alert("Title cannot be empty.");
		return false;
	}
	if (document.getElementById("category").value == "") {
		document.getElementById('category').focus()
		alert("Category must be selected.");
		return false;
	}

}
// -->
</script>


<table border="0" width="100%" cellspacing="0" cellpadding="5">
<tr><td>
<?php
	$numeric_fields  = array("offer_id","offer_parent_id","place_latitude","place_longitude");
	$readonly_fields = array("offer_id");
	$req_fields = array("offer_title", "category");
	$failed_fields = array();
	$combo_list = array("offer_parent_id");
	//$place_types = array(array("store", "Store-food, supplies, etc"),array("food", "Food only"), array("vet", "Holistic vet"), array("hospital", "Hospital"));
	$place_types = array(array("store", "Store-food, supplies, etc"), array("hospital", "Vet or Hospital"));
	$categories = array(array("diet", "Diet"), array("grooming", "Grooming"), array("health", "Health"), array("tips", "Tips"), array("place", "Place/Business"));


	if (isset($_GET['table_name'])) {
		switch($_GET['table_name']) {
			case "offer":
				$req_fields = array("offer_title");
				break;
			default:
				//echo "Table not found: ". $_GET['table_name'] ."<br>\n";
				break;
		}
	}
?>

<div id="contentMain">
	<div id="login">
		<?php
			if (isset($_POST['logout'])) {
				echo 'Session ended  ';
			}
			if (isset($_SESSION['login'])) {
				echo "User: {$_SESSION['login']} [<a href=\"logout.php\">logout</a>]";
				//echo "[<a href=\"change_password.php\">change password</a>]";
			}
			else {
				echo "<a href=\"login.php\">Log In</a>";
				echo "&nbsp;&nbsp <i>test accounts: cam/cam donkey/donkey</i>";
			}
		?>
	</div>
	<p class="clear" />

	<div id="header">
		<h2><a href="./"><img src="../images/yin-yan-dog.jpg" width="100" height="100"></a> Holistic Dog Admin</h2>
	</div>

	<p class="clear" />

	<div id="topMenu">

		<font size=-2><a href="api.php" target=_new>[json api call]</a></font>
		<p>
		<ul>
			<li>Tree Views: <a href="tree.php">Everything</a> |
			<a href="tree.php?cat=diet">Diet</a> | <a href="tree.php?cat=grooming">Grooming</a> |
			<a href="tree.php?cat=health">Health</a> | <a href="tree.php?cat=tips">Tips</a> |
			<a href="tree.php?cat=place">Places</a></li>
			<?php
			$tablenames = array("offer");
			foreach ($tablenames as $tname) {
				echo "<li><a href=\"view.php?view_table={$tname}&table_name={$tname}\">View All Data in a List (table: {$tname})</a><br>\n";
			}
			?>
			<li><a href="insert.php">Add An Item</a></li>
		</ul>

	</div>
	<p class="clear" />
	<!--<hr size="1" noshade />-->
