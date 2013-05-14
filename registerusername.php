<!doctype html>
<html>
	<head>
		<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
		<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
		<title>sayHello</title>
	</head>
	<body>
		<div>
			<input type="text" id="registerUsername" />
			<button id="registerSubmit" disabled="disabled">Ok!</button>
		</div>
		<script type="text/javascript">
			$('#registerUsername').keyup(function(e) {
				$.ajax({
					url: 'ajax/isUsernameAvailable.php',
					data: { username: $('#registerUsername').val() },
					type: 'GET',
					success: function(data) {
						if (data == '1') {
							$('#registerSubmit').removeAttr('disabled');
						} else {
							$('#registerSubmit').attr('disabled', 'disabled');
						}
					}
				});
				e.preventDefault();
			});

			$('#registerSubmit').click(function(e) {
				alert("jah")
				// ok, register user.
				$.ajax({
					url: 'ajax/registerUserThirdParty.php',
					data: { username: $('#registerUsername').val() },
					type: 'POST',
					success: function(data) {
						if (data == '1') {
							// ok registered.
							alert('registered!');
						}
					}
				});
	
				e.preventDefault();
			});
		</script>
	</body>
</html>