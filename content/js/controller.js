var sayHello = angular.module('sayHello', []);

sayHello.userColors = ['#d9d1d5', '#d4d8c2', '#d8cfbc', '#dac0bb', '#e0b6cc', '#ccb7d5', '#beadd7', '#aebed5', '#a5c4ce'];

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

	// "drop down" to show recordings using template
	$scope.dropDownRecs = function(userId) {
		init(userId, 0, 2);
		$scope.templates = [{name: '_recordings.html', url: './views/partials/_recordings.html'}];
		$scope.template = $scope.templates[0];
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

sayHello.controller(controllers);

