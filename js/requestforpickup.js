//check the OS if user is using a computer
$(document).ready(function(){
	if(navigator.appVersion.indexOf('Android') != -1 || navigator.appVersion.indexOf('like Mac') != -1){
		alert('This site is unavailable on mobile phones, please try a different device, Thank you!');
		window.location.href = 'page_unavailable';
		window.close();
	}
});
//when logout button is clicked
$('#logout-admin').on('click', function(){
	$('#modal-logout-confirm').modal('show');
});
$('#logout-btn').click(function(){
	$.ajax({
		url: 'includes/admin_logout',
		success: function(data){
			if(data == 'LOGOUT'){
				window.location.href = 'admin';
			}
			else{
				alert('Oops, an error occurred.');
				console.error(data)
			}
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});

//Get current time and date
setInterval(getTimeAndDate, 1000);
function getTimeAndDate(){
	var currentDate = new Date();
	const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
	const week = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
	var month, day, dayOfTheWeek, year, hours, minute, seconds, timePeriod;

	month = months[currentDate.getMonth()];
	day = currentDate.getDate();
	dayOfTheWeek = week[currentDate.getDay()];
	year = currentDate.getFullYear();

	if(currentDate.getHours() > 12){
		hours = currentDate.getHours() - 12;
		timePeriod = 'PM';
	}
	else{
		hours = currentDate.getHours();
		timePeriod = 'AM';
	}

	if(currentDate.getHours() == 0){
		hours = '12';
	}

	if(hours > 0 && hours < 10){
		hours = '0' + hours;
	}
	
	minute = currentDate.getMinutes();

	if(minute >= 0 && minute < 10){
		minute = '0' + minute;
	}

	seconds = currentDate.getSeconds();

	if(seconds >= 0 && seconds < 10){
		seconds = '0' + seconds;
	}

	$('#date-today').html(dayOfTheWeek + ', ' + month + " " + day + ", " + year);
	$('#current-time').html(hours + ":" + minute + ":" + seconds + " " + timePeriod);
}

//load table for pickup request
$(document).ready(function(){
	getTimeAndDate();
	//load table data
	$.ajax({
		url: 'includes/load_forpickup_request',
		method: 'GET',
		beforeSend: function(){
			$('#loader').show();
		},
		success: function(data){
			$('#loader').hide();
			$('#forpickup-table').html(data);
			$('#forpickup-table').DataTable({
				"responsive" : true,
				"searching" : true,
				"ordering" : false,
				"destroy" : true,
				"autoWidth" : false,
				pageLength : 5,
		   		lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
			}).columns([0,2,6,7]).visible(false);
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});

//when mark as completed button is clicked
$(document).on('click', '.complete-request', function(){
	let id = $(this).attr('id');
	$('#modal-complete-confirm').data('id', id);
	$('#modal-complete-confirm').modal('show');
});
//complete request when yes button is clicked on popup
$('#complete-btn').click(function(){
	$.ajax({
		url: 'includes/update_forpickup_request_function_admin',
		method: 'POST',
		data: { complete_req_no: $('#modal-complete-confirm').data('id') },
		beforeSend: function(){
			$('#loader').show();
			$('.complete-request').attr('disabled', true);
			$('#modal-complete-confirm').modal('hide');
		},
		success: function(data){
			if(data == 'SUCCESS_COMPLETE_REQ'){
				$('#loader').hide();
				$('.complete-request').attr('disabled', false);
				$('#success-process').toast('show');
				$('#forpickup-table').DataTable().row('#'+$('#modal-complete-confirm').data('id')).remove().draw();
			}
			else{
				alert('Oops, an error occurred.');
				console.error(data);
				$('#loader').hide();
				$('.complete-request').attr('disabled', false);
			}
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});
$('#modal-loading-process').on('hidden.bs.modal', function(){
	$('#loader-pickup').hide();
	$('#success-process').show();
});