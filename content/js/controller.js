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
			url: 'ajax/getRecordingList.php',
			method: 'GET'
		});
	};
	return factory;
});

// recordings factory
sayHello.factory('recordingsFactory', function($http) {
	var factory = {};
	factory.getRecordings = function(userId) {
		return $http({
			url: 'ajax/getRecordings.php',
			method: 'GET',
			params: {'user_id': userId}
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
	//$scope.recordings = [];
	init();

	function init() {
		recordingListFactory.getRecordingList().success(function(data) {
			$scope.recordingList = data;
		});
	}



};

controllers.ShowCtrl = function($scope, $routeParams, recordingsFactory) {
	recordingsFactory.getRecordings($routeParams.userId).success(function(data) {
		$scope.recordings = data;
	});

	$scope.showRecs = function(userId) {
		$scope.recordings = [];
		$scope.templates = [{name: '_recordings.html', url: './content/js/partials/_recordings.html'}];
		$scope.template = $scope.templates[0];


		recordingsFactory.getRecordings(userId).success(function(data) {
			$scope.recordings = data;
		});

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