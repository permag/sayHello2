<?php
session_start();

require_once('database/DBConfig.php');
require_once('database/Database.php');
require_once('models/RecordModel.php');

/**
 * Called from jRecorder API, JavaScript/Flash, called when clicked send recording
 */
class RecordCall {

	/**
	 * Call RecordController to save recording
	 */
	public function init() {
		$dbConfig = new DBConfig();
		$db = new Database($dbConfig);
		// DB-connect
		$db->connect();

		$rec = $this->saveRecording($db);

		// kill DB-conn
		$db = null;
	}

	public function saveRecording($db) {
		$ownerUserId = $_SESSION['active_user_id']; // test
		$toUserId = $_SESSION['recording_to_userId'];
		unset($_SESSION['recording_to_userId']);
		
		$recModel = new RecordModel($db);
		$filename = $recModel->setRecordingFilename($ownerUserId);
		$filenameAndExt = $recModel->saveRecordingToFile($filename);

		$recId = $recModel->insertRecording($ownerUserId, $toUserId, $filenameAndExt);

	}
}

if (isset($_SESSION['active_user_id']) && isset($_SESSION['recording_to_userId'])) {
	$recordCall = new RecordCall();
	$recordCall->init();
}






