
var controllers = {};

/**
 * App, recoredingList
 */
controllers.AppCtrl = function($scope, $location, $http, recordingListFactory) {
	$scope.recordingList = [];
	init();

	function init() {
		recordingListFactory.getRecordingList().success(function(data) {
			$scope.recordingList = data;
		});
	}
};


/**
 * Show recordings
 */
controllers.ShowCtrl = function($scope, $routeParams, recordingsFactory) {
	if ($routeParams.userId != null) {
		init($routeParams.userId, 0, 150);
	}
	$scope.recordings = [];

	// userId for which user has a conversation with, start offset, take limit
	function init(userId, start, take) {
		var recDiv = $('#rec_' + userId);
		recDiv.append('<div id="loader"><img src="./content/img/ajax-loader-1.gif" /></div>');

		recordingsFactory.getRecordings(userId, start, take).success(function(data) {
			$('#loader').remove();
			$scope.recordings = data;
		});
	}

	$scope.removeNewMark = function(recording_id) {
		var recElem = $('#rec_id_'+recording_id);
		var audioElem = recElem.find('audio');
		if (audioElem.hasClass('removeNewMark')) {
			// remove new and remove ng-click
			audioElem.removeClass('removeNewMark').removeAttr('ng-click');
			recElem.find('p.newRecordingMark').fadeOut()
			$.ajax({
				url: './ajax/removeNewMark.php',
				data: {recording_id: recording_id},
				type: 'POST',
				success: function(data) {

				}
			});
		}

	};

	// "drop down" to show recordings using template
	$scope.dropDownRecs = function(userId) {
		init(userId, 0, 7);
		$scope.templates = [{name: '_recordings.html', url: './views/partials/_recordings.html'}];
		$scope.templateRecordings = $scope.templates[0];
	};
};


/**
 * Reply by clicking on username
 */
controllers.ReplyCtrl = function($scope) {
	$scope.replyTo = function(username) {
		if (username.toLowerCase() == 'you') {
			$('#shareToUsername').val($('#topBarUsername').html());
		} else {
			$('#shareToUsername').val(username);
		}
	};
};


/**
 * Notifications
 */
controllers.NotificationCtrl = function($scope, $location, $timeout, notificationFactory) {
	if (sayHello.checkForNewInterval == null) { // prevent multiple intervals
		notificationBadge();
	} else {
		clearInterval(sayHello.checkForNewInterval);
	}
	$scope.newRecordingList = [];

	sayHello.checkForNewInterval = window.setInterval(function() {
		notificationBadge();
	}, 10000); // check for new recordings and auto update notification 

	function notificationBadge() {
		notificationFactory.newRecordingsCount().success(function(data) {
			if (parseInt(data) > 0) {
				$('#topBarCountNewRecs').html(data).show();
				document.title = 'sayHello. ('+data+')';
				sayHello.noticeBadgePrev = true;
				newMarkOnRecordingList();
				
			} else {
				$('#topBarCountNewRecs').empty().hide();
				document.title = 'sayHello.';
				if (sayHello.noticeBadgePrev) {
					newMarkOnRecordingList();
				}
				sayHello.noticeBadgePrev = false;
			}
		});
	}

	function newMarkOnRecordingList() {
		if ($location.path().substring(0,5) == '/show') {
			return false; // exit if location is "/show/..."
		}

		$.ajax({
			url: './ajax/getUserIdsWithNewRecordings.php',
			type: 'GET',
			dataType: 'JSON',
			success: function(data) {
				$timeout(function(){
					$('.recordingDiv').find('.recListHasNewRecordings').empty();
					var newIds = [];
					var elemIds = [];

					$.each(data, function(i, item) { 
						newIds.push(item.owner_user_id); // all userIds that sent "new" recs
						if ($('#rec_' + item.owner_user_id).length == 1) {
							elemIds.push(item.owner_user_id);
							$('#rec_' + item.owner_user_id).find('.recListHasNewRecordings').html('new');
						}
					});

					incomingConversation(newIds, elemIds);
				}, 1000, false);
			}

		});
	}

	function incomingConversation(newIds, elemIds) {
		if (newIds.length > 0) { // check if new userId is not in current list
			$('#newRecFromNewUser').empty();
			var checkNew = false;
			$.each(newIds, function(i, item) {
				if (!~$.inArray(item, elemIds)) {
					checkNew = true;
				}
			});
			if (checkNew) {
				$('#newRecFromNewUser').html('Incoming: new conversation! <a href="#">refresh</a>').show('slow');
			}
		}
	}
};

sayHello.controller(controllers);
sayHello.userColors = ['#d9d1d5', '#d4d8c2', '#d8cfbc', '#dac0bb', '#e0b6cc', '#ccb7d5', '#beadd7', '#aebed5', '#a5c4ce'];
sayHello.checkForNewInterval = null;
sayHello.noticeBadgePrev = false;
