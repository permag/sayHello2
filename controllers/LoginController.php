<?php
session_start();
require_once('./models/UserModel.php');

class LoginController {

	private $_db = null;

	public function __construct(Database $db) {
		$this->_db = $db;
	}

	/**
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

	// public function fbLoginView($user, $user_profile) {
	// 	return '<img src="https://graph.facebook.com/'.$user.'/picture?type=large" class="profilePic">
	// 			<span class="profileName">'.$user_profile['first_name'] . ' ' . $user_profile['last_name'].'</span>
	// 			<button id="logoutLink" class="btn btn-mini">Logout</button>';
	// }
}