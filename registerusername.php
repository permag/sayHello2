<?php
session_start();
require_once('./thirdparty/fb/src/facebook.php');
require_once('./thirdparty/fb/fb.php');

if (isset($_SESSION['active_user_id'])) {
	header('location: ./');
} else if (!isset($_SESSION['active_user_id']) && $user) {
	// ...
} else {
	header('location: ./login.php');
}
?>
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/reset.css" />
		<link rel="stylesheet" type="text/css" href="css/login-layout.css" />
		<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
		<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
		<title>sayHello</title>
	</head>
	<body>
		<div id="container">
			<h1>First...</h1>
			<h2>Choose a username</h2>
			<div>
				<p id="regText">a-z A-Z 0-9. Min 5, max 11 characters.</p>
				<input type="text" id="registerUsername" />
				<button id="registerSubmit" disabled="disabled">Ok!</button> 
				<div id="registerInfo"></div>
			</div>
		</div>

		<script src="./js/register/register.js"></script>
	</body>
</html>