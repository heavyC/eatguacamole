<?php
	function redirect_to( $location = NULL ) {
		if ($location != NULL) {
			header("Location: {$location}");
			exit;
		}
	}
	
	function logged_in() {
		return isset($_SESSION['login']);
	}

	function confirm_logged_in() {
		if (!logged_in()) {
			redirect_to("login.php");
		}
	}
?>