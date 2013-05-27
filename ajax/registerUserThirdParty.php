<?php
session_start();
require_once('../database/Database.php');
require_once('../database/DBConfig.php');
require_once('../models/UserModel.php');
require_once('../common/Validator.php');

// params
$thirdPartyType = $_SESSION['third_party_type'];
$thirdPartyId = $_SESSION['third_party_id'];
$username = trim($_POST['username']);

if ($username && isset($thirdPartyType) && isset($thirdPartyId )) {
	$dbConfig = new DBConfig();
	$db = new Database($dbConfig);
	$db->connect();
	$userModel = new UserModel($db);
	$validator = new Validator();

	if ($userModel->getActiveUserId()) { // user already logged in
		echo '0';
		return false;
	}

	if (!$validator->ValidateUsername($username)) {
		echo '0';
		return false;
	}

	// register
	if ($userModel->registerUserThirdParty($thirdPartyType, $thirdPartyId, $username)) {
		unset($_SESSION['third_party_type']);
		unset($_SESSION['third_party_id']);
		$userModel->setActiveUserIdSession($db->lastInsertId());
		echo '1';
	} else {
		echo '0';
	}

	$db = null;
	
} else {
	echo '0';
}

