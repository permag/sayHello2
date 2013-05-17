<?php
require_once('./models/UserModel.php');

class IndexController {

	private $_db = null;

	public function __construct(Database $db) {
		$this->_db = $db;
	}

	public function userControl() {
		$userModel = new UserModel($this->_db);
		$userId = $userModel->getActiveUserId();
		$user = $userModel->getUser($userId);
		return $user;
	}
}