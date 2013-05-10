<?php
session_start();
require_once('models/RecordModel.php');


/**
 * Controls the Recorder box
 * record sound and saves file to disk and in DB
 */
class RecordController {

	public function saveRecording(Datebase $db) {
		$ownerUserId = 1; // test
		$toUserId = $_SESSION['recording_to_userId'];
		unset($_SESSION['recording_to_userId']);
		
		$recModel = new RecordModel($db);
		$filename = $recModel->setRecordingFilename($ownerUserId);
		$filenameAndExt = $recModel->saveRecordingToFile($filename);

		$recId = $recModel->insertRecording($ownerUserId, $toUserId, $filenameAndExt);


	}
}