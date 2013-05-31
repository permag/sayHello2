<?php 
require_once('./thirdparty/fb/src/facebook.php');
require_once('./thirdparty/fb/fb.php');
require_once('./database/DBConfig.php');
require_once('./database/Database.php');
require_once('./models/UserModel.php');
require_once('./controllers/LoginController.php');


// FACEBOOK LOGIN
if ($user) {
	// $user: facebook user is authorized ($user: fb userId).
	$dbConfig = new DBConfig();
	$db = new Database($dbConfig);
	$db->connect();
	$loginController = new LoginController($db);
	$loginController->loginControl($user);
	$db = null;
}

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
	<head>
		<link rel="stylesheet" type="text/css" href="css/reset.css" />
		<link rel="stylesheet" type="text/css" href="css/login-layout.css" />
		<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
		<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
		<title>sayHello</title>

	</head>
	<body>

		<!-- FB LOGIN -->
		<div id="fb-root"></div>

		<div id="container" class="container">

			<?php if (!$user): ?>
				<h1>sayHello</h1>
				<strong><em>Login using your Facebook account: </em></strong>
				<button id="loginLink" class="btn">Login</button>
			<?php endif ?>
		</div>

		<!-- JavaScript files -->
		<script src="./js/thirdparty/fb_login.js"></script>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	</body>
</html>
