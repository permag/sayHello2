var sayHello = angular.module('sayHello', []);

sayHello.config(function($routeProvider, $locationProvider) {
// $locationProvider.html5Mode(true);

// $routeProvider.
// 	when('/', {templateUrl: './partials/_list.html'}).
// 	when('/new', {templateUrl: './partials/_edit.html', controller: 'NewCtrl'}).
// 	when('/edit/:id', {templateUrl: './partials/_edit.html', controller: 'EditCtrl'}).
// 	otherwise({redirectTo: '/'});

});

sayHello.factory('recordingsFactory', function($http) {
	var factory = {};
	factory.getRecordings = function() {
		return $http({
			url: 'content/test_data.json',
			method: 'GET'
		});
	};
	return factory;
});

sayHello.filter('fromNow', function() {
	return function(dateString) {
		return moment(new Date(dateString)).fromNow();
	}
});

var controllers = {};

controllers.AppCtrl = function($scope, $location, $http, recordingsFactory) {
	$scope.recordings = [];
	init();
	function init() {
		recordingsFactory.getRecordings().success(function(data){
			$scope.recordings = data;
		});
	}
};

sayHello.controller(controllers);





// sayHello.controller('AppCtrl', function($scope, $location, $http) {
// 	$scope.recordings = [
// 		{"username": "killingfloor", "date": "2013-02-22 06:47:22", "url": "test.wav"},
// 		{"username": "uhno", "date": "2013-02-22 09:48:25", "url": "test.wav"},
// 		{"username": "hasseman", "date": "2013-02-22 11:37:27", "url": "test.wav"}
// 	];
// });