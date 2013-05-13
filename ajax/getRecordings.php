<?php
session_start();
require_once('../database/DBConfig.php');
require_once('../database/Database.php');

$dbConfig = new DBConfig();
$db = new Database($dbConfig);
$db->connect();

$activeUserId = $_SESSION['active_user_id'];
$userIdToShowRecordingsFor = $_GET['user_id'];
$recordings = array();

// Select recordings dialog between active user and user with "clicked" user id
/**
 * När vi klickar på ett ID tillhörande Hasse
 *  vill vi se alla recordings som har ett TO_ID = hasse
 *  AND ett OWNER_ID = uhno,
 *  samt det motsatta.
 */
$stmt = $db->select("SELECT user.user_id, user.username, 
							recording.recording_id, recording.filename, recording.date_time, recording.to_user_id, recording.owner_user_id
					 FROM user
					 INNER JOIN recording
					 ON user.user_id = recording.owner_user_id 
					 WHERE recording.to_user_id = :userIdToShowRecordingsFor
					 AND recording.owner_user_id = :activeUserId
					 OR (recording.to_user_id = :activeUserId
					 AND recording.owner_user_id = :userIdToShowRecordingsFor)
					 ORDER BY recording.date_time DESC",
					 array(':userIdToShowRecordingsFor' => $userIdToShowRecordingsFor,
					 	   ':activeUserId' => $activeUserId));

$stmt->setFetchMode(PDO::FETCH_ASSOC);

while ($r = $stmt->fetch()) {
	array_push($recordings, $r);
}

$db = null;

echo json_encode($recordings);
