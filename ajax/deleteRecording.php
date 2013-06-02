<?php
session_start();
require_once('../database/Database.php');
require_once('../database/DBConfig.php');
require_once('../models/RecordingsModel.php');

// params
$activeUserId = $_SESSION['active_user_id'];
$recordingId = $_GET['recording_id'];

if ($activeUserId) {
	$dbConfig = new DBConfig();
	$db = new Database($dbConfig);
	$db->connect();
	$recordingsModel = new RecordingsModel($db);

	// get filename to delete
	$filenameToDelete = $recordingsModel->getRecordingFilename($recordingId);

	if ($filenameToDelete) {

		// delete recording in DB
		if ($recordingsModel->deleteRecordingDB($activeUserId, $recordingId)) {

			// delete recording file on disk
			$deleteFile = $recordingsModel->deleteRecordingFile($filenameToDelete);
		}
	}
	
	if ($deleteFile) {
		echo '1';
	} else {
		echo '0';
	}

	$db = null;
}
