<?php
session_start();
require_once('../database/Database.php');
require_once('../database/DBConfig.php');
require_once('../models/RecordingsModel.php');

// params
$activeUserId = $_SESSION['active_user_id'];


if ($activeUserId) {
	$dbConfig = new DBConfig();
	$db = new Database($dbConfig);
	$db->connect();
	$recordingsModel = new RecordingsModel($db);

	$newRecordings = $recordingsModel->newRecordingsExist($activeUserId);

	if ($newRecordings) {
		echo $newRecordings;
	}

	$db = null;
}
