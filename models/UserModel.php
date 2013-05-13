<?php

class UserModel {

	private $_user_id = 0;
	private $_fname = '';
	private $_lname = '';
	private $_email = '';
	private $_username = '';
	private $_third_party_id = null;
	private $_third_party_type = null;

	private $_db = null;

	public function __construct(Database $db) {
		$this->_db = $db;
	}

	public function getUser($username=null, $third_party_id=null, $third_party_type=null) {
		if ($username) {
			// get user from DB and set active session id
		
		} else if ($third_party_id && $third_party_type) {
			// get user from DB and set active id session and "party-sessions" too
		} 
	}
}