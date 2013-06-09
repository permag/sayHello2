<?php
session_start();
require_once('./models/UserModel.php');

class LoginController {

	private $_db = null;

	public function __construct(Database $db) {
		$this->_db = $db;
	}

	/**
	 * control login
	 * @param $user: userId from FB API
	 */
	public function loginControl($user) {
		$userModel = new UserModel($this->_db);
		$thirdPartyType = 1;
		$thirdPartyId = $user;
		// check if facebook user already is registered here.
		$userId = $userModel->getUserIdFromThirdParty($thirdPartyType, $thirdPartyId);
		if ($userId) {
			$userModel->setActiveUserIdSession($userId);
			header('location: ./');
		} else {
			// register user: let user choose username
			$_SESSION['third_party_type'] = $thirdPartyType;
			$_SESSION['third_party_id'] = $thirdPartyId;
			header('location: ./registerusername.php');
		}
	}

}