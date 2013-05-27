<?php
require_once('../database/Database.php');
require_once('../database/DBConfig.php');
require_once('../models/UserModel.php');
require_once('../common/Validator.php');

// params
$username = trim($_GET['username']);

if ($username) {
	$dbConfig = new DBConfig();
	$db = new Database($dbConfig);
	$db->connect();
	$userModel = new UserModel($db);
	$validator = new Validator();

	if ($validator->ValidateUsername($username)) {

		if ($userModel->isUsernameAvailable($username)) {
			echo '1'; // username is available
		} else {
			echo '0';
		}
	} else {
		echo '2'; // too short or long
	}

	$db = null;
}
