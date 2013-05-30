// filter: time from now on dates
sayHello.filter('fromNow', function() {
	return function(dateString) {
		return moment(new Date(dateString)).fromNow();
	}
});


// filter: individual colors for users recordingList "folder"
sayHello.filter('userColor', function() {
	return function(username) {
		if (username.toLowerCase() == 'you') {
			return '#d8cfbc';
		}
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
		} else if (value == 0 || value == '0') {
			return null;
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