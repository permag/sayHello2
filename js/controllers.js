
var controllers = {};

/**
 * App, recordingList
 */
controllers.AppCtrl = function($scope, $location, $http, recordingListFactory) {
	$scope.recordingList = [];
	init();

	function init() {
		recordingListFactory.getRecordingList().success(function(data) {
			$scope.recordingList = data;
			// if ($scope.recordingList.length == 0 && $('#noConversations').length < 1) {
			// 	$('#recordingList').prepend('<div id="noConversations">No conversations.</div>')
			// }
		});
	}
};


/**
 * Show recordings
 */
controllers.ShowCtrl = function($scope, $routeParams, $timeout, $location, recordingsFactory) {
	if ($routeParams.userId != null) {
		init($routeParams.userId, 0, 100);
	}
	$scope.recordings = [];

	// userId for which user has a conversation with, start offset, take limit
	function init(userId, start, take) {
		sayHello.rec_number_counter = 0;

		$timeout(function(){
			getNewRecordings();
		},700);

		var recDiv = $('#rec_' + userId);
		recDiv.append('<div id="loader"><img src="./content/img/ajax-loader-1.gif" /></div>');

		// get all recordings
		recordingsFactory.getRecordings(userId, start, take).success(function(data) {
			$('#loader').remove();
			$scope.recordings = data;
		});
	}

	function getNewRecordings() {

		if ($location.path().substring(0,5) != '/show') { // only on show page
			return false;
		}

		recordingsFactory.getNewRecordings($routeParams.userId).success(function(data) {
			
			updateNewAndUnheard(data); // update unheard/new status in scope

			if (data.length > 0) {

				// get all prev rec ids from scope
				var preIds = [];
				$.each($scope.recordings, function(i, preRec) {
					preIds.push(preRec.recording_id);
				});

				// check if new rec ids exist in prev scope
				var checkNew = false;
				var newRecsToInsert = [];
				$.each(data, function(i, item) {
					if (!~$.inArray(item.recording_id, preIds)) {
						checkNew = true;
						newRecsToInsert.push(item);
					}
				});

				// new recordings that are NOT already in view, exists.
				// add them to view.
				if (checkNew) {
					$.each(newRecsToInsert, function(i, item) {
						sayHello.rec_number_counter--;
						item.rec_number = sayHello.rec_number_counter;
						$scope.recordings.push(item);							
					});
				}
			}
		});
		// clear interval to prevent multiple intervals on multiple calls, and start new interval
		clearInterval(sayHello.getNewRecordingsInterval);
		sayHello.getNewRecordingsInterval = setInterval(function() {
			getNewRecordings();
		}, 7000);
	}

	function updateNewAndUnheard(data) {
		// if scope recording does NOT exists in DB data recordings
		// update scope recoring to new = null
		
		// each rec from scope
		$.each($scope.recordings, function(i, scopeRec) {

			$scope.recordings[i].new = 0;
			// each rec from DB
			$.each(data, function(ii, DBrec) {
				//
				if (scopeRec.recording_id == DBrec.recording_id) {
					$scope.recordings[i].new = DBrec.new;
				}
			});
		});

	}

	$scope.removeNewMark = function(recording_id) {
		var recElem = $('#rec_id_'+recording_id);
		var audioElem = recElem.find('audio');
		if (audioElem.hasClass('removeNewMark')) {
			// remove new and remove ng-click
			audioElem.removeClass('removeNewMark').removeAttr('ng-click');
			recElem.find('p.newRecordingMark').empty();
			$.ajax({
				url: './ajax/removeNewMark.php',
				data: {recording_id: recording_id},
				type: 'POST',
				success: function(data) {
					// ...
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

	// delte recoring on click
	$scope.deleteRecording = function($index) {
		var recToDelete = $scope.recordings[$index];

		// confirm delete click event
		if (confirm("Are you sure you wish to delete this recording? \n\nIt will be removed from both users conversation.")){

			// delete recoring
			recordingsFactory.deleteRecording(recToDelete.recording_id).success(function(data) {
				if (data > 0) {
					$('#rec_id_' + recToDelete.recording_id).fadeOut(333);
					$timeout(function() {
						$scope.recordings.splice($index, 1);
					}, 444);
				}
			});

			return;
		} else {
			return false;
		}
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
controllers.NotificationCtrl = function($scope, $location, $timeout) {
	notificationLongPolling();


	function notificationLongPolling() {
		var number = null;

		$.ajax({
			url: './ajax/checkForNewRecordings.php',
			timeout: 40000,
			cache: false,
			success: function(data) {
				//
				number = data;
				if (parseInt(number) > 0) {
					$timeout(function(){
						notificationLongPolling();
					}, 10000);
				} else {
					notificationLongPolling();
				}
				updateBadge(number);
			},
			error: function(e, jqXHR) {
				if (jqXHR == 'timeout') {
					notificationLongPolling();
				}
			}
		
		});
		setTimeout(function(){
			if (number == null) {
				updateBadge(number);
			}
		},2000);
	}

	function updateBadge(number) {
		if (number > 0) { // number of notifications to display
			sayHello.newContentExists = true;
			// update badge
			$('#topBarCountNewRecs').html(number).show();
			document.title = 'sayHello. ('+number+')';
			newMarkOnRecordingList();
		
		} else { // null
			sayHello.newContentExists = false;
			// remove badge
			$('#topBarCountNewRecs').empty().hide();
			document.title = 'sayHello.';
			newMarkOnRecordingList();
		}
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
				$('.recordingDiv').find('.recListHasNewRecordings').empty();
				var newIds = [];
				$.each(data, function(i, item) { 
					newIds.push(item.owner_user_id); // all userIds that sent "new" recs
					if ($('#rec_' + item.owner_user_id).length == 1) {
						$('#rec_' + item.owner_user_id).find('.recListHasNewRecordings').html('new');
					}
				});
				$timeout(function() {
					incomingConversation(newIds);
				}, 3000);
			}

		});
	}

	function incomingConversation(newIds) {
		if ($('#filterInput').val() != '') { // prevent this to run when filtering. (will show message even if no new.)
			return false;
		}

		if (newIds.length > 0) { // check if new userId is not in current list
			var elemIds = [];
			$.each(newIds, function(i, id) {
				if ($('#rec_' + id).length == 1) {
					elemIds.push(id);
				}
			});

			// check if ids of users that sent new recs are not the current dom 
			var checkNew = false;
			$.each(newIds, function(i, item) {
				if (!~$.inArray(item, elemIds)) {
					checkNew = true;
				}
			});
			if (checkNew) {
				$('#newRecFromNewUser').html('Incoming: new conversation! <a href="#">refresh</a>').show('slow');
			} else {
				$('#newRecFromNewUser').empty();
			}
		}
	}
};

sayHello.controller(controllers);
sayHello.userColors = ['#d9d1d5', '#d4d8c2', '#d8cfbc', '#dac0bb', '#e0b6cc', '#ccb7d5', '#beadd7', '#aebed5', '#a5c4ce'];
sayHello.rec_number_counter = 0;
sayHello.newContentExists = false;
sayHello.getNewRecordingsInterval = null;
sayHello.getNewRecordingsAfterSendTimer = null;

