<?php

class RecordingsModel {

	private $_db = null;

	public function __construct(Database $db) {
		$this->_db = $db;
	}

	/**
	 * List of persons you user has dialog with
	 */
	public function getRecordingList($activeUserId) {
		$recordingList = array();

		$stmt = $this->_db->select("SELECT DISTINCT user.user_id, user.username
									FROM user
									INNER JOIN recording
									ON user.user_id = recording.owner_user_id OR user.user_id = recording.to_user_id
									WHERE recording.owner_user_id = :activeUserId
									OR recording.to_user_id = :activeUserId
									ORDER BY recording.date_time DESC",
									array(':activeUserId' => $activeUserId));

		$stmt->setFetchMode(PDO::FETCH_ASSOC);

		while ($r = $stmt->fetch()) {
			$tmp = array();
			$tmp['user_id'] = $r['user_id'];
			$tmp['username'] = ($r['user_id'] == $activeUserId) ? 'You' : $r['username'];
			array_push($recordingList, $tmp);
		}
		return $recordingList;
	}

	/**
	* Select recordings dialog between active user and user with "clicked" user id
	* När vi klickar på ett ID tillhörande Hasse
	* vill vi se alla recordings som har ett TO_ID = hasse
	* AND ett OWNER_ID = uhno,
	* samt det motsatta.
	*/
	public function getRecordings($activeUserId, $userIdToShowRecordingsFor, $start, $take) {
		$recordings = array();

		$stmt = $this->_db->select("SELECT user.user_id, user.username, 
										   recording.recording_id, recording.filename, recording.date_time, 
										   recording.to_user_id, recording.owner_user_id
									FROM user
									INNER JOIN recording
									ON user.user_id = recording.owner_user_id 
									WHERE recording.to_user_id = :userIdToShowRecordingsFor
									AND recording.owner_user_id = :activeUserId
									OR (recording.to_user_id = :activeUserId
									AND recording.owner_user_id = :userIdToShowRecordingsFor)
									ORDER BY recording.date_time DESC 
									LIMIT ".$start.", ".$take."",
									array(':userIdToShowRecordingsFor' => $userIdToShowRecordingsFor,
										   ':activeUserId' => $activeUserId));

		$stmt->setFetchMode(PDO::FETCH_ASSOC);

		while ($r = $stmt->fetch()) {
			$tmp = array();
			$tmp['user_id'] = $r['user_id'];
			$tmp['username'] = ($r['user_id'] == $activeUserId) ? 'You' : $r['username'];
			$tmp['recording_id'] = $r['recording_id'];
			$tmp['filename'] = $r['filename'];
			$tmp['date_time'] = date('D M j G:i:s (T) Y', strtotime($r['date_time']));
			$tmp['to_user_id'] = $r['to_user_id'];
			$tmp['owner_user_id'] = $r['owner_user_id'];

			array_push($recordings, $tmp);
		}
		return $recordings;
	}

	/**
	 * @param  $activeUserId 
	 * @return nr of new recs
	 */
	public function newRecordingsExist($activeUserId) {
		$stmt = $this->_db->selectCountAll("SELECT COUNT(*) 
											FROM recording
											WHERE new = 1
											AND to_user_id = :activeUserId",
											array(':activeUserId' => $activeUserId));

		if ($stmt > 0) {
			return $stmt;
		} else {
			return false;
		}
	}

	public function getNewRecordingList($activeUserId) {
		$recordingList = array();
		
		$stmt = $this->_db->select("SELECT DISTINCT user.user_id, user.username
									FROM user
									INNER JOIN recording
									ON user.user_id = recording.owner_user_id OR user.user_id = recording.to_user_id
									WHERE (recording.owner_user_id = :activeUserId
									OR recording.to_user_id = :activeUserId)
									AND recording.new = 1
									ORDER BY recording.date_time DESC",
									array(':activeUserId' => $activeUserId));

		$stmt->setFetchMode(PDO::FETCH_ASSOC);

		while ($r = $stmt->fetch()) {
			$tmp = array();
			$tmp['user_id'] = $r['user_id'];
			$tmp['username'] = ($r['user_id'] == $activeUserId) ? 'You' : $r['username'];
			array_push($recordingList, $tmp);
		}
		return $recordingList;
	}
}