<?php
require_once('../database/Database.php');
require_once('../database/DBConfig.php');
require_once('../models/UserModel.php');

// params
$username = $_GET['username'];

if ($username) {
	$dbConfig = new DBConfig();
	$db = new Database($dbConfig);
	$db->connect();
	$userModel = new UserModel($db);

	if ($userModel->isUsernameAvailable($username)) {
		echo '1'; // username is available
	} else {
		echo '0';
	}
	$db = null;
}
