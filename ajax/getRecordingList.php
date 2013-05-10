<?php
session_start();
require_once('../database/DBConfig.php');
require_once('../database/Database.php');

$dbConfig = new DBConfig();
$db = new Database($dbConfig);
$db->connect();

$recordingList = array();

$stmt = $db->select("SELECT DISTINCT user.user_id, user.username 
					 FROM user
					 INNER JOIN recording
					 ON user.user_id = recording.owner_user_id 
					 WHERE recording.to_user_id = :to_user_id",
					 array(':to_user_id' => $_SESSION['active_user_id']));

$stmt->setFetchMode(PDO::FETCH_ASSOC);

while ($r = $stmt->fetch()) {
	array_push($recordingList, $r);
}

$db = null;

echo json_encode($recordingList);
