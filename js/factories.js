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

	factory.getNewRecordings = function(userId) {
		return $http({
			url: './ajax/getNewRecordings.php',
			method: 'GET',
			params: {'user_id': userId}
		});
	};
	return factory;
});


// notification check factory
sayHello.factory('notificationFactory', function($http) {
	var factory = {};
	factory.newRecordingsCount = function() {
		return $http({
			url: './ajax/checkForNewRecordings.php',
			method: 'GET'
		});
	};
	return factory;
});