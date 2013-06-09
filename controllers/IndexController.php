<?php
require_once('./models/UserModel.php');

class IndexController {

	private $_db = null;

	public function __construct(Database $db) {
		$this->_db = $db;
	}

	// control user-data on index.php
	public function userControl() {
		$userModel = new UserModel($this->_db);
		$userId = $userModel->getActiveUserId();
		if ($userId) {
			$user = $userModel->getUser($userId);
			return $user;
		} else {
			header('location: login.php');
		}
	}
}