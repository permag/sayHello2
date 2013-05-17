<?php
session_start();
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
	$userId = $userModel->getUserIdFromUsername($username);

	if ($userId != null) {
		// write session
		$_SESSION['recording_to_userId'] = $userId;
		echo json_encode($userId);
	}

	$db = null;
}
