var error_count;

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
				console.error(data);
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

//load pending table
$(document).ready(function(){
	getTimeAndDate();
	$.ajax({
		url: 'includes/load_pending_request',
		method: 'GET',
		beforeSend: function(){
			$('#loader').show();
		},
		success: function(data){
			$('#loader').hide();
			$('#pending-table').html(data);
			$('#pending-table').DataTable({
				"responsive" : true,
				"searching" : false,
				"ordering" : true,
				"order" : [[5,'asc']],
				"destroy" : true,
				"autoWidth" : false,
				pageLength : 5,
		   		lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
			}).columns([0,6,7,9]).visible(false);
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});

//when process button is clicked
$(document).on('click', '.process-request', function(){
	let id = $(this).attr('id');
	$('#modal-process-confirm').data('id', id);
	$('#modal-process-confirm').modal('show');
});
$('#modal-process-confirm').on('hidden.bs.modal', function(){
	$('#modal-process-confirm').data('id','');
});
//if yes btn is clicked on modal process confirm
$('#process-btn').on('click', function(){
	$.ajax({
		url: 'includes/update_pending_request_function_admin',
		method: 'POST',
		data: { status: 'PROCESSING', req_id: $('#modal-process-confirm').data('id') },
		beforeSend: function(){
			$('#loader').show();
			$('.decline-request').attr('disabled', true);
			$('.process-request').attr('disabled', true);
		},
		success: function(data){
			if(data == 'SUCCESS_UPDATE_REQ'){
				$('#loader').hide();
				$('#success-process').toast('show');
				$('.process-request').attr('disabled', false);
				$('.decline-request').attr('disabled', false);
				$('#pending-table').DataTable().row('#'+$('#modal-process-confirm').data('id')).remove().draw();
				$('#modal-process-confirm').modal('hide');
			}
			else if(data == 'FAILED_SEND'){
				alert('Request declined but failed to send SMS and Email Notification');
				$('#loader').hide();
				$('#success-process').toast('show');
				$('.process-request').attr('disabled', false);
				$('.decline-request').attr('disabled', false);
				$('#pending-table').DataTable().row('#'+id).remove().draw();
				$('#modal-process-confirm').modal('hide');
			}
			else if(data == 'EMAIL_ONLY_SENT'){
				alert('Request declined but failed to send SMS Notification');
				$('#loader').hide();
				$('#success-process').toast('show');
				$('.process-request').attr('disabled', false);
				$('.decline-request').attr('disabled', false);
				$('#pending-table').DataTable().row('#'+id).remove().draw();
				$('#modal-process-confirm').modal('hide');
			}
			else if(data == 'SMS_ONLY_SENT'){
				alert('Request declined but failed to send Email Notification');
				$('#loader').hide();
				$('#success-process').toast('show');
				$('.process-request').attr('disabled', false);
				$('.decline-request').attr('disabled', false);
				$('#pending-table').DataTable().row('#'+id).remove().draw();
				$('#modal-process-confirm').modal('hide');
			}
			else{
				alert('Oops, an error occurred.');
				console.error(data);
				$('#loader').hide();
				$('.process-request').attr('disabled', false);
				$('.decline-request').attr('disabled', false);
				$('#modal-process-confirm').modal('hide');
			}
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});

//when input in remarks field is triggered
$('#remarks-field').on('input', function(){
	if($('#remarks-field').hasClass('is-invalid')){
		$('#remarks-field').removeClass('is-invalid');
		error_count--;
	}
});

//when decline button in table is clicked
$(document).on('click', '.decline-request', function(){
	$('#modal-remarks').data('id', $(this).attr('id'));
	$('#modal-remarks').modal('show');
});
//when modal decline is closed
$('#modal-remarks').on('hidden.bs.modal', function(){
	$('#modal-remarks').data('id', "");
	$('#remarks-field').removeClass('is-invalid');
	$('#remarks-field').val("");
});

//when decline button on modal remarks is clicked
$('#submit-decline').on('click', function(){
	var remarks = $('#remarks-field').val().trim();
	var error_count = 0;

	if(remarks == ""){
		$('#remarks-field').addClass('is-invalid');
		error_count++;
	}

	if(error_count == 0){
		$.ajax({
			url: 'includes/update_pending_request_function_admin',
			method: 'POST',
			data: { status_decline: 'DECLINED', req_id_decline: $('#modal-remarks').data('id'), remarks_decline: remarks },
			beforeSend: function(){
				$('#loader-remarks').show();
				$('#submit-decline').attr('disabled', true);
				$('#close-remarks').attr('disabled', true);
				$('.decline-request').attr('disabled', true);
				$('.process-request').attr('disabled', true);
			},
			success: function(data){
				if(data == 'SUCCESS_UPDATE_REQ'){
					$('#loader-remarks').hide();
					$('#submit-decline').attr('disabled', false);
					$('#close-remarks').attr('disabled', false);
					$('.decline-request').attr('disabled', false);
					$('.process-request').attr('disabled', false);
					$('#pending-table').DataTable().row('#'+$('#modal-remarks').data('id')).remove().draw();
					$('#modal-remarks').modal('hide');
					$('#confirm-text').html('Request Declined');
					$('#success-process').toast('show');
				}
				else{
					alert('Oops, an error occurred.');
					console.error(data);
					$('#loader-remarks').hide();
					$('.process-request').attr('disabled', false);
					$('.decline-request').attr('disabled', false);
					$('#submit-decline').attr('disabled', false);
					$('#close-remarks').attr('disabled', false);
					$('#modal-remarks').modal('hide');
				}
			},
			error: function(xhr){
				alert('Oops, an error occurred.');
	        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
	    	}
		});
	}
});

$(document).on('click', '.view-info-btn', function(){
	let id = $(this).attr('id');

	$('#modal-view-info').modal('show');
	$.ajax({
		url: 'includes/view_info_modal',
		method: 'POST',
		data: { id: id },
		beforeSend: function(){
			$('#loader-view-info').show();
			$('#display-info').hide();
		},
		success: function(data){
			const json = JSON.parse(data);

			$('#loader-view-info').hide();
			$('#resident-photo').attr('src', 'uploaded image/profile pictures/' + json.picture);
			$('#full-name').html(json.fname + ' ' + json.mname + ' ' + json.lname + ' ' + json.suffix);
			$('#birthdate').html(json.bdate);
			$('#birthplace').html(json.bplace);
			$('#gender').html(json.gender);
			$('#address').html(json.address);
			$('#YOS').html(json.year_of_stay);
			$('#civil-status').html(json.civilstat);
			$('#email-address').html(json.email);
			$('#mobile-number').html(json.mobileno);
			$('#resident-voters').attr('src', 'uploaded image/voters id pictures/' + json.voters);

			$('#display-info').show();
		},
		error: function(xhr){
			alert('Oops, an error occurred.');
        	console.error('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
    	}
	});
});