$(function(){
	 // var to check if rec been pressed since page reload
	var recordingExists = false;
	stopped = false;

	// background beep
	 $('body').append('<div id="recBeepDiv"><audio id="recBeep" src="./content/audio/recBeep.wav" controls hidden></audio><audio id="recBeepSend" src="./content/audio/recBeepSend.wav" controls hidden></audio></div>');

	
	$('#record').click(function(){
		$('#recBeep').trigger('play');
		setTimeout(function(){
			$.jRecorder.record(20);
		}, 421);
		stopped = false;
		recordingExists = true;

		$(this).css({color: 'red'});
	});

	$('#stop').click(function(){

		if (recordingExists == true) {
	 		$.jRecorder.stop();

	 		$('#record').css({color: '#bd0001'});
	 	}

	});

	$('#send').click(function(){
		var sendButton = $(this);

		if (recordingExists == true && stopped == true) {

			sendButton.attr('disabled', 'disabled');
			var shareToUsername = $('#shareToUsername').val();
			// call ajax script
			$.ajax({
				type: 'get',
				url: './ajax/getUserIdFromUsername.php',
				data: { username: shareToUsername },
				cache: false,
				dataType: 'json',
				success: function(data){
					if (typeof parseInt(data) == 'number' && data != null) {
						$.jRecorder.sendData();
						$('#shareToUsername').val('');

						// check when upload is finished.
						var checkFinished = setInterval(function(){
							$.ajax({
								type: 'get',
								url: './ajax/getRecordingUploadStatus.php',
								cache: false,
								success: function(data){
									if (data == '1') {
										clearInterval(checkFinished);
										backend_finished_sending();
										sendButton.removeAttr('disabled');
									}
								}

							});
						},1500);

						return true;
					} else {
						if ($.trim(shareToUsername) == 'username' || $.trim(shareToUsername) == '') {
							$('#status').html('Enter a username');
							sendButton.removeAttr('disabled');
						} else {
							$('#status').html('Username not found');
							sendButton.removeAttr('disabled');
						}
					}
				}
			});
		} else {
			$('#status').html('No recording exists');
		}
	});

});


function callback_finished(){
  $('#status').html('Recording finished');
}

function callback_started(){
  $('#status').html('Recording started');
}

function callback_error(code){
  $('#status').html('Error, code:' + code);
}

function callback_stopped(){
 	$('#status').html('Stopped');

	if (stopped == false) {
		$('#recBeep').trigger('play');
		$('#record').css({color: '#bd0001'});
	}
	stopped = true;
}

function callback_finished_recording(){
  $('#status').html('Recording finished');
}

function callback_finished_sending(){
 	// $('#status').html('Sending recording...');
 	// setTimeout(function(){
 	// 	$('#status').html('Recording was sent');
 	// 	$('#recBeepSend').trigger('play');
 	// }, 2345);
}

function backend_finished_sending() {
	$('#status').html('Sending recording...');
	$('#status').html('Recording was sent');
	$('#recBeepSend').trigger('play');
}

function callback_activityLevel(level){
	$('#level').html(level);

	if(level == -1){
	  $('#levelbar').css('width',  '2px');
	
	} else {
	  $('#levelbar').css('width', (level * 2)+ 'px');
	}
}

function callback_activityTime(time){
	//$('.flrecorder').css('width', '1px'); 
	//$('.flrecorder').css('height', '1px'); 
	$('#time').html(time);
}           
						           