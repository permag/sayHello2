var sayHello = angular.module('sayHello', []);

sayHello.userColors = ['#d9d1d5', '#d4d8c2', '#d8cfbc', '#dac0bb', '#e0b6cc', '#ccb7d5', '#beadd7', '#aebed5', '#a5c4ce'];
sayHello.checkForNewInterval = null;

sayHello.config(function($routeProvider, $locationProvider) {
// $locationProvider.html5Mode(true);

$routeProvider
	.when('/', {templateUrl: './views/partials/_recordingList.html', controller: 'AppCtrl'})
	.when('/show/:userId', {templateUrl: './views/partials/_recordings.html', controller: 'ShowCtrl'})
	.otherwise({redirectTo: '/'});

});

// recordingListFactory
sayHello.factory('recordingListFactory', function($http) {
	var factory = {};
	factory.getRecordingList = function() {
		return $http({
			url: './ajax/getRecordingList.php',
			method: 'GET'
		});
	};
	return factory;
});

// newRecordingListFactory
sayHello.factory('newRecordingListFactory', function($http) {
	var factory = {};
	factory.getNewRecordingList = function() {
		return $http({
			url: './ajax/getNewRecordingList.php',
			method: 'GET'
		});
	};
	return factory;
});

// recordings factory
sayHello.factory('recordingsFactory', function($http) {
	var factory = {};
	factory.getRecordings = function(userId, start, take) {
		return $http({
			url: './ajax/getRecordings.php',
			method: 'GET',
			params: {'user_id': userId, 'start': start, 'take': take}
		});
	};
	return factory;
});

// event check factory
sayHello.factory('eventFactory', function($http) {
	var factory = {};
	factory.newRecordingsCount = function() {
		return $http({
			url: './ajax/checkForNewRecordings.php',
			method: 'GET'
		});
	};
	return factory;
});

// filter: time from now on dates
sayHello.filter('fromNow', function() {
	return function(dateString) {
		return moment(new Date(dateString)).fromNow();
	}
});

// filter: individual colors for users recordingList "folder"
sayHello.filter('userColor', function() {
	return function(username) {
		return sayHello.userColors[username.length - 1];
	}
});

// filter: mark new recording
sayHello.filter('markAsNew', function() {
	return function(value) {
		if (value == 'new') {
			return 'new';
		} else if (value == 'unheard') {
			return 'unheard';
		}
	};
});

// filter: remove new mark
sayHello.filter('removeNewMark', function() {
	return function(value) {
		if ((value != 0 || value != '0') && value != 'unheard') {
			return 'removeNewMark';
		} else {
			return null;
		}
	};
});

var controllers = {};

controllers.AppCtrl = function($scope, $location, $http, recordingListFactory) {
	$scope.recordingList = [];
	init();

	function init() {
		recordingListFactory.getRecordingList().success(function(data) {
			$scope.recordingList = data;
		});
	}
};

controllers.ShowCtrl = function($scope, $routeParams, recordingsFactory) {
	if ($routeParams.userId != null) {
		init($routeParams.userId, 0, 100);
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
		init(userId, 0, 12);
		$scope.templates = [{name: '_recordings.html', url: './views/partials/_recordings.html'}];
		$scope.templateRecordings = $scope.templates[0];
	};
};

controllers.ReplyCtrl = function($scope) {
	$scope.replyTo = function(username) {
		if (username.toLowerCase() == 'you') {
			$('#shareToUsername').val($('#topBarUsername').html());
		} else {
			$('#shareToUsername').val(username);
		}
	};
};

controllers.EventCtrl = function($scope, eventFactory, $compile, $filter, newRecordingListFactory, recordingsFactory) {
	getUserIdsWithNewRecordings();
	if (sayHello.checkForNewInterval == null) {
		newRecs();
	} else {
		clearInterval(sayHello.checkForNewInterval);
	}
	$scope.newRecordingList = [];

	sayHello.checkForNewInterval = window.setInterval(function() {
		newRecs();
		getUserIdsWithNewRecordings();
	}, 10000); // check for new recordings and auto update event notice

	function newRecs() {
		eventFactory.newRecordingsCount().success(function(data) {
			if (parseInt(data) > 0) {
				$('#topBarCountNewRecs').html(data).show();
			} else {
				$('#topBarCountNewRecs').empty().hide();
			}
		});
	}

	function getUserIdsWithNewRecordings() {
		$.ajax({
			url: './ajax/getUserIdsWithNewRecordings.php',
			type: 'GET',
			dataType: 'JSON',
			success: function(data) {
				$('.recordingDiv').find('.recListHasNewRecordings').empty();
				$.each(data, function(i, item) {
					$('#rec_' + item.owner_user_id).find('.recListHasNewRecordings').html('new');
				});
			}
		});
	}
	// function init() {
	// 	newRecordingListFactory.getNewRecordingList().success(function(data) {
	// 		$scope.newRecordingList = data;
	// 		$.each(data, function(i, item){
	// 			if ($('#rec_'+item.user_id).length > 0) {
	// 				$('#rec_'+item.user_id).hide().remove();
	// 			}
	// 		});
	// 	});
	// }
	// $scope.getNewRecordingList = function() {
	// 	init();
		
	// 	$scope.templates = [{name: '_newRecordingList.html', url: './views/partials/_newRecordingList.html'}];
	// 	$scope.templateList = $scope.templates[0];
	// };

	// $scope.dropDownNewRecs = function(userId) {
	// 	recordingsFactory.getRecordings(userId, 0, 2).success(function(data) {
	// 		var newDom = '';
	// 		$.each(data, function(i, item){
	// 			var dateTime = $filter('fromNow')(item.date_time);
	// 			newDom += '<div class="recordings"><ul><li>';
	// 			newDom += '<p title="'+item.date_time+'">'+item.username+', '+dateTime+'</p>';
	// 			newDom += '<audio ng-src="./recs/'+item.filename+'" controls></audio>';
	// 			newDom += '</li></ul></div>';

	// 		});
	// 		var compiledDom = $compile(newDom)($scope);
	// 		//$('#newRecordings_' +userId).append(newDom);
	// 		///////$('#newRecordings_' +userId).append(compiledDom);
	// 		//$('#container').append(compiledDom);
	// 		//console.log($('#newRecordings_' +userId).length)
	// 		//$('body').append($('#newRecordings_' +userId).html())
			
	// 		var testDom = $('#newRecordings_' +userId);
	// 		$('#newRecordings_' +userId).before(testDom.html());
	// 		$('#newRecordings_' +userId).remove();
	// 		testDom.append(compiledDom);

	// 	});
	// };

	// $scope.clickForNewRecordingList = function() {
	// 	$('#clickForNewRecordingList').click();
	// };
};

sayHello.controller(controllers);




