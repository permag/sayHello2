<?php
session_start();
require_once('../models/RecordModel.php');

$recModel = new RecordModel(null);
if ($recModel->recordingIsUploaded()) {
	echo '1';
} else {
	echo '0';
}