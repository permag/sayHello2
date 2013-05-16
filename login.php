<?php 

require('src/facebook.php');
require_once('fb.php');
require_once('./database/DBConfig.php');
require_once('./database/Database.php');
require_once('./models/UserModel.php');

if ($user) {
	//$_SESSION['user_id'] = $user_profile['id'];
	// $user: facebook user is authorized.

	//
	$dbConfig = new DBConfig();
	$db = new Database($dbConfig);
	$db->connect();
	$userModel = new UserModel($db);

	$thirdPartyType = 1;
	$thirdPartyId = $user;
	// check if facebook user already is registered here.
	$userId = $userModel->getUserIdThirdParty($thirdPartyType, $thirdPartyId);
	if ($userId) {
		$userModel->setActiveUserIdSession($userId);
		header('location: ./');
	} else {
		// register user: let user choose username
		$_SESSION['third_party_type'] = $thirdPartyType;
		$_SESSION['third_party_id'] = $thirdPartyId;
		header('location: ./registerusername.php');
	}
}

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
	<head>
		<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
		<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
		<title>sayHello</title>

	</head>
	<body>

		<!-- FB LOGIN -->
		<div id="fb-root"></div>

		<div class="container">

			<?php 
			if ($user):
					echo '<img src="https://graph.facebook.com/'.$user.'/picture?type=large" class="profilePic">
								<span class="profileName">'.$user_profile['first_name'] . ' ' . $user_profile['last_name'].'</span>
								<button id="logoutLink" class="btn btn-mini">Logout</button>';
			endif ?>

			<?php if (!$user): ?>
				<h1>sayHello</h1>
				<strong><em>Login using your Facebook account: </em></strong>
				<button id="loginLink" class="btn">Login</button>
			<?php endif ?>
		</div>

		<!-- JavaScript files -->
		<script src="./content/js/fb_login.js"></script>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<?php if ($user): ?>
		<?php endif ?>
	</body>
</html>
