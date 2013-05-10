<?php
session_start();

require_once('../database/Database.php');
require_once('../database/DBConfig.php');

//
$username = $_GET['username'];

if (isset($username)) {
	$dbConfig = new DBConfig();
	$db = new Database($dbConfig);
	$db->connect();

	// get userId from username
	$theUserId = null;
	$ret = $db->select("SELECT user_id FROM user WHERE username = :username", 
							 array(':username' => $username));
	$db = null;

	if ($ret != null || $ret != '') {
		foreach ($ret as $row) {
			$theUserId = $row['user_id'];
		}
	
		if ($theUserId != null || $theUserId != '') {
			// write session
			$_SESSION['recording_to_userId'] = $theUserId;
			echo json_encode($theUserId);
		}
	}
}
