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

		$stmt = $this->_db->select("SELECT DISTINCT user.user_id, user.username, user.third_party_id, user.third_party_type
									,(SELECT recording.date_time
										FROM recording 
										WHERE recording.to_user_id = user.user_id
										AND recording.owner_user_id = :activeUserId
										OR recording.owner_user_id = user.user_id
										AND recording.to_user_id = :activeUserId
										ORDER BY recording.date_time DESC
										LIMIT 1) AS date_time
									,(SELECT COUNT(recording.date_time)
										FROM recording 
										WHERE recording.to_user_id = user.user_id
										AND recording.owner_user_id = :activeUserId
										OR recording.owner_user_id = user.user_id
										AND recording.to_user_id = :activeUserId) AS rec_count
									,(SELECT recording.new 
										FROM recording 
										WHERE recording.owner_user_id = user.user_id
										AND recording.to_user_id = :activeUserId
										ORDER BY recording.new DESC
										LIMIT 1) AS new
									FROM user
									INNER JOIN recording
									ON user.user_id = recording.owner_user_id OR user.user_id = recording.to_user_id
									WHERE recording.owner_user_id = :activeUserId
									OR recording.to_user_id = :activeUserId
									ORDER BY new DESC, date_time DESC",
									array(':activeUserId' => $activeUserId));

		$stmt->setFetchMode(PDO::FETCH_ASSOC);

		while ($r = $stmt->fetch()) {
			$tmp = array();
			$tmp['user_id'] = $r['user_id'];
			//$tmp['username'] = ($r['user_id'] == $activeUserId) ? 'You' : $r['username'];
			$tmp['username'] = $r['username'];
			$tmp['date_time'] = date('D M j G:i:s (T) Y', strtotime($r['date_time']));
			$tmp['rec_count'] = $r['rec_count'];
			$tmp['new'] = ($r['new'] == 1) ? 'new' : null;
			if ($r['third_party_type'] == 1) {
				$tmp['image'] = 'https://graph.facebook.com/'.$r['third_party_id'].'/picture?type=square';
			}
			array_push($recordingList, $tmp);
		}
		return $recordingList;
	}

	public function getUserIdsWithNewRecordings($activeUserId) {
		$ownerUserIds = array();
		$stmt = $this->_db->select("SELECT DISTINCT owner_user_id
									FROM recording
									WHERE to_user_id = :activeUserId
									AND new = 1",
									array(':activeUserId' => $activeUserId));

		$stmt->setFetchMode(PDO::FETCH_ASSOC);

		while ($r = $stmt->fetch()) {
			$tmp = array();
			$tmp['owner_user_id'] = $r['owner_user_id'];
			array_push($ownerUserIds, $tmp);
		}
		return $ownerUserIds;
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

		$stmt = $this->_db->select("SELECT user.user_id, user.username, user.third_party_id, user.third_party_type,
										   recording.recording_id, recording.filename, recording.date_time, 
										   recording.to_user_id, recording.owner_user_id, recording.new
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

		$count = 0;
		while ($r = $stmt->fetch()) {
			$tmp = array();
			$tmp['rec_number'] = ++$count;
			$tmp['user_id'] = $r['user_id'];
			//$tmp['username'] = ($r['user_id'] == $activeUserId) ? 'You' : $r['username'];
			$tmp['username'] = $r['username'];
			$tmp['recording_id'] = $r['recording_id'];
			$tmp['filename'] = $r['filename'];
			$tmp['date_time'] = date('D M j G:i:s (T) Y', strtotime($r['date_time']));
			$tmp['to_user_id'] = $r['to_user_id'];
			$tmp['owner_user_id'] = $r['owner_user_id'];
			if ($r['third_party_type'] == 1) {
				$tmp['image'] = 'https://graph.facebook.com/'.$r['third_party_id'].'/picture?type=square';
			}

			if ($r['new'] == 1 && $r['to_user_id'] == $activeUserId) { // new to you, from friend
				$tmp['new'] = 'new';
			} else if ($r['new'] == 1 && $r['owner_user_id'] == $activeUserId) { // from you, unheard to friend
				$tmp['new'] = 'unheard';
			} else {
				$tmp['new'] = 0;
			}

			array_push($recordings, $tmp);
		}
		return $recordings;
	}


	public function getNewRecordings($activeUserId, $userIdToShowRecordingsFor) {
		$recordings = array();

		$stmt = $this->_db->select("SELECT user.user_id, user.username, user.third_party_id, user.third_party_type,
										   recording.recording_id, recording.filename, recording.date_time, 
										   recording.to_user_id, recording.owner_user_id, recording.new
									FROM user
									INNER JOIN recording
									ON user.user_id = recording.owner_user_id 
									WHERE (recording.to_user_id = :userIdToShowRecordingsFor
									AND recording.owner_user_id = :activeUserId
									OR (recording.to_user_id = :activeUserId
									AND recording.owner_user_id = :userIdToShowRecordingsFor))
									AND recording.new = 1 
									ORDER BY recording.date_time DESC",
									array(':userIdToShowRecordingsFor' => $userIdToShowRecordingsFor,
										   ':activeUserId' => $activeUserId));

		$stmt->setFetchMode(PDO::FETCH_ASSOC);

		while ($r = $stmt->fetch()) {
			$tmp = array();
			$tmp['user_id'] = $r['user_id'];
			//$tmp['username'] = ($r['user_id'] == $activeUserId) ? 'You' : $r['username'];
			$tmp['username'] = $r['username'];
			$tmp['recording_id'] = $r['recording_id'];
			$tmp['filename'] = $r['filename'];
			$tmp['date_time'] = date('D M j G:i:s (T) Y', strtotime($r['date_time']));
			$tmp['to_user_id'] = $r['to_user_id'];
			$tmp['owner_user_id'] = $r['owner_user_id'];
			if ($r['third_party_type'] == 1) {
				$tmp['image'] = 'https://graph.facebook.com/'.$r['third_party_id'].'/picture?type=square';
			}

			if ($r['new'] == 1 && $r['to_user_id'] == $activeUserId) { // new to you, from friend
				$tmp['new'] = 'new';
			} else if ($r['new'] == 1 && $r['owner_user_id'] == $activeUserId) { // from you, unheard to friend
				$tmp['new'] = 'unheard';
			} else {
				$tmp['new'] = 0;
			}

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
			// $tmp['username'] = ($r['user_id'] == $activeUserId) ? 'You' : $r['username'];
			$tmp['username'] = $r['username'];
			array_push($recordingList, $tmp);
		}
		return $recordingList;
	}

	public function removeNewMark($activeUserId, $recordingId) {
		$stmt = $this->_db->update("UPDATE recording
									SET new = 0
									WHERE recording_id = :recordingId
									AND to_user_id = :activeUserId",
									array(':recordingId' => $recordingId,
										  ':activeUserId' => $activeUserId));
		return $stmt; // rowcount
	}
}