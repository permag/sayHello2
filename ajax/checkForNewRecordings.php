<?php
session_start();
require_once('../database/Database.php');
require_once('../database/DBConfig.php');
require_once('../models/RecordingsModel.php');

/**
 *  Get nr of new recordings using Long Polling
 */

// params
$activeUserId = $_SESSION['active_user_id'];
session_write_close(); // close session file. otherwise long polling will freeze other requests with sessions.


if ($activeUserId) {
	$dbConfig = new DBConfig();
	$db = new Database($dbConfig);
	$db->connect();
	$recordingsModel = new RecordingsModel($db);

	
	while (!$newRecordings = $recordingsModel->newRecordingsExist($activeUserId)) {
		sleep(10);
	}

	if ($newRecordings) {
		echo $newRecordings;
	} else {
		echo '0';
	}

	$db = null;
}
