var sayHello = angular.module('sayHello', []);

sayHello.config(function($routeProvider, $locationProvider) {
// $locationProvider.html5Mode(true);

$routeProvider
	.when('/', {templateUrl: './content/js/partials/_recordingList.html'})
	.when('/show/:userId', {templateUrl: './content/js/partials/_recordings.html', controller: 'ShowCtrl'})
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

// filter
sayHello.filter('fromNow', function() {
	return function(dateString) {
		return moment(new Date(dateString)).fromNow();
	}
});

var controllers = {};

controllers.AppCtrl = function($scope, $location, $http, recordingListFactory, recordingsFactory) {
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
		init($routeParams.userId);
	}
	$scope.recordings = [];

	function init(userId) {
		var recDiv = $('#rec_' + userId);
		recDiv.append('<div id="loader"><img src="./content/img/ajax-loader-1.gif" /></div>');
		// offset, limit DB
		var start = 0;
		var take = 2;

		recordingsFactory.getRecordings(userId, start, take).success(function(data) {
			$('#loader').remove();
			$scope.recordings = data;
		});
	}

	// "drop down" to show recordings using template
	$scope.dropDownRecs = function(userId) {
		init(userId);
		$scope.templates = [{name: '_recordings.html', url: './content/js/partials/_recordings.html'}];
		$scope.template = $scope.templates[0];
	};
};

sayHello.controller(controllers);





// sayHello.controller('AppCtrl', function($scope, $location, $http) {
// 	$scope.recordings = [
// 		{"username": "killingfloor", "date": "2013-02-22 06:47:22", "url": "test.wav"},
// 		{"username": "uhno", "date": "2013-02-22 09:48:25", "url": "test.wav"},
// 		{"username": "hasseman", "date": "2013-02-22 11:37:27", "url": "test.wav"}
// 	];
// });