<?php
require_once('../database/DBConfig.php');
require_once('../database/Database.php');

$searchTerm = $_GET['term'];
if (isset($searchTerm)) {
	$dbConfig = new DBConfig();
	$db = new Database($dbConfig);
	$db->connect();

	// get usernames //
	
	// array for JSON result
	$usernames = array();

	// get username suggestions
	$stmt = $db->select("SELECT username FROM user WHERE username LIKE :username LIMIT 27", 
						 array(':username' => '%'.$searchTerm.'%'));
	
	$stmt->setFetchMode(PDO::FETCH_ASSOC);

	while ($r = $stmt->fetch()) {
		array_push($usernames, $r);
	}

	$db = null;

	echo json_encode($usernames);
}
