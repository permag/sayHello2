$(function(){

	// keypress
	$(document).keypress(function(e) {
	   var key = e.which;
	   if (key == 114 && e.ctrlKey) { // "r"
	   	$('#record').click();
	   } else if (key == 112 && e.ctrlKey) { // "p"
	   	$('#stop').click();
	   }
	});

	// // new recordings marking
	// $('.newRecording').slideDown('slow');
	// // remove recordings marking on click on audio player
	// $('.newRecording').find('audio').click(function(e){
	// 	$(this).parent().parent().removeClass('newRecording').addClass('newRecordingClicked');
	// });

	// // error messages
	// setTimeout(function(){
	// 	if ($('.errorMessages').html() != '') {
	// 		$('.errorMessages').slideDown('slow');
	// 	}
	// },9);
	// setTimeout(function(){
	// 	$('.errorMessages').slideUp('slow');
	// },3999);
	// $('.errorMessages').click(function(e){
	// 	$(this).slideUp('slow');
	// 	e.preventDefault();
	// });

	// follow scroll
	if (!($.browser.webkit)) {
		recorderFollowScroll();
  	} else {
  		$('#topBar').css('position', 'static');
  		$('#container').css('margin-top', '0');
  	}

	// username field
	$('#shareToUsername').focus(function(e){
		e.stopPropagation();
		if ($(this).val() == 'username'){
			$(this).val('');
			e.preventDefault();
		}
	});
	// confirm delete click event
	$('.deleteRec').click(function(e){
		if (confirmDelete("Are you sure you wish to delete this recording? \n\nIt will be removed from both users conversations.")){
			return;
		}
		e.preventDefault();
	});

	// ajax autocomplete to get usernames
	$("#shareToUsername").autocomplete({
	    source: function(request, response) {
	        $.ajax({
	            url: "./ajax/getUsernamesFromSearch.php",
	            data: {term: request.term},
	            dataType: "json",
	            success: function(data) {
	                response($.map(data, function(user) {
	                    return {
	                        value: user.username,
	                        image: user.image
	                    }
	                }));
	            }
	        });
	    }
	}).data('autocomplete')._renderItem = function(ul, item) {
        return $('<li></li>')
            .data('item.autocomplete', item)
            .append('<a><img src="' + item.image + '" class="ajaxSearchImage" /><span class="ajaxSearchUsername">' + item.value + '</span></a>')
            .appendTo(ul);
    };

	
	// confirm delete
	function confirmDelete(message) {
		var agree = confirm(message);
		if (agree) {
			return true;
		} else {
			return false;
		}
	}

	function recorderFollowScroll() {
		var top = $('#rightSection').offset().top - parseFloat($('#rightSection').css('marginTop').replace(/auto/, 0));
		$(window).scroll(function (event) {
			if ($(window).height() > 1) { // if window height < 500 dont follow scroll
				// what the y position of the scroll is
				var y = $(this).scrollTop() + 30; // + height of headerBar

				// whether that's below the form
				if (y >= top) {
				  // if so, ad the fixed class
				  $('#rightSection').addClass('fixed');
				} else {
				  // otherwise remove it
				  $('#rightSection').removeClass('fixed');
				}
			}
		});
	}

});