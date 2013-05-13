<?php

class UserModel {

	private $_db = null;

	public function __construct(Database $db) {
		$this->_db = $db;
	}

	// Authenticate native users on login
	public function authenticateUserNative($username, $password) {
		if ($username && $password) {
			// get user from DB and set active session id
			$stmt = $this->_db->select("SELECT user_id
										FROM user 
										WHERE username = :username AND password = :password",
										array(':username' => $username, ':password' => $password));
			while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$userId = $r['user_id'];
			}
			$stmt = null;

			if ($userId != null || $userId != '' || $userId != 0) {
				return $userId;
			}
		
		} else {
			return null;
		} 
	}

	// get user data for user from user_id
	public function getUser($userId) {
		$user = new stdClass();
		$stmt = $this->_db->select("SELECT user_id, fname, lname, email, username, third_party_id, third_party_type
									FROM user
									WHERE user_id = :user_id",
									array(':user_id' => $userId));
		while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$user->userId = $r['user_id'];
			$user->fname = $r['fname'];
			$user->lname = $r['lname'];
			$user->email = $r['email'];
			$user->username = $r['username'];
			$user->thirdPartyType = $r['third_party_type'];
			$user->thirdPartyId = $r['third_party_id']; 
		}
		$stmt = null;

		return $user;
	}

	// get user_id (pk) for third party user
	public function getUserIdThirdParty($thirdPartyType, $thirdPartyId) {
		// thirdPartyType 1 = facebook
		// thirdPartyType 2 = google+
		
		if ($thirdPartyType && $thirdPartyId) {

			$stmt = $this->_db->select("SELECT user_id 
										FROM user 
										WHERE third_party_type = :third_party_type
										AND third_party_id = :third_party_id",
										array(':third_party_type' => $thirdPartyType,
											  ':third_party_id' => $thirdPartyId));
			while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$userId = $r['user_id'];
			}
			if ($userId != null || $userId != '' || $userId != 0) {
				return $userId;
			} else {
				return null;
			}
		}
	}

	public function setActiveUserIdSession($userId) {
		if ($userId != null || $userId != '') {
			$_SESSION['active_user_id'] = $userId;
		}
	}

	public function unsetActiveUserIdSession() {
		unset($_SESSION['active_user_id']);
	}
}