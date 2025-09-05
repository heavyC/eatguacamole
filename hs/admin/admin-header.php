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

<head>
<title>Horse Spanish admin</title>
    <link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
    <link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" type="text/css" href="../style.css" />
	<link rel="stylesheet" type="text/css" href="http://www.geiser.net/guac/style-copyright.css" />
</head>
<body>

<table border="1" width="100%" cellspacing="0" cellpadding="5">
<tr><td>

<?php
	$numeric_fields  = array("cat_id", "word_id", "cat_seqno", "cat_vid", "word_vid");
	$readonly_fields = array("cat_id", "word_id");
	$req_fields = array();
	$failed_fields = array();
	$combo_list = array();
	
	if (isset($_GET['table_name'])) {
		switch($_GET['table_name']) {
			case "cat":
				$req_fields = array("cat_name");
				$combo_list = array("cat_cat_id");
				break;
			case "word":
				$req_fields = array("english");
				$combo_list = array("word_cat_id");
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
		<h2>Horse Spanish Admin</h2>
	</div>
	<font size=-1>
	<a href="admin/api.php?isUpdateForVersion=0" target=_new>example api 1</a> - 
	<a href="admin/api.php?getUpdatesForVersion=0" target=_new>example api 2</a><br>
	</font>
	<p class="clear" />
	
	<div id="topMenu">
		<ul>
			<li><a href="./">Admin Home</a></li>
			<?php
			$tablenames = array("cat", "word", "dbdelete", "dbversion");
			foreach ($tablenames as $tname) {
				echo "<li>View table: <a href=\"view.php?view_table={$tname}&table_name={$tname}\">{$tname}</a><br>\n";
			}
			?>
			<br>
			<li><a href="insert.php">Add Word or Category</a></li>
			<li><a href="release.php">Create new DB Release</a></li>
		</ul>
		
	</div>
	<p class="clear" />
	<hr size="1" noshade />