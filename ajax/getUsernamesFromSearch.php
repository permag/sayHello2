<?php
require_once('../database/DBConfig.php');
require_once('../database/Database.php');
require_once('../models/UserModel.php');

$searchTerm = $_GET['term'];
if ($searchTerm) {
	$dbConfig = new DBConfig();
	$db = new Database($dbConfig);
	$db->connect();
	$userModel = new UserModel($db);
	
	// get usernames
	echo json_encode($userModel->getUsernamesFromSearch($searchTerm));

	$db = null;
}
