<?php
session_start();
require_once('../database/DBConfig.php');
require_once('../database/Database.php');
require_once('../models/RecordingsModel.php');

// params
$activeUserId = $_SESSION['active_user_id'];
$userIdToShowRecordingsFor = $_GET['user_id'];


if ($activeUserId && $userIdToShowRecordingsFor) {
	$dbConfig = new DBConfig();
	$db = new Database($dbConfig);
	$db->connect();
	$recordingsModel = new RecordingsModel($db);

	echo json_encode($recordingsModel->getNewRecordings($activeUserId, $userIdToShowRecordingsFor));

	$db = null;
}
