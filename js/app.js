var sayHello = angular.module('sayHello', []);

sayHello.config(function($routeProvider, $locationProvider) {

	$routeProvider
		.when('/', {templateUrl: './views/partials/_recordingList.html', controller: 'AppCtrl'})
		.when('/show/:userId', {templateUrl: './views/partials/_recordings.html', controller: 'ShowCtrl'})
		.otherwise({redirectTo: '/'});

});