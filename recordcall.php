<?php
session_start();

require_once('database/DBConfig.php');
require_once('database/Database.php');
require_once('models/RecordModel.php');

/**
 * Called when clicked send recording
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

		$this->saveRecording($db);

		// kill DB-conn
		$db = null;
	}

	public function saveRecording($db) {
		$recModel = new RecordModel($db);
		$recModel->setRecordingUploadStatus(false); // set a session to indicate recording began uploading

		$ownerUserId = $_SESSION['active_user_id']; 
		$toUserId = $_SESSION['recording_to_userId'];
		unset($_SESSION['recording_to_userId']);
		
		$filename = $recModel->setRecordingFilename($ownerUserId);
		if ($filename != null) {
			$filenameAndExt = $recModel->saveRecordingToFile($filename);
			$recId = $recModel->insertRecording($ownerUserId, $toUserId, $filenameAndExt);

		}

		$recModel->setRecordingUploadStatus(true); // all finished
	}
}

if (isset($_SESSION['active_user_id']) && isset($_SESSION['recording_to_userId'])) {
	$recordCall = new RecordCall();
	$recordCall->init();
}

