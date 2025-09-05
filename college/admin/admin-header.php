<?php
## DEBUG lines
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	
	require_once("includes/connection.php");
	require_once("includes/functions.php");
	require_once("includes/form_functions.php");
	
	$column_titles = array("college_id" => "ID", "college_url" => "Website", "college_name" => "Name",
	"college_subtitle" => "Subtitle", "college_type" => "Type", "college_location" => "Location",
	"college_deadline" => "Appl. Deadline", "college_test" => "Reqd. Tests", "college_fee" => "Fees", 
	"college_rec" => "Recs", "college_essay" => "Main Essay", "college_interview" => "Interview", 
	"college_suppl_art" => "Art Supplement", "college_suppl_essay" => "Supplemental Essay", 
	"apl"=>"Application Type", "tip_type"=>"Tip Type", "tip"=>"Tip", "intro"=>"Intro Text",
	"college_apl"=>"College to Application Type mapping table",
	"tip_type_vid"=>"Ver","tip_vid"=>"Ver","college_vid"=>"Ver", "dbversion"=>"Version");
	
	
	$skip_columns = array("college_school_year", "college_other1", "college_other2", "college_adm_type");
	$skip_edit_cols = array("college_vid", "college_apl_vid", "tip_vid", "tip_type_vid");
	
	confirm_logged_in();
?>

<head>
<title>All College Essays admin</title>
    <link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
    <link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" type="text/css" href="../style-ace.css" />
</head>

<script language="javascript">

function validateDelete() {
	alert("Delete currently disabled.");
	return;
}

function validateForm() {
	if (validateRadio(editForm.editId)) {
		editForm.submit();
	}
	else {
		alert("Please select a row to edit.");
	}
}

function validateRadio(btn) {
    var cnt = -1;
    for (var i=btn.length-1; i > -1; i--) {
        if (btn[i].checked) {cnt = i; i = -1;}
    }
    if (cnt > -1) return btn[cnt].value;
    else return null;
}
</script>

<body>

<table border="1" width="100%" cellspacing="0" cellpadding="5">
<tr><td>


<?php
	$numeric_fields  = array("apl_id", "apl_display_order", "adm_id", "adm_display_order", 
	"college_id", "college_apl_type", "college_apl_type2", "college_adm_type", 
	"tip_type_id", "tip_type_display_order", "tip_id", "apl_vid", "college_vid",
	"tip_vid", "tip_type_vid", "intro_vid", "college_apl_vid");
	$readonly_fields = array("apl_id", "adm_id", "college_id", 
	"tip_type_id", "tip_id");
	
	$failed_fields = array();
	$combo_list = array();
	
	$req_fields = array();
	if (isset($_POST['table_name'])) {
		switch($_POST['table_name']) {
			case "tip":
				$req_fields = array("tip_text1", "tip_type_id");
				break;
			case "tip_type":
				$req_fields = array("tip_type_title");
				break;
			default:
				echo('No required fields for table:  $_GET["table_name"] <br>\n');
				break;
		}
	}
?>

<div id="contentMain">
	<table width=700 border=0 cellpadding=0 cellspacing=0>
	<tr><td>
		<div id="login">
			<?php
				echo "<img src=\"../images/AceIcon57.png\" align=left border=1>";

				if (isset($_POST['logout'])) {
					echo 'Session ended  ';
				}
				if (isset($_SESSION['login'])) {
					echo "User: {$_SESSION['login']} [<a href=\"logout.php\">logout</a>]<br>";
					//echo "[<a href=\"change_password.php\">change password</a>]";
				}
				else {
					echo "<a href=\"login.php\">Log In</a>";
					echo "&nbsp;&nbsp <i>test accounts: cam/cam donkey/donkey</i><br>";
				}
				echo "<a href=\".\">Admin Home</a><br>";
				echo "<a href=\"..\">Main Website</a><br>";
			?>
		</div>
	</td>	
	<td align="center">
		<div id="header">
			<h2>All College Essays Admin</h2>
		</div>
	</td></tr>
	</table>

	<!-- Static links -->
	<font size=-1><a href="api.php?isUpdateForVersion=0" target=_new>api 1</a> ::
	<a href="api.php?getUpdatesForVersion=0" target=_new>api 2</a></xfont>
	<p>
	<p><a href="insert.php">Add New Stuff</a> :: 
	<a href="release.php">Create New DB Release</a>
	</font>
	<p class="clear" />
<?php
	$combo_list = array("");
	$tablenames = array("apl", "tip_type", "tip", "intro", "college_apl", "dbversion");
	
	echo "<a href=\"view-c.php?view_table=college&table_name=college\">Colleges</a><br>\n";
	foreach ($tablenames as $tname) {
		$str = $column_titles[$tname];
		echo "<a href=\"view.php?view_table={$tname}&table_name={$tname}\">{$str}</a><br>\n";
	}
	
	echo "<p><hr size=1 noshade><p>";
?>
