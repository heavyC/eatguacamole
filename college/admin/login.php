<?php
	require_once("includes/connection.php");
	require_once("includes/functions.php");
	
	//ini_set('display_errors', 1);
	//error_reporting(E_ALL);

	
	if (isset($_GET['logout'])) {
		echo '<h3>Session ended by user.</h3>';
	}
	
	$login = "";
	$password = "";
	if (isset($_POST['submit'])) {
		$login = $_POST['login'];
		$password = $_POST['password'];
		$hashed_password = sha1($password);
		$query = "select user_login, first_name, last_name from user where user_login='{$login}' and password='{$hashed_password}' ";
		$result = mysql_query($query);
		
		if (mysql_num_rows($result) == 1) {
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			$_SESSION['login'] = $row['user_login'];
			$_SESSION['first_name'] = $row['first_name'];
			$_SESSION['last_name'] = $row['last_name'];
			redirect_to('index.php');
		}
		//echo "<h3>Login failed</h3>";
	}
	
	if (logged_in()) {
		//header('Location: {"index.php"}');
		redirect_to("view.php");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Admin</title>
    <link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
	<link rel="stylesheet" type="text/css" href="../style-ace.css" />
</head>
<body>

<!-- was here... -->

<div class="formDiv">
	<h3>Login</h3>
	<form action="login.php" method="post">
	<table border=0 cellpadding=5 cellspacing=0>
		<tr>
		<td>Login:</td><td><input value="<?php echo htmlentities($login); ?>" type="text" size="20" maxlength="20" name="login" /></td>
		</tr>
		<tr>
		<td>Password:</td><td><input value="" type="password" size="20" maxlength="20" name="password" /></td>
		</tr>
	</table>
	<input type="submit" name="submit" value="submit" />
	</form>
</div>

<div class="footerDiv">
	<script language="JavaScript" type="text/javaScript" src="http://www.EatGuacamole.com/copyright.js"></script>
</div>

</body>
</html>
