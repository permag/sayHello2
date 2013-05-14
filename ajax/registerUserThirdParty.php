<?php
session_start();

require_once('../database/Database.php');
require_once('../database/DBConfig.php');
require_once('../models/UserModel.php');

//
$thirdPartyType = $_SESSION['third_party_type'];
$thirdPartyId = $_SESSION['third_party_id'];
$username = $_POST['username'];

if (isset($username)) {
	$dbConfig = new DBConfig();
	$db = new Database($dbConfig);
	$db->connect();
	$userModel = new UserModel($db);

	if ($userModel->registerUserThirdParty($thirdPartyType, $thirdPartyId, $username)) {
		unset($_SESSION['third_party_type']);
		unset($_SESSION['third_party_id']);
		$userModel->setActiveUserIdSession($db->lastInsertId());
		echo '1';
	} else {
		echo '0';
	}

	$db = null;
}
