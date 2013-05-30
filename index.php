<?php
error_reporting(E_ALL);
session_start();
require_once('./database/DBConfig.php');
require_once('./database/Database.php');
require_once('./controllers/IndexController.php');

$dbConfig = new DBConfig();
$db = new Database($dbConfig);
$db->connect();
$indexController = new IndexController($db);
$user = $indexController->userControl();
$db = null;

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="content/css/reset.css" />
		<link rel="stylesheet" type="text/css" href="content/css/index-layout.css" />
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" />
		<title>sayHello.</title>
	</head>
	<body ng-app="sayHello" ng-controller="AppCtrl">

		<div id="fb-root"></div>

		<div id="topBar">
			<div id="topBarWrap">
				<div id="topBarLogo">sayHello.</div>
				<div id="topBarRefresh"><a href="#">
					<img src="./content/img/reload.png"></a>
					<a href="#"><div id="topBarCountNewRecs"></div></a>
				</div>
				<div id="topBarUserContainer">
					<div id="topBarLogout"><button id="logoutLink">Logout</button></div>
					<div id="topBarUsername"><?php echo $user->username; ?></div>
				</div>
			</div>
		</div>
		<div id="container">
			<div id="leftSection">
				<div id="sideMenu">
					<img id="profilePhoto" src="<?php echo $user->profilePhotoUrl; ?>">
					<p id="usernameBox"><?php echo $user->username; ?></p>
					<p><a href="./#/show/<?php echo $user->userId; ?>">mono</a></p>
				</div>
			</div>

			<div id="mainSection">
				<div id="audioListHeaderContainer">
					<h3 id="audioListHeader"><a href="#">Conversations</a></h3>
					<div id="filterContainer">
						<input id="filterInput" type="text" ng-model="filter.username" placeholder="Filter..."/>
					</div>
				</div>


				<div id="recordingList">

					<div ng-view></div>

				</div>

			</div>


			<div id="rightSectionWrapper">
				<div id="rightSection">
					<div id="recorderContainer">

					<div id="recorderTime">
					  Time: <span id="time">00:00</span>
					</div>
					<div id="levelbase">
					  <div id="levelbar"></div>
					</div>
					<div id="recorderLevel">
					  Level: <span id="level"></span>
					</div>  
					<div id="recorderStatus">
					  Status: <span id="status"></span>
					</div>

					<button id="record">Record</button>
					<button id="stop">Stop/Play</button>
					
					</div>
					<div id="shareToUsernameContainer">
						<input type="text" id="shareToUsername" value="username" />
						<button id="send">Send recording</button>
					</div>
				</div>
			</div>

		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.5/angular.js"></script>
		<!-- <script src="http://code.angularjs.org/1.0.6/angular-resource.min.js"></script> -->
		<script src="content/js/moment.js"></script>
		<script src="content/js/controller.js"></script>
		<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
		<script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>

		<script src="./content/js/fb_login.js"></script>
		<script src="./content/js/rec/jRecorder.js"></script>
		<script src="./content/js/rec/init.js"></script>
		<script src="./content/js/rec/recInit.js"></script>
		<script src="./content/js/rec/recEvent.js"></script>

	</body>
</html>