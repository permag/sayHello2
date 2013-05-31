$(function() {
	$('#registerUsername').focus();
	registerUsername();
});

function registerUsername() {
	$('#registerUsername').keyup(function(e) {
		var registerInfo = $('#registerInfo');
		registerInfo.empty();
		$.ajax({
			url: 'ajax/isUsernameAvailable.php',
			data: { username: $('#registerUsername').val() },
			type: 'GET',
			success: function(data) {
				if (data == '1') {
					$('#registerSubmit').removeAttr('disabled');
				} else if (data == '0') {
					$('#registerSubmit').attr('disabled', 'disabled');
					registerInfo.html('Username is already in use.');
				} else if (data == '2') {
					$('#registerSubmit').attr('disabled', 'disabled');
				}
			}
		});
		e.preventDefault();
	});

	$('#registerSubmit').click(function(e) {
		// ok, register user.
		$.ajax({
			url: 'ajax/registerUserThirdParty.php',
			data: { username: $('#registerUsername').val() },
			type: 'POST',
			success: function(data) {
				if (data == '1') {
					// ok registered.
					window.location.href = './';
				} else if (data == '0') {
					$('#registerInfo').html('Could not register.');
				}
			}
		});

		e.preventDefault();
	});
}