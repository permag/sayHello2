<?php
session_start();
require_once('../database/DBConfig.php');
require_once('../database/Database.php');
require_once('../models/RecordingsModel.php');

// params
$activeUserId = $_SESSION['active_user_id'];
$recordingId = trim($_POST['recording_id']);

if ($activeUserId && $recordingId) {
	$dbConfig = new DBConfig();
	$db = new Database($dbConfig);
	$db->connect();
	$recordingsModel = new RecordingsModel($db);

	echo $recordingsModel->removeNewMark($activeUserId, $recordingId);

	$db = null;
}